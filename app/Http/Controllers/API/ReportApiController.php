<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariationOption;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\StockTransaction;

class ReportApiController extends Controller
{
    // Sale list
    public function sale_list(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $orders = Order::with(['user','vendor'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    // Sale item list
    public function sale_item(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $orderItems = OrderItems::with(['order.user', 'product', 'option'])
            ->whereHas('order', function($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $orderItems
        ]);
    }

    // Purchase list
    public function purchase_list(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $purchases = Purchase::with(['vendor','items.product'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $purchases
        ]);
    }

    // Stock report
    public function stock_report(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $stocks = ProductVariationOption::with(['variation.product' => function($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                }])
                ->select('id', 'variation_id', 'name', 'quantity')
                ->whereHas('variation.product', function($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })
                ->get();

        return response()->json([
            'status' => true,
            'data' => $stocks
        ]);
    }

    // Payment list
    public function payment_list(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $payments = Transaction::with(['order','user','vendor'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $payments
        ]);
    }

    // Expiry list
    public function expiry_list(Request $request)
    {
        $vendorId = $request->user()->vendor->id;

        $expiryItems = StockTransaction::with(['product','variationOption'])
            ->where('vendor_id', $vendorId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->latest('expiry_date')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $expiryItems
        ]);
    }
}
