<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Transaction View', only: ['index','show']),
        ];
    }

    public function get_date_wise_total_payment(Request $request){
        $vendorId = auth()->user()->id;
        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            } catch (\Exception $e) {
                // If invalid date, you can set it to null or today's date
                $date = null;
            }
        } else {
            $date = null;
        }
        $ordersQuery = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as order_date"),
            DB::raw('SUM(total_amount) as total_amount')
        )
        ->where('vendor_id', $vendorId);

        // Apply date filter only if valid date exists
        if ($date) {
            $ordersQuery->whereDate('created_at', $date);
        }

        $orders = $ordersQuery
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->orderBy(DB::raw("STR_TO_DATE(order_date, '%d-%m-%Y')"), 'desc')
            ->get();

        return view('admin.transaction.index',compact('orders'));
    }

    public function get_transaction_details(Request $request)
    {
        // Check if date provided
        if (!$request->filled('date')) {
            return redirect()->back()->with('error','Date is required');
        }

        try {
            // Convert date from d-m-Y to Y-m-d
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->back()->with('error','Invalid date format. Use dd-mm-yyyy format.');
        }

        $vendorId = auth()->user()->id;
        // Fetch orders created on that date
        $orders = Order::whereDate('created_at', $date)->where('vendor_id', $vendorId)->orderBy('id','desc')->get();

        // Map to desired format
        $data = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'date_time' => $order->created_at->format('d-m-Y H:i'),
                'bill_no' => $order->order_number ?? $order->id,
                'amount' => $order->total_amount,
                'mode' => $order->payment_method,
                'transaction_dtls' => $order->transactions->first()->gateway_transaction_id ?? '-', 
            ];
        });

        return view('admin.transaction.details',compact('data'));
    }

    public function get_order_by_id(string $id, Request $request)
    {
        // Get all vendor IDs for the logged-in user
        // $vendorIds = $request->user()->vendors->pluck('id');
        $vendorId = auth()->user()->id;

        // Fetch order with items relation
        $order = Order::with('items') // eager-load items
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->firstOrFail();
        
        $order->items->each(function ($item) {
            $item->image_url = getProductMainImage($item->product_id);
        });

        return view('admin.transaction.details_order',compact('order'));
    }
}