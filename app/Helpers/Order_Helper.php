<?php

    use App\Models\Order;
    use App\Models\OrderItems;
    use App\Models\Cart;
    use App\Models\Coupon;
    use App\Models\Category;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    if (!function_exists('generateOrderNumber')) {
        function generateOrderNumber() {
            $dateTime = date('Ymd');
            // $orderNumber = 'ORD' . $dateTime;
            $orderNumber = 'O' . $dateTime;
            return $orderNumber;
        }
    }
    if (!function_exists('generateDraftOrderNumber')) {
        function generateDraftOrderNumber() {
            $dateTime = date('Ymd');
            $orderNumber = 'DRFT' . $dateTime;
            return $orderNumber;
        }
    }

    if (!function_exists('generateTransactionNumber')) {
        function generateTransactionNumber()
        {
            // Format: TXN + YYYYMMDD + random 6 digits
            $prefix = 'TXN';
            $date   = now()->format('Ymd');
            $random = mt_rand(100000, 999999);

            return $prefix . $date . $random;
        }
    }


    if (!function_exists('update_order_number')) {
        function update_order_number($order_id, $order_number)
        {
            $data = array(
                'order_number' => $order_number.$order_id
            );
            Order::where('id', $order_id)->update($data);
        }
    }


    if (!function_exists('get_coupone_discount')) {
        function get_coupone_discount($coupone_code,$amount)
        {
            // Fetch the coupon based on the code
            $coupone = Coupon::where('code', $coupone_code)
                            ->where('is_active', 1)
                            ->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now())
                            ->first();

            // If the coupon is not found or is inactive/expired
            if (!$coupone) {
                return 0.00; // No discount
            }

            // Check minimum purchase amount
            if ($amount < $coupone->minimum_purchase) {
                return 0.00; // No discount if purchase amount is less than required
            }

            // Calculate discount based on coupon type
            if ($coupone->type === 'percentage') {
                $discount = ($coupone->value / 100) * $amount; // Percentage discount
            } elseif ($coupone->type === 'flat') {
                $discount = $coupone->value; // Flat discount
            } else {
                $discount = 0.00; // Fallback for unknown types
            }

            // Ensure the discount does not exceed the total amount
            return min($discount, $amount);
        }
    }


    if (!function_exists('total_orders')) {
        function total_orders()
        {
            $query = Order::query();

            $user = Auth::user();
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            return $query->sum('total_amount');
        }
    }
    
    if (!function_exists('today_orders')) {
        function today_orders()
        {
            $today_amount = Order::whereDate('created_at', today())->sum('total_amount');
            return $today_amount;
        }
    }


    if (!function_exists('order_total_by_period')) {
        function order_total_by_period($period)
        {
            $query = Order::query();
            $user = Auth::user();
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;

                case 'weekly':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
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
                        now()->endOfQuarter()
                    ]);
                    break;

                case 'yearly':
                    $query->whereYear('created_at', now()->year);
                    break;

                case 'all':
                default:
                    // No date filter
                    break;
            }

            return $query->sum('total_amount');
        }
    }



    if (!function_exists('total_orders_by_user')) {
        function total_orders_by_user($user_id)
        {
            $query = Order::query();
            $query->where('user_id', $user_id);
            return $query->sum('total_amount');
        }
    }


    
    if (!function_exists('total_orders_period_by_user')) {
        function total_orders_period_by_user($user_id,$period)
        {
            $query = Order::query();
            $query->where('user_id', $user_id);
            

            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;

                case 'weekly':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
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
                        now()->endOfQuarter()
                    ]);
                    break;

                case 'yearly':
                    $query->whereYear('created_at', now()->year);
                    break;

                case 'all':
                default:
                    // No date filter
                    break;
            }

            return $query->sum('total_amount');
        }
    }


    if (!function_exists('monthly_sales_data')) {
        function monthly_sales_data()
        {
            $user = Auth::user();
            $sales = [];

            for ($month = 1; $month <= 12; $month++) {
                $query = \App\Models\Order::query()
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year);

                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }

                $sales[] = $query->sum('total_amount');
            }

            return $sales;
            
        }
    }
    if (!function_exists('daily_sales_data')) {
        function daily_sales_data($days = 7)
        {
            $user = Auth::user();
            $sales = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');

                $query = \App\Models\Order::query()
                    ->whereDate('created_at', $date);

                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }

                $sales[] = $query->sum('total_amount');
            }

            return $sales;
        }

    }

    // if (!function_exists('daily_order_stats')) {
    //     function daily_order_stats($days = 7)
    //     {
    //         $user = Auth::user();
    //         $stats = [];

    //         for ($i = $days - 1; $i >= 0; $i--) {
    //             $date = now()->subDays($i)->format('Y-m-d');

    //             $query = \App\Models\Order::query()
    //                 ->whereDate('created_at', $date);

    //             if ($user && $user->hasRole('Vendor')) {
    //                 $query->where('vendor_id', $user->id);
    //             }

    //             $stats[] = [
    //                 'date' => now()->subDays($i)->format('d M'),
    //                 'count' => $query->count()
    //             ];
    //         }

    //         return $stats;
    //     }
    // }
    
    if (!function_exists('monthly_sales_stats')) {
        function monthly_sales_stats()
        {
            $user = Auth::user();
            $sales = [];
    
            // List of months for labeling
            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
            ];
    
            foreach ($months as $monthNumber => $monthName) {
                $query = Order::query()
                    ->whereMonth('created_at', $monthNumber)
                    ->whereYear('created_at', now()->year);
    
                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }
    
                $sales[$monthName] = $query->sum('total_amount');
            }
    
            return $sales;
        }
    }
    
    
    
   if (!function_exists('daily_order_stats')) {
    function daily_order_stats($days = 7)
    {
        $user = Auth::user();
        $stats = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $date = $dateObj->format('Y-m-d');
            $displayDate = $dateObj->format('d M');

            $query = \App\Models\Order::query()
                ->whereDate('created_at', $date);

            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            $stats[] = [
                'date' => $displayDate,
                'count' => $query->count(),
                'total' => (float) $query->sum('total_amount'),
            ];
        }

        return $stats;
    }
}


    if (!function_exists('category_sales_stats')) {
        function category_sales_stats()
        {
            $stats = [];

            $categories = Category::with(['products'])->get();

            foreach ($categories as $category) {
                $productIds = $category->products->pluck('id')->toArray();

                $query = OrderItems::whereIn('product_id', $productIds);

                $totalSales = $query->sum('subtotal');
                $totalQty = $query->sum('quantity');
                $totalOrders = $query->distinct('order_id')->count('order_id');

                $stats[] = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'total_sales' => round($totalSales, 2),
                    'total_quantity' => $totalQty,
                    'total_orders' => $totalOrders,
                ];
            }

            return $stats;
        }
    }
    
    if (!function_exists('category_sales_summary')) {
    function category_sales_summary()
    {
        $allStats = category_sales_stats(); // re-use the detailed function

        $totalSales = 0;
        $totalQuantity = 0;
        $totalOrders = 0;
        $topCategories = collect($allStats)->sortByDesc('total_sales')->values();

        foreach ($allStats as $stat) {
            $totalSales += $stat['total_sales'];
            $totalQuantity += $stat['total_quantity'];
            $totalOrders += $stat['total_orders'];
        }

        return [
            'total_sales' => round($totalSales, 2),
            'total_quantity' => $totalQuantity,
            'total_orders' => $totalOrders,
            'top_category_by_sales' => $topCategories->first(),
            'top_5_categories' => $topCategories->take(5),
        ];
    }
}

