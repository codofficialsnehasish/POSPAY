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
        $validated = $request->validate([
            'seller_name' => 'required|string',
            'vendor_id' => 'required|integer',
            'purchase_number' => 'required|string',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.batch_number' => 'nullable|string',
            'items.*.expiry_date' => 'nullable|date'
        ]);

        // Create the purchase header
        $purchase = Purchase::create([
            'seller_name' => $validated['seller_name'],
            'vendor_id' => $validated['vendor_id'],
            'purchase_number' => $validated['purchase_number'],
            'purchase_date' => $validated['purchase_date'],
            'total_amount' => $validated['total_amount'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            // Create purchase item
            $purchaseItem = PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'batch_number' => $item['batch_number'] ?? strtoupper('BATCH-' . uniqid()),
                'expiry_date' => $item['expiry_date'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
            ]);

            // Update stock transaction for this batch
            $lastStock = StockTransaction::where('product_id', $item['product_id'])
                                        ->latest('id')->first();
            $openingBalance = $lastStock->closing_balance ?? 0;
            $closingBalance = $openingBalance + $item['quantity'];

            StockTransaction::create([
                'product_id' => $item['product_id'],
                'batch_number' => $purchaseItem->batch_number,
                'transaction_type' => 'purchase',
                'transaction_date' => $validated['purchase_date'],
                'quantity_in' => $item['quantity'],
                'quantity_out' => 0,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
                'expiry_date' => $purchaseItem->expiry_date,
            ]);
        }

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
