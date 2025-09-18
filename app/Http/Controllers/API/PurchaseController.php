<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\ProductVariationOption;
use App\Models\StockTransaction;
use App\Models\SellerMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    // 🟢 List all purchases
    public function index()
    {
        // Eager load items + product to avoid N+1 queries
        $purchases = Purchase::with('items.product')->get();

        return response()->json([
            'status' => true,
            'purchases' => $purchases,
        ]);
    }

    // 🟢 Show single purchase
    public function show($id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);

        return response()->json([
            'status' => true,
            'purchase' => $purchase,
        ]);
    }

    public function get_products(Request $request)
    {
        $vendorIds = $request->user()->vendors->pluck('id');

        // Load products with variations and their options
        $products = Product::with('variations.options')
            ->where('is_visible', 1)
            ->whereIn('vendor_id', $vendorIds)
            ->get();

        $data = $products->flatMap(function ($product) {
            return $product->variations->flatMap(function ($variation) use ($product) {
                return $variation->options->map(function ($option) use ($product, $variation) {
                    return [
                        'product_id'           => $product->id,
                        'variation_id'         => $variation->id,
                        'variation_option_id'  => $option->id,
                        'product_name'         => $product->name . '-' . $option->name, // 👈 option name here
                        'product_price'        => $option->price ?? $variation->price ?? $product->price,
                        'stock'                => $option->quantity,
                        'product_image'        => getProductMainImage($product->id),
                    ];
                });
            });
        });

        return response()->json([
            'status' => true,
            'data' => $data->values() // clean re-index
        ]);
    }

    public function search_products(Request $request)
    {
        $vendorIds = $request->user()->vendors->pluck('id');
        $search = $request->input('search');

        // Query products visible and belong to vendor
        $productsQuery = Product::with(['variations.options'])
            ->where('is_visible', 1)
            ->whereIn('vendor_id', $vendorIds);

        if ($search) {
            $productsQuery->where(function($query) use ($search) {
                // Search in product name
                $query->where('name', 'like', "%{$search}%")
                    // Search in variation options
                    ->orWhereHas('variations.options', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('price', $search) // exact match for price
                        ->orWhere('barcode', 'like', "%{$search}%");
                    });
            });
        }

        $products = $productsQuery->get();

        // Flatten data for API
        $data = $products->flatMap(function ($product) {
            return $product->variations->flatMap(function ($variation) use ($product) {
                return $variation->options->map(function ($option) use ($product, $variation) {
                    return [
                        'product_id'           => $product->id,
                        'variation_id'         => $variation->id,
                        'variation_option_id'  => $option->id,
                        'product_name'         => $product->name . ' - ' . $option->name,
                        'product_price'        => $option->price ?? $variation->price ?? $product->price,
                        'stock'                => $option->quantity,
                        'barcode'              => $option->barcode,
                        'product_image'        => getProductMainImage($product->id),
                    ];
                });
            });
        });

        return response()->json([
            'status' => true,
            'data'   => $data->values()
        ]);
    }

    public function get_seller(Request $request){
        $vendorIds = $request->user()->vendors->pluck('id');
        $sellers = SellerMaster::whereIn('vendor_id', $vendorIds)->get();

        return response()->json([
            'status' => true,
            'sellers' => $sellers,
        ]);
    }

    // 🟢 Create new purchase with items

    public function storePurchase(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required|integer',
            'invoice_no' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer',
            'products.*.veriation_option_id' => 'required|integer',
            'products.*.batch_no' => 'nullable',
            'products.*.expriay_date' => 'nullable',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.mrp' => 'required|numeric|min:0',
            'products.*.discount_rate' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $vendorId = $request->user()->vendor->id;

        // 2️⃣ Create purchase header
        $purchase = Purchase::create([
            'seller_name' => $validated['seller_id'], // map seller_id to seller_name or your table column
            'vendor_id' => $vendorId, // you can pass vendor_id from request if needed
            'invoice_number' => $validated['invoice_no'],
            'purchase_date' => now(), // or pass from request
            'total_amount' => 0, // will calculate below
            'notes' => $request->notes ?? null,
        ]);

        $totalAmount = 0;

        // 3️⃣ Loop through products
        foreach ($validated['products'] as $product) {
            $lineTotal = $product['quantity'] * $product['mrp'];
            $totalAmount += $lineTotal;

            $purchaseItem = PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'veriation_option_id' =>  $product['veriation_option_id'],
                'batch_number' => $product['batch_no'],
                'expiry_date' => date('Y-m-d', strtotime($product['expriay_date'])),
                'quantity' => $product['quantity'],
                'price' => $product['mrp'],
                'total' => $lineTotal,
            ]);

            // 4️⃣ Stock transaction logic
            $lastStock = StockTransaction::where('product_id', $product['product_id'])->latest('id')->first();
            $openingBalance = $lastStock->closing_balance ?? 0;
            $closingBalance = $openingBalance + $product['quantity'];

            StockTransaction::create([
                'product_id' => $product['product_id'],
                'veriation_option_id' =>  $product['veriation_option_id'],
                'batch_number' => $purchaseItem->batch_number,
                'transaction_type' => 'purchase',
                'transaction_date' => now(),
                'quantity_in' => $product['quantity'],
                'quantity_out' => 0,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
                'expiry_date' => null,
            ]);

            $ProductVariationOption = ProductVariationOption::find($product['veriation_option_id']);
            $ProductVariationOption->quantity += $product['quantity'];
            $ProductVariationOption->update();
        }

        // update total amount in purchase header
        $purchase->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Purchase created successfully',
            'purchase' => $purchase->load('items.product')
        ], 201);
    }


    // 🟡 Update purchase
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            'purchase_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|integer|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        $purchase->update($validated);

        if ($request->has('items')) {
            // delete old items and reinsert
            $purchase->items()->delete();

            foreach ($request->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            $purchase->update([
                'total_amount' => $purchase->items->sum('total'),
            ]);
        }

        return response()->json([
            'message' => 'Purchase updated successfully',
            'purchase' => $purchase->load('items.product')
        ]);
    }

    // 🔴 Delete purchase
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted successfully']);
    }
}
