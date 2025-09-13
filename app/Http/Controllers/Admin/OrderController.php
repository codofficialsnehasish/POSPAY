<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\Category;
use Carbon\Carbon;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Order View', only: ['index','order_filter','show']),
            // new Middleware('permission:Order Create', only: ['create','store']),
            new Middleware('permission:Order Edit', only: ['update_order_status','update_payment_status']),
            new Middleware('permission:Order Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            $orders = Order::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->latest()->get();
            
        } elseif ($user->hasRole('Vendor')) {
            $orders = Order::where('vendor_id', $user->id) 
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()->get();
        } else {
            $orders = collect(); 
        }
        $total_amount = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $brands=  Brand::where('is_visible',1)->get();  
        $categories = Category::where('is_visible',1)->get();

        $vendors = User::role('Vendor')->get();
        return view('admin.orders.index',compact('orders','brands','categories','vendors','total_amount'));
    }

    public function order_filter(Request $request)
    {
        $user = Auth::user();

        $query = Order::query();
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('items.product.categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('brand_id')) {
            $query->whereHas('items.product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

         if ($request->has('filter_period') && $request->filter_period) {
            $filterPeriod = $request->filter_period;
            switch ($filterPeriod) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ]);
                    break;
                case 'monthly':
                    $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;
                case 'quarterly':
                    $query->whereBetween('created_at', [
                        now()->startOfQuarter(),
                        now()->endOfQuarter(),
                    ]);
                    break;
                case 'yearly':
                    $query->whereYear('created_at', now()->year);
                case 'custom_date':
                    if ($request->has('custom_from_date') && $request->has('custom_to_date')) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($request->custom_from_date)->startOfDay(),
                            Carbon::parse($request->custom_to_date)->endOfDay(),
                        ]);
                    }
                    break;
                case 'all':
                    default:
    
                        break;
            }
        }

        $total_amount = (clone $query)->sum('total_amount');
        $orders = $query->latest()->get();

        $brands = Brand::where('is_visible', 1)->get();  
        $categories = Category::where('is_visible', 1)->get();
        $vendors = User::role('Vendor')->get();

        return view('admin.orders.index', compact('orders', 'brands', 'categories', 'vendors', 'total_amount'));
    }


    public function show($id){
        $order = Order::find($id);
        $buyer_details = User::find($order->user_id);
        $order_items = $order->items;
        return view('admin.orders.show',compact('order','buyer_details','order_items'));
    }

    public function update_order_status(Request $request){
        $order = Order::find($request->order_id);
        if($request->order_status == 'Rejected'){
            $order->cancel_cause = $request->cancel_cause;
            // $order->is_cancel = 1;
            
            if($order->order_status == 'Order Placed' || $order->order_status == 'Order Confirmed'){
                $order->cancel_cause = $request->cancel_cause;
                // $order->is_cancel = 1;
            }else{
                return redirect()->back()->with(['error'=>'Order is now being '.ucfirst($order->order_status).', and it cannot be reject or cancel at this time.']);
            }
        }elseif($request->order_status == 'Delivered'){
            $order->status = 1;
        }
        // if($r->order_status == 'Ready for Pickup' && $order->order_type != 'takeaway'){
        //     if($r->delivary_partner){
        //         $delivery = new Delivery();
        //         $delivery->order_id = $order->id;
        //         // $delivery->order_type = 'delivery';
        //         $delivery->order_type = 'order';
        //         $delivery->partner_id = $r->delivary_partner;
        //         $delivery->status = "Delivery Assigned";
        //         $delivery->is_delivered = 0;
        //         $res = $delivery->save();
        //         if($res){
        //             $device_tokens = get_device_token_by_user_id($r->delivary_partner);
        //             if(!empty($device_tokens)){
        //                 $data = massage_data($delivery->status);
        //                 sendFcmNotification($device_tokens, $delivery->status, $delivery->status);
        //             }
        //         }
        //     }else{
        //         return redirect()->back()->with('error','Delivery Pertner is Required');
        //     }
        // }
        $order->order_status = $request->order_status;
        $result = $order->update();

        // if($result){
        //     $device_tokens = get_device_token_by_order_id($order->id);
        //     if(!empty($device_tokens)){
        //         $data = massage_data($r->order_status);
        //         sendFcmNotification($device_tokens, $r->order_status, $data);
        //     }
        // }
        return redirect()->back()->with(['success'=>'Status Updated Successfully']);
    }

    public function update_payment_status(Request $request){
        $order = Order::find($request->order_id);
        $order->payment_status = $request->order_status;
        // if($r->order_status == 'paid'){
        //     $order->payment_date = now();
        // }
        $order->update();
        return redirect()->back()->with(['success'=>'Payment Updated Successfully']);
    }

    public function destroy($id){
        $order = Order::find($id);
        if($order){
            $res = $order->delete();
            if($res){
                return back()->with('success','Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}