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
        $sellers = SellerMaster::where('vendor_id', auth()->user()->id)->get();;
        return view('admin.purchase.create', compact('sellers'));
    }

    // AJAX search for products
    public function searchProducts(Request $request)
    {
        // return $request;
        // $search = $request->input('search');

        // $products = Product::with('variations.options')
        //     ->where('is_visible', 1)
        //     ->where(function($q) use ($search) {
        //         $q->where('name', 'like', "%{$search}%")
        //           ->orWhereHas('variations.options', function($q2) use ($search) {
        //               $q2->where('barcode', $search);
        //           });
        //     })
        //     ->get();

        // $data = $products->flatMap(function ($product) {
        //     return $product->variations->flatMap(function ($variation) use ($product) {
        //         return $variation->options->map(function ($option) use ($product, $variation) {
        //             return [
        //                 'id' => $option->id,
        //                 'product_name' => $product->name . ' - ' . $option->name,
        //                 'price' => $option->price ?? $variation->price ?? $product->price,
        //                 'stock' => $option->quantity,
        //                 'barcode' => $option->barcode,
        //                 'product_image' => getProductMainImage($product->id),
        //             ];
        //         });
        //     });
        // });

        // return response()->json($data->values());

        $vendorId = $request->user()->id;
        $search = $request->input('search');
    
        // Query products visible and belong to vendor
        $productsQuery = Product::with(['variations.options', 'hsncode']) // make sure you eager load hsncode
            ->where('is_visible', 1)
            ->where('vendor_id', $vendorId);
    
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
    }

    public function store(Request $request)
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
    }

    public function show($id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);
        return view('admin.purchase.show', compact('purchase'));
    }

}
