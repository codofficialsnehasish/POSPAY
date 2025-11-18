<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public function index(Request $request){

        $user_id = $request->user()->id;
        $vendorId = $request->vendorId;
        
        $total_orders= total_orders_by_user($user_id, $vendorId);
        $today_orders= total_orders_period_by_user($user_id,$vendorId,'today');
        $weekly_orders= total_orders_period_by_user($user_id,$vendorId,'weekly');
        $monthly_orders= total_orders_period_by_user($user_id,$vendorId,'monthly');
        $last_month_orders= total_orders_period_by_user($user_id,$vendorId,'last_month');
        $quarterly_orders= total_orders_period_by_user($user_id,$vendorId,'quarterly');
        $yearly_orders= total_orders_period_by_user($user_id,$vendorId,'yearly');
        // $monthly_sales_orders =monthly_sales_data();
        // $daily_sales_orders =daily_sales_data();

        return response()->json([
            'response' => true,
            'message' => 'get dashboard stats',
            'data' => [
                'total_orders' => $total_orders,
                'today_orders' => $today_orders,
                'weekly_orders' => $weekly_orders,
                'monthly_orders' => $monthly_orders,
                'last_month_orders' => $last_month_orders,
                'quarterly_orders' => $quarterly_orders,
                'yearly_orders' => $yearly_orders,
                'total_yearly_sales_stats'=>yearly_sales_stats(5,$user_id,$vendorId),
                'total_monthly_sales_stats'=>monthly_sales_stats($user_id,$vendorId),
                'total_daily_sales_stats'=>daily_order_stats(7,$user_id,$vendorId),
                'category_wise_sales_stats'=>category_sales_stats(),
                'all_category_wise'=>category_sales_summary(),
                'today_sales_by_payment_method'=>today_sales_by_payment_method(),
            ],
        ]);
    }
    
    
    public function products(Request $request){

        $user_id = $request->user()->id;
        $limit = 5;
        
       $top_selling_products= OrderItems::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('COUNT(DISTINCT(order_id)) as total_orders'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')              // eager load the product
            ->take($limit)
            ->get()
            ->map(fn($item) => [
                'id'           => $item->product_id,
                'name'         => $item->product->name,
                'category'     => $item->product->category?->name ?? '—',
                'price'        => $item->product->price,
                'discount'     => $item->product->discount_percent,   // or however you store it
                'sold'         => $item->total_sold,
                'total_orders' => $item->total_orders,
                 'image_url'    => $item->product->mainImage
                                    ? Storage::url($item->product->mainImage->path)
                                    : asset('images/default-product.png'),
            ]);
        


        return response()->json([
            'response' => true,
            'message' => 'get dashboard products',
            'data' => [
                'top_selling_products' => $top_selling_products,
        
            ],
        ]);
    }
}