if (!function_exists('today_sales_by_payment_method')) {
    function today_sales_by_payment_method()
    {
        $paymentMethods = ['Cash On Delevery', 'Online', 'UPI', 'Card'];
        $stats = [];

        $todayOrders = Order::whereDate('created_at', today())->get();

        $totalSalesToday = $todayOrders->sum('total_amount');

        foreach ($paymentMethods as $method) {
            $methodSales = $todayOrders
                ->where('payment_method', $method)
                ->sum('total_amount');

            $percentage = $totalSalesToday > 0 
                ? round(($methodSales / $totalSalesToday) * 100, 2)
                : 0;

            $stats[] = [
                'payment_method' => $method,
                'total_sales' => $methodSales,
                'percentage' => $percentage
            ];
        }

        return $stats;
    }
}



    
    





    if (!function_exists('vendor_wise_order_count')) {
        function vendor_wise_order_count()
        {
            return \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Vendor');
                })
                ->withCount(['orders'])
                ->get()
                ->map(function ($vendor) {
                    return [
                        'name' => $vendor->name,
                        'count' => $vendor->orders_count,
                    ];
                });
        }
    }


    if (! function_exists('top_selling_products')) {
    /**
     * Return top N selling products over all time (or you can scope by date).
     *
     * @param  int  $limit
     * @return \Illuminate\Support\Collection
     */
    function top_selling_products(int $limit = 5)
    {
        return OrderItems::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('COUNT(DISTINCT(order_id)) as total_orders'))
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
    }
}



