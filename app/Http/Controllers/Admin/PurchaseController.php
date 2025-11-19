<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariationOption;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SellerMaster;
use App\Models\User;
use App\Models\StockTransaction;
use App\Models\VendorProduct;
use App\Models\VendorProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            $purchases = Purchase::with('items.product')->latest()->get();
        } else if($user->hasRole('Admin')) {
            $vendors = User::role('Vendor')->where('admin_id',$user->id)->pluck('id')->toArray();
            $purchases = Purchase::with('items.product')->whereIn('vendor_id',$vendors)->latest()->get();
        }else if($user->hasRole('Vendor')){
            $purchases = Purchase::with('items.product')->where('vendor_id',$user->id)->latest()->get();
        }else{
            $purchases = collect();
        }
        return view('admin.purchase.index', compact('purchases'));
    }

    public function create()
    {
        $sellers = SellerMaster::where('admin_id', auth()->user()->admin?->id)->where('status',1)->get();
        return view('admin.purchase.create', compact('sellers'));
    }

    // AJAX search for products
    /*public function searchProducts(Request $request)
    {

        $vendorId = $request->user()->id;
        $search = $request->input('search');

        $availableProductIds = VendorProduct::where('vendor_id', $vendorId)
            ->where('availability', 1)
            ->pluck('product_id');
    
        // Query products visible and belong to vendor
        $productsQuery = Product::with(['variations.options', 'hsncode']) // make sure you eager load hsncode
            ->where('is_visible', 1)
            ->where('vendor_id', $vendorId)
            ->whereIn('id', $availableProductIds);
    
        if ($search) {
            $productsQuery->where(function($query) use ($search) {
                // Search by product name
                $query->where('name', 'like', '%' . $search . '%')
                    // Or search by barcode (exact match) in variations options
                    ->orWhereHas('variations.options', function($q) use ($search) {
                        $q->where('barcode', $search);
                    });
            });
        }
    
        $products = $productsQuery->get();
    
        // Flatten data for API (only matched options)
        $data = $products->flatMap(function ($product) use ($search) {
            return $product->variations->flatMap(function ($variation) use ($product, $search) {
                return $variation->options
                    ->when($search, function ($options) use ($search, $product) {
                        // if search looks like a barcode (all numbers) → filter by barcode
                        if (is_numeric($search)) {
                            return $options->where('barcode', $search);
                        }
        
                        // if searching by name, just return all options (no filter here)
                        return $options;
                    })
                    ->map(function ($option) use ($product, $variation) {
                        $price = $option->price ?? $variation->price ?? $product->price;
                        $gstRate = $product->hsncode->gst_rate ?? 0;
                        $isGstIncluded = $product->is_gst_included ?? 0;
    
                        // Split GST into CGST and SGST
                        $cgstRate = $gstRate / 2;
                        $sgstRate = $gstRate / 2;
    
                        if ($isGstIncluded) {
                            // If GST is included in price, extract base price first
                            $basePrice = $price / (1 + ($gstRate / 100));
                            $cgstAmount = $basePrice * ($cgstRate / 100);
                            $sgstAmount = $basePrice * ($sgstRate / 100);
                        } else {
                            // GST not included, simply calculate on price
                            $cgstAmount = $price * ($cgstRate / 100);
                            $sgstAmount = $price * ($sgstRate / 100);
                        }
    
                        return [
                            'product_id'           => $product->id,
                            'variation_id'         => $variation->id,
                            'variation_option_id'  => $option->id,
                            'product_name'         => $product->name . ' - ' . $option->name,
                            'product_price'        => $price,
                            'gst_rate'             => $gstRate,
                            'is_gst_included'      => $isGstIncluded,
                            'cgst_rate'            => $cgstRate,
                            'sgst_rate'            => $sgstRate,
                            'cgst_amount'          => round($cgstAmount, 2),
                            'sgst_amount'          => round($sgstAmount, 2),
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
    }*/
    public function searchProducts(Request $request)
    {
        $vendorId = $request->user()->id;
        $search = $request->input('search');

        // 1) Get allowed products for this vendor
        $availableProductIds = VendorProduct::where('vendor_id', $vendorId)
            ->where('availability', 1)
            ->pluck('product_id');

        // 2) Query products with variations + options
        $productsQuery = Product::with(['variations.options', 'hsncode'])
            ->where('is_visible', 1)
            ->whereIn('id', $availableProductIds);

        // SEARCH CONDITIONS
        if ($search) {
            $productsQuery->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('variations.options', function($q) use ($search) {
                        $q->where('barcode', $search);
                    });
            });
        }

        $products = $productsQuery->get();

        // 3) Flatten & add vendor wise stock
        $data = $products->flatMap(function ($product) use ($search, $vendorId) {
            return $product->variations->flatMap(function ($variation) use ($product, $search, $vendorId) {
                return $variation->options
                    ->when($search, function ($options) use ($search) {

                        // Barcode match → pick exact option
                        if (is_numeric($search)) {
                            return $options->where('barcode', $search);
                        }

                        return $options;
                    })
                    ->map(function ($option) use ($product, $variation, $vendorId) {

                        // ----------------------------------------------------
                        // ✅ GET VENDOR PRODUCT ID
                        // ----------------------------------------------------
                        $vendorProduct = VendorProduct::where('vendor_id', $vendorId)
                            ->where('product_id', $product->id)
                            ->first();

                        // Prevent errors if product not assigned
                        if (!$vendorProduct) {
                            $stock = 0;
                        } else {

                            // ----------------------------------------------------
                            // ✅ GET STOCK FROM vendor_product_stocks
                            // ----------------------------------------------------
                            $stockRow = VendorProductStock::where('vendor_product_id', $vendorProduct->id)
                                ->where('variation_id', $variation->id)
                                ->where('option_id', $option->id)
                                ->first();

                            $stock = $stockRow->stock ?? 0;
                        }

                        // GST Calculations
                        $price = $option->price ?? $variation->price ?? $product->price;
                        $gstRate = $product->hsncode->gst_rate ?? 0;
                        $isGstIncluded = $product->is_gst_included ?? 0;

                        $cgstRate = $gstRate / 2;
                        $sgstRate = $gstRate / 2;

                        if ($isGstIncluded) {
                            $basePrice = $price / (1 + ($gstRate / 100));
                            $cgstAmount = $basePrice * ($cgstRate / 100);
                            $sgstAmount = $basePrice * ($sgstRate / 100);
                        } else {
                            $cgstAmount = $price * ($cgstRate / 100);
                            $sgstAmount = $price * ($sgstRate / 100);
                        }

                        return [
                            'product_id'           => $product->id,
                            'variation_id'         => $variation->id,
                            'variation_option_id'  => $option->id,
                            'product_name'         => $product->name . ' - ' . $option->name,
                            'product_price'        => $price,
                            'gst_rate'             => $gstRate,
                            'is_gst_included'      => $isGstIncluded,
                            'cgst_rate'            => $cgstRate,
                            'sgst_rate'            => $sgstRate,
                            'cgst_amount'          => round($cgstAmount, 2),
                            'sgst_amount'          => round($sgstAmount, 2),
                            'stock'                => $stock,  // ✅ VENDOR-WISE STOCK
                            'barcode'              => $option->barcode,
                            'product_image'        => getProductMainImage($product->id),
                        ];
                    });
            });
        });

        return response()->json([
            'status' => true,
            'data' => $data->values(),
        ]);
    }


    /*public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:seller_masters,id',
            'invoice_no' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.option_id' => 'required|integer',
            'products.*.mrp' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.batch_no' => 'nullable|string',
            'products.*.expiry_date' => 'nullable|date',
        ]);

        $purchase = Purchase::create([
            'seller_name' => $request->seller_id,
            'invoice_number' => $request->invoice_no,
            'vendor_id' => auth()->user()->vendor->id ?? null,
            'purchase_date' => now(),
            'total_amount' => 0,
            'notes' => $request->notes,
        ]);

        $totalAmount = 0;

        foreach ($request->products as $prod) {
            $lineTotal = $prod['quantity'] * $prod['mrp'];
            $totalAmount += $lineTotal;

            $item = PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => ProductVariationOption::find($prod['option_id'])->variation->product_id,
                'veriation_option_id' => $prod['option_id'],
                'batch_number' => $prod['batch_no'],
                'expiry_date' => $prod['expiry_date'] ?? null,
                'quantity' => $prod['quantity'],
                'price' => $prod['mrp'],
                'discount' => $prod['discount'] ?? 0,
                'total' => $lineTotal,
            ]);

            // Update stock transaction
            $lastStock = StockTransaction::where('product_id', $item->product_id)->latest('id')->first();
            $openingBalance = $lastStock->closing_balance ?? 0;
            $closingBalance = $openingBalance + $prod['quantity'];

            StockTransaction::create([
                'product_id' => $item->product_id,
                'veriation_option_id' => $prod['option_id'],
                'batch_number' => $item->batch_number,
                'transaction_type' => 'purchase',
                'transaction_date' => now(),
                'quantity_in' => $prod['quantity'],
                'quantity_out' => 0,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
            ]);

            $option = ProductVariationOption::find($prod['option_id']);
            $option->quantity += $prod['quantity'];
            $option->save();
        }

        $purchase->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Purchase created successfully!',
            'purchase_id' => $purchase->id
        ], 201);
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:seller_masters,id',
            'invoice_no' => 'required|string|',
            'products' => 'required|array|min:1',
            'products.*.option_id' => 'required|integer|exists:product_variation_options,id',
            'products.*.mrp' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.batch_no' => 'nullable|string',
            'products.*.expiry_date' => 'nullable|date',
        ]);

        $vendorId = auth()->user()->id ?? null;

        $purchase = Purchase::create([
            'seller_name' => $request->seller_id,
            'invoice_number' => $request->invoice_no,
            'vendor_id' => $vendorId,
            'purchase_date' => now(),
            'total_amount' => 0,
            'notes' => $request->notes,
        ]);

        $totalAmount = 0;

        foreach ($request->products as $prod) {

            // Get option and product
            $option = ProductVariationOption::findOrFail($prod['option_id']);
            $variation = $option->variation;
            $productId = $variation->product_id;

            // Line total
            $lineTotal = $prod['quantity'] * $prod['mrp'];
            $totalAmount += $lineTotal;

            // Create purchase item
            $item = PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $productId,
                'veriation_option_id' => $prod['option_id'],
                'batch_number' => $prod['batch_no'],
                'expiry_date' => $prod['expiry_date'] ?? null,
                'quantity' => $prod['quantity'],
                'price' => $prod['mrp'],
                'discount' => $prod['discount'] ?? 0,
                'total' => $lineTotal,
            ]);

            // ----------------------------------------------
            // ✅ UPDATE VENDOR STOCK IN vendor_product_stocks
            // ----------------------------------------------

            // Get vendor_product_id for this vendor + product
            $vendorProduct = VendorProduct::where('vendor_id', $vendorId)
                ->where('product_id', $productId)
                ->first();

            if (!$vendorProduct) {
                return response()->json([
                    'status' => false,
                    'message' => "Product not assigned to vendor (Product ID: $productId)"
                ], 422);
            }

            // Get vendor stock record for this variation option
            $stock = VendorProductStock::firstOrNew([
                'vendor_product_id' => $vendorProduct->id,
                'variation_id' => $variation->id,
                'option_id' => $option->id,
            ]);

            // If new, opening stock = 0 or existing
            $openingBalance = $stock->stock ?? 0;

            // Update stock
            $stock->stock = $openingBalance + $prod['quantity'];
            $stock->save();

            // ----------------------------------------------
            // ✅ STOCK TRANSACTION ENTRY (per variation option)
            // ----------------------------------------------
            $lastStockTx = StockTransaction::where('product_id', $productId)
                ->where('veriation_option_id', $option->id)
                ->latest('id')
                ->first();

            $opening = $lastStockTx->closing_balance ?? 0;
            $closing = $opening + $prod['quantity'];

            StockTransaction::create([
                'product_id' => $productId,
                'veriation_option_id' => $option->id,
                'batch_number' => $item->batch_number,
                'transaction_type' => 'purchase',
                'transaction_date' => now(),
                'quantity_in' => $prod['quantity'],
                'quantity_out' => 0,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
            ]);

            // ❌ Removed old: $option->quantity += $prod['quantity'];
            // Now stock is maintained per vendor!
        }

        $purchase->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Purchase created successfully!',
            'purchase_id' => $purchase->id
        ], 201);
    }


    public function show($id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);
        return view('admin.purchase.show', compact('purchase'));
    }

}
