<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\OrderItems;
use App\Models\Transaction;
use App\Models\StockTransaction;
use App\Models\VendorProduct;
use App\Models\VendorProductStock;
use App\Models\Hsncode;
use App\Models\Unit;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Auth;

class ReportController  extends Controller implements HasMiddleware {

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Sale View', only: ['sale_list']),
            new Middleware('permission:Sale Item View', only: ['sale_item']),
            new Middleware('permission:Purchase List', only: ['purchase_list']),
            new Middleware('permission:Stock Report', only: ['stock_report']),
            new Middleware('permission:Payment List', only: ['payment_list']),
            new Middleware('permission:Expiry List', only: ['expiry_list']),
        ];
    }

    public function sale_list(Request $request)
    {
        $orders = Order::with(['user','vendor','transactions'])
            ->where('vendor_id', auth()->user()->id)
            ->latest()
            ->get();

        return view('admin.reports.sale_list', compact('orders'));
    }

    public function sale_item(Request $request)
    {
        $orderItems = OrderItems::with(['order.user', 'product', 'option'])
                    ->whereHas('order', function($query) {
                        $query->where('vendor_id', auth()->user()->id);
                    })
                    ->latest()
                    ->get();

        return view('admin.reports.sale_item', compact('orderItems'));
    }

    public function purchase_list(Request $request)
    {
        $vendorId = auth()->user()->id;
        $purchases = Purchase::with(['vendor','items','items.product'])
            ->where('vendor_id',$vendorId)
            ->latest()
            ->get();

        return view('admin.reports.purchase_list', compact('purchases'));
    }

    /*public function purchase_list(Request $request)
    {
        $vendorId = auth()->user()->id;

        // Products allowed for this vendor
        $vendorProducts = VendorProduct::where('vendor_id', $vendorId)
            ->where('availability', 1)
            ->pluck('product_id');

        // Load all purchases WITH relationships
        $purchases = Purchase::with([
                'seller',
                'items.product',
                'items.variation'
            ])
            ->latest()
            ->get();

        // Filter purchase->items so only vendor products remain
        foreach ($purchases as $purchase) {
            $purchase->items = $purchase->items->filter(function ($item) use ($vendorProducts) {
                return $vendorProducts->contains($item->product_id);
            });

            // Recalculate total amount based on filtered items
            $purchase->total_amount = $purchase->items->sum(function ($i) {
                return $i->quantity * $i->price;
            });
        }

        return view('admin.reports.purchase_list', compact('purchases'));
    }*/




    public function purchase_products(Request $request)
    {
        $vendorId = auth()->user()->id;
        $purchases = Purchase::with(['vendor','items','items.product'])
            ->where('vendor_id',$vendorId)
            ->latest()
            ->get();

        return view('admin.reports.purchase_list_products', compact('purchases'));
    }

    /*public function purchase_products(Request $request)
    {
        $vendorId = auth()->user()->id;

        $vendorProducts = VendorProduct::where('vendor_id', $vendorId)
            ->where('availability', 1)
            ->pluck('product_id');

        $purchases = Purchase::with([
                'vendor',
                'items',
                'items.product',
                'items.variation'
            ])
            ->latest()
            ->get();

        $rows = $purchases->flatMap(function ($purchase) use ($vendorProducts, $vendorId) {

            return $purchase->items
                ->filter(function ($item) use ($vendorProducts) {
                    return $vendorProducts->contains($item->product_id);
                })
                ->map(function ($item) use ($purchase, $vendorId) {

                    $vendorProductId = VendorProduct::where('vendor_id', $vendorId)
                        ->where('product_id', $item->product_id)
                        ->value('id');

                    $stock = VendorProductStock::where('vendor_product_id', $vendorProductId)
                        ->where('variation_id', $item->variation->variation_id ?? null)
                        ->where('option_id', $item->veriation_option_id)
                        ->first();

                    return [
                        'date'          => $purchase->purchase_date,
                        'seller'        => $purchase->seller->seller_name ?? 'N/A',
                        'invoice'       => $purchase->invoice_number,
                        'product'       => $item->product->name ?? '',
                        'variation'     => $item->variation->name ?? '',
                        'price'         => $item->price,
                        'qty'           => $item->quantity,
                        'discount'      => $item->discount ?? 0,
                        'batch'         => $item->batch_number,
                        'expiry'        => $item->expiry_date,
                        'cgst'          => $item->cgst_amount ?? 0,
                        'sgst'          => $item->sgst_amount ?? 0,
                        'line_total'    => ($item->quantity * $item->price),
                        'final_total'   => ($item->quantity * $item->price) - ($item->discount ?? 0) + ($item->cgst_amount ?? 0) + ($item->sgst_amount ?? 0),
                        'stock'         => $stock->stock ?? 0,
                    ];
                });
        });

        return view('admin.reports.purchase_list_products', [
            'rows' => $rows
        ]);
    }*/





    /*public function stock_report(Request $request)
    {
        $stocks = ProductVariationOption::with(['variation.product' => function($query) {
                    $query->where('vendor_id', auth()->user()->id);
                }])
                ->select('id', 'variation_id', 'name', 'quantity','price')
                ->whereHas('variation.product', function($query) {
                    $query->where('vendor_id', auth()->user()->id);
                })
                ->get();

        return view('admin.reports.stock_report', compact('stocks'));
    }*/
    public function stock_report(Request $request)
    {
        // vendor id: prefer vendor relation if available, fallback to user id
        $vendorId = auth()->user()->id;

        // 1) Build a map of product_id => vendor_product_model for this vendor
        $vendorProductsMap = VendorProduct::where('vendor_id', $vendorId)
            ->get()
            ->keyBy('product_id'); // collection keyed by product_id

        // 2) Get the product IDs that this vendor has
        $vendorProductIds = $vendorProductsMap->keys()->all(); // array of product_ids

        // 3) Fetch all variation options for products belonging to this vendor
        $options = ProductVariationOption::with(['variation.product'])
            ->whereHas('variation.product', function ($q) use ($vendorProductIds) {
                $q->whereIn('id', $vendorProductIds);
            })
            ->get();

        // 4) Map options to a simple structure with vendor-wise stock
        $stocks = $options->map(function ($option) use ($vendorProductsMap) {
            $product = $option->variation->product;
            $productId = $product->id;

            // find vendor_product record (if exists)
            $vendorProduct = $vendorProductsMap->get($productId);

            if ($vendorProduct) {
                $stockRow = VendorProductStock::where('vendor_product_id', $vendorProduct->id)
                    ->where('variation_id', $option->variation_id)
                    ->where('option_id', $option->id)
                    ->first();

                $stockQty = $stockRow->stock ?? 0;
            } else {
                $stockQty = 0;
            }

            return (object)[
                'option_id'     => $option->id,
                'variation_id'  => $option->variation_id,
                'product_name'  => $product->name,
                'option_name'   => $option->name,
                'price'         => $option->price,
                'stock'         => $stockQty,
                'barcode'       => $option->barcode,
            ];
        });

        return view('admin.reports.stock_report', compact('stocks'));
    }



    public function payment_list(Request $request)
    {
        $payments = Transaction::with(['order','user','vendor'])
            ->latest()
            ->get();

        return view('admin.reports.payment_list', compact('payments'));
    }

    public function expiry_list(Request $request)
    {
        $expiryItems = StockTransaction::with(['product','variationOption'])
            ->whereHas('product', function ($query){
                    $query->where('vendor_id', auth()->user()->id);
                })
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30)) // next 30 days expiry
            ->latest('expiry_date')
            ->get();

        return view('admin.reports.expiry_list', compact('expiryItems'));
    }

}