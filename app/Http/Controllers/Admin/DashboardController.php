<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Hsncode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public function index(){
        $data['total_order'] = total_order_count();
        $data['total_return'] = 0;
        $data['total_category_count'] = total_category_count();
        $data['total_variation_option_count'] = total_variation_option_count();
        $data['low_stock_variation_option_count'] = low_stock_variation_option_count();
        $data['average_order_value'] = average_order_value();
        $data['total_orders'] = total_orders();
        $data['total_purchase_amount'] = total_purchase_amount();
        $data['total_stock_count'] = total_stock_count();
        $data['total_payment_amount_cash'] = total_payment_amount('Cash');
        $data['total_payment_amount_card'] = total_payment_amount('Card');
        $data['total_payment_amount_upi'] = total_payment_amount('UPI');
        $data['top_selling_products'] = top_selling_products(10);
        $data['low_stock_products'] = low_stock_products(10);
        $data['recent_sales'] = recent_sales(10);
        $data['top_categories'] = top_categories(10);
        $data['order_heatmap_data'] = order_heatmap_data();
        $data['category_heatmap_data'] = category_heatmap_data();

        return view('dashboard')->with($data);
    }
}