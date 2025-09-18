<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Transaction;
use App\Models\Order;

use Illuminate\Support\Facades\DB;

class TransactionApi extends Controller
{
    // public function get_date_wise_total_payment(Request $request){
    //     $vendorIds = $request->user()->vendors->pluck('id');
    //     if ($request->filled('date')) {
    //         try {
    //             $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
    //         } catch (\Exception $e) {
    //             // If invalid date, you can set it to null or today's date
    //             $date = null;
    //         }
    //     } else {
    //         $date = null;
    //     }
    //     $ordersQuery = Order::select(
    //         DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as order_date"),
    //         DB::raw('SUM(total_amount) as total_amount')
    //     )
    //     ->whereIn('vendor_id', $vendorIds);

    //     // Apply date filter only if valid date exists
    //     if ($date) {
    //         $ordersQuery->whereDate('created_at', $date);
    //     }

    //     $orders = $ordersQuery
    //         ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
    //         ->orderBy(DB::raw("STR_TO_DATE(order_date, '%d-%m-%Y')"), 'desc')
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'transactions' => $orders
    //     ]);
    // }

    public function get_date_wise_total_payment(Request $request){
        $vendorIds = $request->user()->vendors->pluck('id');

        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            } catch (\Exception $e) {
                $date = null;
            }
        } else {
            $date = null;
        }

        // List of all payment methods you want to always show
        $allPaymentMethods = ['Cash On Delivery', 'Online', 'UPI', 'Card', 'Cash'];

        // Get flat data
        $ordersQuery = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as order_date"),
            'payment_method',
            DB::raw('SUM(total_amount) as total_amount')
        )
        ->whereIn('vendor_id', $vendorIds);

        if ($date) {
            $ordersQuery->whereDate('created_at', $date);
        }

        $orders = $ordersQuery
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"), 'payment_method')
            ->orderBy(DB::raw("STR_TO_DATE(order_date, '%d-%m-%Y')"), 'desc')
            ->get();

        // Convert to nested structure with fixed keys
        $grouped = [];
        foreach ($orders as $order) {
            $dateKey = $order->order_date;

            // initialize all payment methods with 0 for this date if not already
            if (!isset($grouped[$dateKey])) {
                $grouped[$dateKey] = [
                    'order_date' => $dateKey,
                    'payment_methods' => array_fill_keys($allPaymentMethods, 0),
                    'total' => 0
                ];
            }

            // fill actual payment amount
            $grouped[$dateKey]['payment_methods'][$order->payment_method] = (float)$order->total_amount;

            // update total
            $grouped[$dateKey]['total'] += (float)$order->total_amount;
        }

        // reset keys to numeric for JSON
        $result = array_values($grouped);

        return response()->json([
            'success' => true,
            'transactions' => $result
        ]);
    }



    public function get_transaction_details(Request $request)
    {
        // Check if date provided
        if (!$request->filled('date')) {
            return response()->json([
                'success' => false,
                'error' => 'Date is required'
            ], 400);
        }

        try {
            // Convert date from d-m-Y to Y-m-d
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid date format. Use dd-mm-yyyy format.'
            ], 400);
        }

        $vendorIds = $request->user()->vendors->pluck('id');
        // Fetch orders created on that date
        $orders = Order::whereDate('created_at', $date)->whereIn('vendor_id', $vendorIds)->orderBy('id','desc')->get();

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

        return response()->json([
            'success' => true,
            'date' => $date,
            'transactions' => $data
        ]);
    }

    public function get_order_by_id(string $id, Request $request)
    {
        // Get all vendor IDs for the logged-in user
        $vendorIds = $request->user()->vendors->pluck('id');

        // Fetch order with items relation
        $order = Order::with('items') // eager-load items
            ->where('id', $id)
            ->whereIn('vendor_id', $vendorIds)
            ->firstOrFail();
        
        $order->items->each(function ($item) {
            $item->image_url = getProductMainImage($item->product_id);
        });

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

}