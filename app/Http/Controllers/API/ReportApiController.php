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
use App\Exports\StockReportExport;
use App\Exports\SellReportExport;
use App\Exports\SellItemReportExport;
use App\Exports\PurchasesReportExport;
use App\Exports\PaymentReportExport;
use App\Exports\ExpiryItemsReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportApiController extends Controller
{
    // Sale list
    public function sale_list(Request $request)
    {
        $vendorId = $request->vendor_id;

        // $orders = Order::with(['user','vendor'])
        //     ->where('vendor_id', $vendorId)
        //     ->latest()
        //     ->get();
        $orders = Order::with(['user','vendor'])
                ->where('vendor_id', $vendorId)
                ->when($request->filter, function ($query) use ($request) {
                    $today = now()->toDateString();

                    switch ($request->filter) {
                        case 'today':
                            $query->whereDate('created_at', $today);
                            break;
                        case 'yesterday':
                            $query->whereDate('created_at', now()->subDay()->toDateString());
                            break;
                        case '15days':
                            $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                            break;
                        case 'monthly':
                            $query->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
                            break;
                        case 'quarterly':
                            $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                            break;
                    }
                })
                ->when($request->start_date && $request->end_date, function($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->end_date);
                })
                ->latest()
                ->get();


        $exportData = $orders->map(function ($order) {
                        return [
                            'Date' => format_datetime_excel($order->created_at),
                            'User' => $order->user->name,
                            'Order #' => $order->order_number,
                            'Total Amount' => number_format($order->total_amount,2),
                            'Payment Status' => $order->payment_status,
                            'Payment Mode' => $order->payment_method,
                            'Order Status' => $order->order_status,
                        ];
                    });

        return Excel::download(new SellReportExport($exportData), 'sell_report.xlsx');
    }

    // Sale item list
    public function sale_item(Request $request)
    {
        $vendorId = $request->vendor_id;

        // $orderItems = OrderItems::with(['order.user', 'product', 'option'])
        //     ->whereHas('order', function($query) use ($vendorId) {
        //         $query->where('vendor_id', $vendorId);
        //     })
        //     ->latest()
        //     ->get();

        $orderItems = OrderItems::with(['order.user', 'product', 'option'])
                    ->whereHas('order', function($query) use ($vendorId, $request) {
                        $query->where('vendor_id', $vendorId)
                            ->when($request->filter, function ($query) use ($request) {
                                $today = now()->toDateString();

                                switch ($request->filter) {
                                    case 'today':
                                        $query->whereDate('created_at', $today);
                                        break;
                                    case 'yesterday':
                                        $query->whereDate('created_at', now()->subDay()->toDateString());
                                        break;
                                    case '15days':
                                        $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                                        break;
                                    case 'monthly':
                                        $query->whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year);
                                        break;
                                    case 'quarterly':
                                        $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                                        break;
                                }
                            })
                            ->when($request->start_date && $request->end_date, function($query) use ($request) {
                                $query->whereDate('created_at', '>=', $request->start_date)
                                    ->whereDate('created_at', '<=', $request->end_date);
                            });
                    })
                    ->latest()
                    ->get();


        $exportData = $orderItems->map(function ($item) {
                        return [
                            'Order #' => $item->order->order_number,
                            'Product' => $item->product->name ?? $item->product_name,
                            'Variation' => $item->option->name,
                            'Quantity' => $item->quantity,
                            'Price' => number_format($item->price,2),
                            'Subtotal' => number_format($item->subtotal,2),
                        ];
                    });

        return Excel::download(new SellItemReportExport($exportData), 'sell_item_report.xlsx');
    }

    // Purchase list
    public function purchase_list(Request $request)
    {
        $vendorId = $request->vendor_id;

        // $purchases = Purchase::with(['vendor','items.product'])
        //     ->where('vendor_id', $vendorId)
        //     ->latest()
        //     ->get();

        $purchases = Purchase::with(['vendor','items.product'])
                    ->where('vendor_id', $vendorId)
                    ->when($request->filter, function ($query) use ($request) {
                        $today = now()->toDateString();

                        switch ($request->filter) {
                            case 'today':
                                $query->whereDate('created_at', $today);
                                break;
                            case 'yesterday':
                                $query->whereDate('created_at', now()->subDay()->toDateString());
                                break;
                            case '15days':
                                $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                                break;
                            case 'monthly':
                                $query->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year);
                                break;
                            case 'quarterly':
                                $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                                break;
                        }
                    })
                    ->when($request->start_date && $request->end_date, function($query) use ($request) {
                        $query->whereDate('created_at', '>=', $request->start_date)
                            ->whereDate('created_at', '<=', $request->end_date);
                    })
                    ->latest()
                    ->get();


        $exportData = $purchases->map(function ($purchase) {
                        // Build product + variation string
                        $products = $purchase->items->map(function ($item) {
                            $variation = $item->variation ? ' - '.$item->variation->name : '';
                            return $item->product->name . $variation;
                        })->implode(", "); // join with newline or comma

                        return [
                            'Date' => format_date_excel($purchase->purchase_date),
                            'Seller' => $purchase->seller->seller_name,
                            'Product' => $products, // here is your combined product + variation
                            'Invoice #' => $purchase->invoice_number,
                            'Total Amount' => number_format($purchase->total_amount, 2),
                        ];
                    });


        return Excel::download(new PurchasesReportExport($exportData), 'purchase_report.xlsx');
    }

    // Stock report
    public function stock_report(Request $request)
    {
        $vendorId = $request->vendor_id;

        // $stocks = ProductVariationOption::with(['variation.product' => function($query) use ($vendorId) {
        //             $query->where('vendor_id', $vendorId);
        //         }])
        //         ->select('id', 'variation_id', 'name', 'quantity')
        //         ->whereHas('variation.product', function($query) use ($vendorId) {
        //             $query->where('vendor_id', $vendorId);
        //         })
        //         ->get();
                
        $stocks = ProductVariationOption::with(['variation.product' => function($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                }])
                ->select('id', 'variation_id', 'name', 'quantity')
                ->whereHas('variation.product', function($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })
                ->when($request->filter, function ($query) use ($request) {
                    $today = now()->toDateString();

                    switch ($request->filter) {
                        case 'today':
                            $query->whereDate('created_at', $today);
                            break;
                        case 'yesterday':
                            $query->whereDate('created_at', now()->subDay()->toDateString());
                            break;
                        case '15days':
                            $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                            break;
                        case 'monthly':
                            $query->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
                            break;
                        case 'quarterly':
                            $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                            break;
                    }
                })
                ->when($request->start_date && $request->end_date, function($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                    $query->whereDate('created_at', '<=', $request->end_date);
                })
                ->get();


        $exportData = $stocks->map(function ($stock) {
                        return [
                            'Product' => $stock->variation->product->name,
                            'Variation' => $stock->name,
                            'Current Quantity' => $stock->quantity,
                        ];
                    });

        return Excel::download(new StockReportExport($exportData), 'current_stock_report.xlsx');
    }

    // Payment list
    public function payment_list(Request $request)
    {
       $vendorId = $request->vendor_id;

        // $payments = Transaction::with(['order','user','vendor'])
        //     ->where('vendor_id', $vendorId)
        //     ->latest()
        //     ->get();

        $payments = Transaction::with(['order','user','vendor'])
                ->where('vendor_id', $vendorId)
                ->when($request->filter, function ($query) use ($request) {
                    $today = now()->toDateString();

                    switch ($request->filter) {
                        case 'today':
                            $query->whereDate('created_at', $today);
                            break;
                        case 'yesterday':
                            $query->whereDate('created_at', now()->subDay()->toDateString());
                            break;
                        case '15days':
                            $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                            break;
                        case 'monthly':
                            $query->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
                            break;
                        case 'quarterly':
                            $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                            break;
                    }
                })
                ->when($request->start_date && $request->end_date, function($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->end_date);
                })
                ->latest()
                ->get();


        $exportData = $payments->map(function ($payment) {
                        return [
                            'Transaction #' => $payment->transaction_number,
                            'User' => $payment->user->name,
                            'Order #' => $payment->order->order_number,
                            'Amount' => number_format($payment->amount,2),
                            'Payment Method' => $payment->payment_method,
                            'Status' => $payment->payment_status,
                            'Paid At' => $payment->paid_at ? format_datetime($payment->paid_at) : 'Pending',
                        ];
                    });

        return Excel::download(new PaymentReportExport($exportData), 'payment_report.xlsx');
    }

    // Expiry list
    public function expiry_list(Request $request)
    {
        $vendorId = $request->vendor_id;

        // $expiryItems = StockTransaction::with(['product','variationOption'])
        //     ->where('vendor_id', $vendorId)
        //     ->whereNotNull('expiry_date')
        //     ->where('expiry_date', '<=', now()->addDays(30))
        //     ->latest('expiry_date')
        //     ->get();

        $expiryItems = StockTransaction::with(['product','variationOption'])
                    ->whereHas('product', function ($query) use ($vendorId) {
                        $query->where('vendor_id', $vendorId);
                    })
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays(30))
                    ->when($request->filter, function ($query) use ($request) {
                        $today = now()->toDateString();

                        switch ($request->filter) {
                            case 'today':
                                $query->whereDate('created_at', $today);
                                break;
                            case 'yesterday':
                                $query->whereDate('created_at', now()->subDay()->toDateString());
                                break;
                            case '15days':
                                $query->where('created_at', '>=', now()->subDays(15)->toDateString());
                                break;
                            case 'monthly':
                                $query->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year);
                                break;
                            case 'quarterly':
                                $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                                break;
                        }
                    })
                    ->when($request->start_date && $request->end_date, function($query) use ($request) {
                        $query->whereDate('created_at', '>=', $request->start_date)
                            ->whereDate('created_at', '<=', $request->end_date);
                    })
                    ->latest('expiry_date')
                    ->get();


        $exportData = $expiryItems->map(function ($item) {
                        return [
                            'Product' => $item->product->name ,
                            'Variation' => $item->variationOption->name,
                            'Batch Number' => $item->batch_number,
                            'Quantity' => $item->closing_balance ?? $item->quantity_in ?? 0,
                            'Expiry Date' => $item->expiry_date ? format_datetime($item->expiry_date) : 'N/A',
                        ];
                    });

        return Excel::download(new ExpiryItemsReportExport($exportData), 'expiry_items_report.xlsx');
    }

    public function get_report_list(Request $request){

        // $vendorId = $request->user()->vendor->id;

        $reports_list = [
            ["id" => 1,"name"=>'Current Stock Report'],
            ["id" => 2,"name"=>'Sales Report'],
            ["id" => 3,"name"=>'Sales Item Report'],
            ["id" => 4,"name"=>'Purchase Report'],
            ["id" => 5,"name"=>'Payment / Transaction Report'],
            ["id" => 6,"name"=>'Expiry Product Report'],
        ];

        // $id = $request->id;
        // // $excelLink = null;

        // // If ID is provided, trigger Excel export
        // if ($id) {
        //     switch ($id) {
        //         case 1: // Stock Report
        //             $excelLink = route('report.stocks', [
        //                 'vendor_id' => $vendorId,
        //                 'filter' => $request->filter,
        //                 'start_date' => $request->start_date,
        //                 'end_date' => $request->end_date
        //             ]);
        //             break;
        //         case 2:
        //             break;
        //     }
        // }


        return response()->json([
            'status' => true,
            'data' => [
                'reports_list'=>$reports_list,
                // 'excel_link'=>$excelLink
            ]
        ]);
    }

    public function get_report_excel_link(Request $request){

        $vendorId = $request->user()->vendor->id;

        $id = $request->id;
        $excelLink = null;

        // If ID is provided, trigger Excel export
        if ($id) {
            switch ($id) {
                case 1: // Stock Report
                    $excelLink = route('report.stocks', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
                case 2:
                    $excelLink = route('report.sales', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
                case 3:
                    $excelLink = route('report.sales-items', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
                case 4:
                    $excelLink = route('report.purchases', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
                case 5:
                    $excelLink = route('report.payments', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
                case 6:
                    $excelLink = route('report.expiry', [
                        'vendor_id' => $vendorId,
                        'filter' => $request->filter,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                    break;
            }
        }


        return response()->json([
            'status' => true,
            'data' => [
                'excel_link'=>$excelLink
            ]
        ]);
    }


}
