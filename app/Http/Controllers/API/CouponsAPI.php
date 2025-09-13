<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Coupon;

class CouponsAPI extends Controller
{
    public function index(){
        $today = Carbon::today();

        $coupons = Coupon::where('is_active', 1)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->get();

        return response()->json([
            'status' => 'true',
            'data' => $coupons,
        ]);
    }
}