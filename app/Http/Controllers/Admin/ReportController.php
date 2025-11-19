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
        $purchases = Purchase::with(['vendor','items','items.product'])
            ->latest()
            ->get();

        return view('admin.reports.purchase_list', compact('purchases'));
    }

    public function purchase_products(Request $request)
    {
        $purchases = Purchase::with(['vendor','items','items.product'])
            ->latest()
            ->get();

        return view('admin.reports.purchase_list_products', compact('purchases'));
    }

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
        $vendorId = auth()->user()->vendor->id ?? auth()->user()->id;

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