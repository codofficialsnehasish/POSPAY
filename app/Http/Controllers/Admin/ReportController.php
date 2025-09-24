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
        $orders = Order::with(['user','vendor'])
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
        $purchases = Purchase::with(['vendor','items.product'])
            ->latest()
            ->get();

        return view('admin.reports.purchase_list', compact('purchases'));
    }

    public function stock_report(Request $request)
    {
        $stocks = ProductVariationOption::with(['variation.product' => function($query) {
                    $query->where('vendor_id', auth()->user()->id);
                }])
                ->select('id', 'variation_id', 'name', 'quantity')
                ->whereHas('variation.product', function($query) {
                    $query->where('vendor_id', auth()->user()->id);
                })
                ->get();

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