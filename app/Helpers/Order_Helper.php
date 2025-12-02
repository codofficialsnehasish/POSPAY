<?php

    use App\Models\Order;
    use App\Models\OrderItems;
    use App\Models\Cart;
    use App\Models\Coupon;
    use App\Models\Category;
    use App\Models\VendorProduct;
    use App\Models\Purchase;
    use App\Models\ProductVariationOption;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;

    if (!function_exists('generateOrderNumber')) {
        function generateOrderNumber() {
            
            
            $date = date('Ymd');
            $countToday = Order::withoutGlobalScope('withoutDraft')->whereDate('created_at', date('Y-m-d'))->count();
            $nextNumber = $countToday + 1;
            $nextNumberPadded = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            return "O" . $date . $nextNumberPadded;
            
            
            // $dateTime = date('Ymd');
            // $orderNumber = 'ORD' . $dateTime;
            // $orderNumber = 'O' . $dateTime;
            // return $orderNumber;
        }
    }
    if (!function_exists('generateDraftOrderNumber')) {
        function generateDraftOrderNumber() {
            $date = date('Ymd');
            $countToday = Order::withoutGlobalScope('withoutDraft')->whereDate('created_at', date('Y-m-d'))->count();
            $nextNumber = $countToday + 1;
            $nextNumberPadded = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            return "DRFT" . $date . $nextNumberPadded;
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

            return intval($query->sum('total_amount'));
        }
    }

    if (!function_exists('total_payment_amount')) {
        function total_payment_amount($method)
        {
            $query = Order::query()
                ->where('payment_method', $method); // card, cash, upi

            $user = Auth::user();
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            return intval($query->sum('total_amount'));
        }
    }

    if (!function_exists('average_order_value')) {
        function average_order_value()
        {
            $query = Order::query();

            $user = Auth::user();
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            $totalAmount = $query->sum('total_amount');
            $totalOrders = $query->count();

            if ($totalOrders == 0) {
                return 0; // Avoid division by zero
            }

            return intval($totalAmount / $totalOrders);
        }
    }


    if (!function_exists('total_order_count')) {
        function total_order_count()
        {
            $query = Order::query();

            $user = Auth::user();
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            return $query->count();
        }
    }

    if (!function_exists('total_category_count')) {
        function total_category_count($id = null)
        {
            $query = Category::query();

            $user = Auth::user();

            // If vendor, apply vendor filters
            if ($user && $user->hasRole('Vendor')) {

                // Get available product IDs for this vendor
                $availableProductIds = VendorProduct::where('vendor_id', $user->id)
                    ->where('availability', 1)
                    ->pluck('product_id');

                // Base vendor categories
                $query->where('vendor_id', $user->id)
                    ->where('is_visible', 1);

                // Parent / child category filter
                if ($id) {
                    $query->where('parent_id', $id);
                } else {
                    $query->whereNull('parent_id');
                }

                // Include only categories that have available products of the vendor
                $query->whereHas('products', function ($q) use ($availableProductIds) {
                    $q->whereIn('products.id', $availableProductIds);
                });
            }

            return $query->count();
        }
    }

    if (!function_exists('total_variation_option_count')) {
        function total_variation_option_count($productId = null)
        {
            $query = ProductVariationOption::query();

            $user = Auth::user();

            if ($user && $user->hasRole('Vendor')) {

                // Get available product IDs for this vendor
                $availableProductIds = VendorProduct::where('vendor_id', $user->id)
                    ->where('availability', 1)
                    ->pluck('product_id');

                // Restrict to vendor products
                $query->whereHas('variation.product', function ($q) use ($availableProductIds) {
                    $q->whereIn('products.id', $availableProductIds);
                });

                // Optional filter by product
                if ($productId) {
                    $query->whereHas('variation', function ($q) use ($productId) {
                        $q->where('product_id', $productId);
                    });
                }
            }

            return $query->count();
        }
    }

    if (!function_exists('low_stock_variation_option_count')) {
        function low_stock_variation_option_count($threshold = 5, $productId = null)
        {
            $query = ProductVariationOption::query()
                ->where('quantity', '<=', $threshold);

            $user = Auth::user();

            if ($user && $user->hasRole('Vendor')) {

                // Get available product IDs for this vendor
                $availableProductIds = VendorProduct::where('vendor_id', $user->id)
                    ->where('availability', 1)
                    ->pluck('product_id');

                // Restrict to vendor's available products
                $query->whereHas('variation.product', function ($q) use ($availableProductIds) {
                    $q->whereIn('products.id', $availableProductIds);
                });

                // Optional: filter by specific product
                if ($productId) {
                    $query->whereHas('variation', function ($q) use ($productId) {
                        $q->where('product_id', $productId);
                    });
                }
            }

            return $query->count();
        }
    }

    if (!function_exists('total_stock_count')) {
        function total_stock_count($productId = null)
        {
            $query = ProductVariationOption::query();

            $user = Auth::user();

            if ($user && $user->hasRole('Vendor')) {

                // Get available product IDs for this vendor
                $availableProductIds = VendorProduct::where('vendor_id', $user->id)
                    ->where('availability', 1)
                    ->pluck('product_id');

                // Restrict to vendor's available products
                $query->whereHas('variation.product', function ($q) use ($availableProductIds) {
                    $q->whereIn('products.id', $availableProductIds);
                });

                // Optional: filter by specific product
                if ($productId) {
                    $query->whereHas('variation', function ($q) use ($productId) {
                        $q->where('product_id', $productId);
                    });
                }
            }

            // Sum total quantity
            return intval($query->sum('quantity'));
        }
    }

    if (!function_exists('total_purchase_amount')) {
        function total_purchase_amount()
        {
            $query = Purchase::query();

            $user = Auth::user();

            // Vendor filter
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            return intval($query->sum('total_amount')); // no decimals
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
        function total_orders_by_user($user_id, $vendor_id, $from, $to)
        {
            $query = Order::query();
            $query->where('user_id', $user_id);
            $query->where('vendor_id', $vendor_id);
            $query->whereDate('created_at', '>=', $from);
            $query->whereDate('created_at', '<=', $to);

            return $query->sum('total_amount');

        }
    }


    
    /*if (!function_exists('total_orders_period_by_user')) {
        function total_orders_period_by_user($user_id,$vendor_id,$period)
        {
            $query = Order::query();
            $query->where('user_id', $user_id);
            $query->where('vendor_id', $vendor_id);
            

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
    }*/

    if (!function_exists('total_orders_period_by_user')) {
        function total_orders_period_by_user($user_id, $vendor_id, $from, $to)
        {
            return Order::where('user_id', $user_id)
                ->where('vendor_id', $vendor_id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('total_amount');
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
        function monthly_sales_stats($user_id = null, $vendor_id = null, $from=null, $to=null)
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
                    ->whereYear('created_at', \Carbon\Carbon::now()->year);

                if ($user_id) { $query->where('user_id', $user_id); }
                if ($vendor_id) { $query->where('vendor_id', $vendor_id); }

                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }

                $sales[$monthName] = $query->sum('total_amount');
            }

            return $sales;
        }
    }

    if (!function_exists('weekly_sales_stats')) {
        function weekly_sales_stats($user_id = null, $vendor_id = null)
        {
            $user = Auth::user();

            // Last 7 days including today
            $startDate = \Carbon\Carbon::today()->subDays(6);
            $endDate   = \Carbon\Carbon::today();

            // Prepare days array (Mon → Sun)
            $days = [
                'Monday' => 0,
                'Tuesday' => 0,
                'Wednesday' => 0,
                'Thursday' => 0,
                'Friday' => 0,
                'Saturday' => 0,
                'Sunday' => 0,
            ];

            $query = Order::query()->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);


            if ($user_id) { $query->where('user_id', $user_id); }
            if ($vendor_id) { $query->where('vendor_id', $vendor_id); }

            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            $orders = $query->get();

            // foreach ($orders as $order) {
            //     $dayName = \Carbon\Carbon::parse($order->created_at)->format('l'); // Monday, Tuesday...
            //     $days[$dayName] += round($order->total_amount,2);
            // }

            foreach ($orders as $order) {
                $dayName = \Carbon\Carbon::parse($order->created_at)->format('l');

                // Convert to paise (integer)
                $amount = (int) round($order->total_amount * 100);

                // Add to that day's total
                $days[$dayName] += $amount;
            }

            // Convert back to numeric rupees
            foreach ($days as $day => $value) {
                $days[$day] = $value / 100;   // <-- gives numeric without float errors
            }

            return $days;
        }
    }



    if (!function_exists('yearly_sales_stats')) {
        function yearly_sales_stats($user_id = null, $vendor_id = null,$from=null, $to=null)
        {
            $user = Auth::user();

            // Validate date format Y-m-d using Carbon's createFromFormat
            if (!Carbon::createFromFormat('Y-m-d', $from) ||
                !Carbon::createFromFormat('Y-m-d', $to)) {

                return []; // return empty list instead of 1970
            }

            // Now safe to parse
            $start = Carbon::parse($from)->startOfDay();
            $end   = Carbon::parse($to)->endOfDay();

            // If FROM > TO, return empty
            if ($start->gt($end)) {
                return [];
            }

            $sales = [];

            $startYear = $start->year;
            $endYear   = $end->year;

            for ($year = $startYear; $year <= $endYear; $year++) {

                $query = Order::query()
                    ->whereYear('created_at', $year);
                    // ->whereBetween('created_at', [$start, $end]);

                if ($user_id) {
                    $query->where('user_id', $user_id);
                }

                if ($vendor_id) {
                    $query->where('vendor_id', $vendor_id);
                }

                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }

                $sales[] = [
                    'year'  => $year,
                    'total' => $query->sum('total_amount'),
                ];
            }

            return $sales;
        }
    }





    
    
    
    if (!function_exists('daily_order_stats')) {
        function daily_order_stats($days = 7, $user_id = null, $vendor_id = null)
        {
            $user = Auth::user();
            $stats = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $dateObj = now()->subDays($i);
                $date = $dateObj->format('Y-m-d');
                $displayDate = $dateObj->format('d M');

                $query = \App\Models\Order::query()
                    ->whereDate('created_at', $date);

                if($user_id){ $query->where('user_id', $user_id); }
                if($vendor_id){ $query->where('vendor_id', $vendor_id); }                    

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


    /*if (!function_exists('category_sales_stats')) {
        function category_sales_stats($user_id = null, $vendor_id = null, $from, $to)
        {
            $stats = [];

            $availableProductIds = VendorProduct::where('vendor_id', $vendor_id)
                    ->where('availability', 1)
                    ->pluck('product_id');
                    
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
    }*/

    /*if (!function_exists('category_sales_stats')) {
        function category_sales_stats($from, $to, $user_id = null, $vendor_id = null)
        {
            // Validate date format (prevents 1970 bug)
            if (
                !Carbon::createFromFormat('Y-m-d', $from) ||
                !Carbon::createFromFormat('Y-m-d', $to)
            ) {
                return [];
            }

            // Parse date range safely
            $start = Carbon::parse($from)->startOfDay();
            $end   = Carbon::parse($to)->endOfDay();

            $stats = [];

            // Get vendor products (only available)
            $vendorProductIds = VendorProduct::where('vendor_id', $vendor_id)
                ->where('availability', 1)
                ->pluck('product_id')
                ->toArray();

            // Load categories + products
            $categories = Category::with('products')->get();

            foreach ($categories as $category) {

                // Get product IDs under this category which also belong to vendor
                $productIds = $category->products
                    ->whereIn('id', $vendorProductIds)
                    ->pluck('id');

                if ($productIds->isEmpty()) {
                    continue; // Skip if vendor has no products in this category
                }

                $query = OrderItems::whereIn('product_id', $productIds)
                    ->whereBetween('created_at', [$start, $end]);

                // Optional user filter
                if ($user_id) {
                    $query->whereHas('order', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                }

                // Optional vendor filter
                if ($vendor_id) {
                    $query->whereHas('order', function ($q) use ($vendor_id) {
                        $q->where('vendor_id', $vendor_id);
                    });
                }

                $stats[] = [
                    'category_id'   => $category->id,
                    'category_name' => $category->name,
                    'total_sales'   => round($query->sum('subtotal'), 2),
                    'total_quantity'=> $query->sum('quantity'),
                    'total_orders'  => $query->distinct('order_id')->count('order_id'),
                ];
            }

            return $stats;
        }
    }*/

    
    if (!function_exists('category_sales_stats')) {
        function category_sales_stats($user_id = null, $vendor_id = null,$from=null, $to=null)
        {
            // return 0;
            // SAFE DATE PARSING (never throws error)
            try {
                $start = Carbon::parse($from)->startOfDay();
                $end   = Carbon::parse($to)->endOfDay();
            } catch (\Exception $e) {
                return []; // invalid date → return empty result safely
            }

            $stats = [];

            // Vendor products
            $vendorProductIds = VendorProduct::where('vendor_id', $vendor_id)
                ->where('availability', 1)
                ->pluck('product_id')
                ->toArray();

            // Categories + products
            $categories = Category::with('products')->get();

            foreach ($categories as $category) {

                $productIds = $category->products
                    ->whereIn('id', $vendorProductIds)
                    ->pluck('id');

                if ($productIds->isEmpty()) {
                    continue;
                }

                $query = OrderItems::whereIn('product_id', $productIds)
                    ->whereBetween('created_at', [$start, $end]);

                // User filter
                if ($user_id) {
                    $query->whereHas('order', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                }

                // Vendor filter
                if ($vendor_id) {
                    $query->whereHas('order', function ($q) use ($vendor_id) {
                        $q->where('vendor_id', $vendor_id);
                    });
                }

                $stats[] = [
                    'category_id'    => $category->id,
                    'category_name'  => $category->name,
                    'total_sales'    => round($query->sum('subtotal'), 2),
                    'total_quantity' => $query->sum('quantity'),
                    'total_orders'   => $query->distinct('order_id')->count('order_id'),
                ];
            }

            return $stats;
        }
    }


    /*if (!function_exists('today_sales_by_payment_method')) {
        function today_sales_by_payment_method($user_id = null, $vendor_id = null, $from, $to)
        {
            // $paymentMethods = ['Cash On Delevery', 'Online', 'UPI', 'Card'];
            $paymentMethods = ['Cash', 'UPI', 'Card'];
            $stats = [];

            $todayOrders = Order::whereDate('created_at', date('Y-m-d'))->get();

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

            // Add total at the end
            $stats[] = [
                'payment_method' => 'All',
                'total_sales' => $totalSalesToday,
                'percentage' => 100
            ];

            return $stats;
        }
    }*/

    if (!function_exists('today_sales_by_payment_method')) {
        function today_sales_by_payment_method($user_id = null, $vendor_id = null, $from=null, $to=null)
        {
            $paymentMethods = ['Cash', 'UPI', 'Card'];
            $stats = [];

            // Build query using from + to
            $ordersQuery = Order::query();

            if ($user_id) {
                $ordersQuery->where('user_id', $user_id);
            }

            if ($vendor_id) {
                $ordersQuery->where('vendor_id', $vendor_id);
            }

            $ordersQuery->whereDate('created_at', '>=', $from);
            $ordersQuery->whereDate('created_at', '<=', $to);

            $orders = $ordersQuery->get();

            $totalSales = $orders->sum('total_amount');

            foreach ($paymentMethods as $method) {
                $methodSales = $orders->where('payment_method', $method)->sum('total_amount');

                $percentage = $totalSales > 0
                    ? round(($methodSales / $totalSales) * 100, 2)
                    : 0;

                $stats[] = [
                    'payment_method' => $method,
                    'total_sales' => round($methodSales,2), //number_format
                    'percentage' => $percentage
                ];
            }

            // Add total row
            $stats[] = [
                'payment_method' => 'All',
                'total_sales' => round($totalSales,2), //number_format
                'percentage' => 100
            ];

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
    /*function top_selling_products(int $limit = 5)
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
    }*/

    /*function top_selling_products(int $limit = 5, $user_id = null, $vendor_id = null, $from=null, $to=null)
    {
        $vendorID = $vendor_id ? $vendor_id : auth()->id();
        return OrderItems::with([
                'product.categories',
                'variation',
                'option'
            ])
            ->select(
                'product_id',
                'variation_id',
                'option_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('COUNT(DISTINCT(order_id)) as total_orders')
            )
            ->whereHas('order', function ($query) {
                $query->where('vendor_id', auth()->id());
            })
            ->groupBy('product_id', 'variation_id', 'option_id')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $product   = $item->product;
                $option    = $item->option;
                $variation = $item->variation;

                // Build full product name like “Bira 91 Blonde Summer Lager Beer - 10ml”
                $fullName = $product?->name ?? 'Unknown Product';
                if ($option?->name) {
                    $fullName .= ' - ' . $option->name;
                }

                return [
                    'id'            => $item->product_id,
                    'name'          => $fullName,
                    'category'      => $product?->categories?->first()?->name ?? '',
                    'price'         => $option?->price ?? $product?->price ?? 0,
                    'sold'          => $item->total_sold,
                    'total_orders'  => $item->total_orders,
                    'image_url'     => getProductMainImage($product?->id)
                                        ? getProductMainImage($product->id)
                                        : asset('images/default-product.png'),
                ];
            });
    }*/

    function top_selling_products(int $limit = 5, $user_id = null, $vendor_id = null, $from = null, $to = null)
    {
        $vendorID = $vendor_id ?: auth()->id();

        return OrderItems::with(['product.categories', 'variation', 'option'])
            ->select(
                'product_id',
                'variation_id',
                'option_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('COUNT(DISTINCT(order_id)) as total_orders')
            )
            ->whereHas('order', function ($query) use ($vendorID, $user_id, $from, $to) {

                // Filter by vendor
                $query->where('vendor_id', $vendorID);

                // Filter by user_id (customer)
                if (!empty($user_id)) {
                    $query->where('user_id', $user_id);
                }

                // Date range filters
                if (!empty($from)) {
                    $query->whereDate('created_at', '>=', $from);
                }
                if (!empty($to)) {
                    $query->whereDate('created_at', '<=', $to);
                }
            })
            ->groupBy('product_id', 'variation_id', 'option_id')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get()
            ->map(function ($item) {

                $product   = $item->product;
                $option    = $item->option;
                $variation = $item->variation;

                // Build product name “Product - Option”
                $fullName = $product?->name ?? 'Unknown Product';
                if ($option?->name) {
                    $fullName .= ' - ' . $option->name;
                }

                return [
                    'id'            => $item->product_id,
                    'name'          => $fullName,
                    'category'      => $product?->categories?->first()?->name ?? '',
                    'price'         => $option?->price ?? $product?->price ?? 0,
                    'sold'          => $item->total_sold,
                    'total_orders'  => $item->total_orders,
                    'image_url'     => getProductMainImage($product?->id)
                                        ? getProductMainImage($product->id)
                                        : asset('images/default-product.png'),
                ];
            });
    }

    
}

if (!function_exists('low_stock_products')) {
    function low_stock_products(int $limit = 5)
    {
        return Product::with(['categories', 'variations.options'])
            ->where('vendor_id', auth()->id())
            ->get()
            ->flatMap(function ($product) {
                return $product->variations->flatMap(function ($variation) use ($product) {
                    return $variation->options->map(function ($option) use ($product, $variation) {
                        // Full product name like “Bira 91 Blonde Summer Lager Beer - 10ml”
                        $fullName = $product->name;
                        if (!empty($option->name)) {
                            $fullName .= ' - ' . $option->name;
                        }

                        return [
                            'id'          => $product->id,
                            'name'        => $fullName,
                            'category'    => $product->categories?->first()?->name ?? '',
                            'price'       => $option->price ?? $product->price ?? 0,
                            'stock'       => $option->quantity ?? 0,
                            'image_url'   => getProductMainImage($product->id)
                                                ? getProductMainImage($product->id)
                                                : asset('images/default-product.png'),
                        ];
                    });
                });
            })
            ->filter(fn($p) => $p['stock'] <= 10) // show variants with low stock
            ->sortBy('stock')
            ->take($limit)
            ->values();
    }
}



if (!function_exists('recent_sales')) {
    function recent_sales(int $limit = 5)
    {
        return \App\Models\OrderItems::with([
                'product.categories',
                'order.user',
                'variation',
                'option'
            ])
            ->whereHas('order', function ($query) {
                $query->where('vendor_id', auth()->id());
            })
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $product   = $item->product;
                $option    = $item->option;
                $variation = $item->variation;

                // Build full name like “Bira 91 Blonde Summer Lager Beer - 10ml”
                $fullName = $product?->name ?? 'Unknown Product';
                if ($option?->name) {
                    $fullName .= ' - ' . $option->name;
                }

                return [
                    'id'            => $item->id,
                    'product_name'  => $fullName,
                    'category'      => $product?->categories?->first()?->name ?? '',
                    'price'         => $option?->price ?? $product?->price ?? 0,
                    'quantity'      => $item->quantity ?? 0,
                    'amount'        => ($item->quantity ?? 0) * ($option?->price ?? $product?->price ?? 0),
                    'date'          => $item->created_at,
                    'product_image' => getProductMainImage($item->product_id)
                                        ? getProductMainImage($item->product_id)
                                        : asset('images/default-product.png'),
                    'customer_name' => $item->order?->user?->name ?? 'Guest',
                ];
            });
    }
}


/**
 * TOP CUSTOMERS
 */
if (!function_exists('top_customers')) {
    function top_customers(int $limit = 5)
    {
        return Order::with('user')
            ->select('user_id', 
                DB::raw('SUM(total_amount) as total_spent'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->where('vendor_id', auth()->id())
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take($limit)
            ->get()
            ->map(fn($order) => [
                'name'  => $order->user?->name ?? 'Guest',
                'email' => $order->user?->email ?? 'N/A',
                'total_spent'  => $order->total_spent ?? 0,
                'total_orders' => $order->total_orders ?? 0,
            ]);
    }
}


/**
 * TOP CATEGORIES (for Doughnut chart)
 */
if (!function_exists('top_categories')) {
    /*function top_categories(int $limit = 5, $user_id = null, $vendor_id = null, $from = null, $to = null)
    {
        $vendorID = $vendor_id ?: auth()->id();
        return OrderItems::with('product.categories')
            ->whereHas('order', fn($q) => $q->where('vendor_id', $vendorID))
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->get()
            ->flatMap(function ($item) {
                return $item->product->categories->map(function ($cat) use ($item) {
                    return [
                        'name'  => $cat->name,
                        'count' => $item->total_sold,
                    ];
                });
            })
            ->groupBy('name')
            ->map(fn($g) => [
                'name'  => $g->first()['name'],
                'count' => $g->sum('count'),
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)), // random color
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values();
    }*/

    function top_categories(int $limit = 5, $user_id = null, $vendor_id = null, $from = null, $to = null, $period = null)
    {
         $today = now();

        // --- PERIOD BASED DATE FILTERS ---
        if ($period) {
            switch ($period) {
    
                case 'today': // 1D
                    $from = $today->copy()->startOfDay();
                    $to   = $today->copy()->endOfDay();
                    break;
    
                case 'week': // 1W
                    $from = $today->copy()->startOfWeek();
                    $to   = $today->copy()->endOfWeek();
                    break;
    
                case 'month': // 1M
                    $from = $today->copy()->startOfMonth();
                    $to   = $today->copy()->endOfMonth();
                    break;
    
                case '3month': // 3M
                    $from = $today->copy()->subMonths(3)->startOfDay();
                    $to   = $today->copy()->endOfDay();
                    break;
    
                case '6month': // 6M
                    $from = $today->copy()->subMonths(6)->startOfDay();
                    $to   = $today->copy()->endOfDay();
                    break;
    
                case 'year': // 1Y
                    $from = $today->copy()->startOfYear();
                    $to   = $today->copy()->endOfYear();
                    break;
    
                default:
                    $from = null;
                    $to = null;
            }
        }
    
        $vendorID = $vendor_id ?: auth()->id();
    
        return OrderItems::with('product.categories')
            ->whereHas('order', function ($q) use ($vendorID, $user_id, $from, $to) {
    
                $q->where('vendor_id', $vendorID);
    
                if (!empty($user_id)) {
                    $q->where('user_id', $user_id);
                }
    
                if (!empty($from)) {
                    $q->whereDate('created_at', '>=', $from);
                }
                if (!empty($to)) {
                    $q->whereDate('created_at', '<=', $to);
                }
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->get()
    
            ->flatMap(function ($item) {
                return $item->product->categories->map(function ($cat) use ($item) {
                    return [
                        'name'  => $cat->name,
                        'count' => $item->total_sold,
                    ];
                });
            })
    
            ->groupBy('name')
            ->map(fn($g) => [
                'name'  => $g->first()['name'],
                'count' => $g->sum('count'),
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ])
    
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

}


/**
 * HOURLY ORDER STATISTICS
 */
if (!function_exists('order_heatmap_data')) {
    function order_heatmap_data($params = ['period' => 'today'])
    {
        $period = $params['period'] ?? 'today';

        // -------- PERIOD HANDLER --------  
        if ($period === 'custom') {
            try {
                $startDate = Carbon::parse($params['from_date'])->startOfDay();
                $endDate   = Carbon::parse($params['to_date'])->endOfDay();
            } catch (\Throwable $e) {
                // fallback if invalid format
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            }
        } else {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate   = now()->endOfDay();
                    break;

                case 'week':
                    $startDate = now()->startOfWeek()->startOfDay();
                    $endDate   = now()->endOfWeek()->endOfDay();
                    break;

                case 'month':
                    $startDate = now()->startOfMonth()->startOfDay();
                    $endDate   = now()->endOfMonth()->endOfDay();
                    break;

                case '3month':
                    $startDate = now()->subMonths(2)->startOfMonth();
                    $endDate   = now()->endOfMonth();
                    break;

                case '6month':
                    $startDate = now()->subMonths(5)->startOfMonth();
                    $endDate   = now()->endOfMonth();
                    break;

                case 'year':
                    $startDate = now()->startOfYear()->startOfDay();
                    $endDate   = now()->endOfYear()->endOfDay();
                    break;

                default:
                    $startDate = now()->startOfDay();
                    $endDate   = now()->endOfDay();
            }
        }

        // -------- QUERY --------  
        $raw = \App\Models\Order::where('vendor_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(created_at) as weekday'),
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('weekday', 'hour')
            ->get();

        // reorder days → 0=Mon ... 6=Sun
        $dayMap = collect([2,3,4,5,6,7,1]);  // Sunday last

        return collect(range(0, 6))->flatMap(function ($index) use ($raw, $dayMap) {
            return collect(range(0, 23))->map(function ($hour) use ($raw, $dayMap, $index) {
                $found = $raw->first(fn($r) =>
                    $r->weekday == $dayMap[$index] &&
                    $r->hour == $hour
                );

                return [
                    'x' => $index,
                    'y' => $hour,
                    'v' => $found?->total ?? 0,
                ];
            });
        })->values();
    }
}


if (!function_exists('category_heatmap_data')) {
    function category_heatmap_data($params = ['period' => 'today'])
    {
        $vendorId = auth()->id();
        $period = $params['period'] ?? 'today';

        // -------- PERIOD HANDLER --------
        if ($period === 'custom') {
            try {
                $startDate = Carbon::parse($params['from_date'])->startOfDay();
                $endDate   = Carbon::parse($params['to_date'])->endOfDay();
            } catch (\Throwable $e) {
                $startDate = now()->startOfDay();
                $endDate   = now()->endOfDay();
            }
        } else {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate   = now()->endOfDay();
                    break;

                case 'week':
                    $startDate = now()->startOfWeek()->startOfDay();
                    $endDate   = now()->endOfWeek()->endOfDay();
                    break;

                case 'month':
                    $startDate = now()->startOfMonth()->startOfDay();
                    $endDate   = now()->endOfMonth()->endOfDay();
                    break;

                case '3month':
                    $startDate = now()->subMonths(2)->startOfMonth();
                    $endDate   = now()->endOfMonth();
                    break;

                case '6month':
                    $startDate = now()->subMonths(5)->startOfMonth();
                    $endDate   = now()->endOfMonth();
                    break;

                case 'year':
                    $startDate = now()->startOfYear();
                    $endDate   = now()->endOfYear();
                    break;

                default:
                    $startDate = now()->startOfDay();
                    $endDate   = now()->endOfDay();
            }
        }

        // -------- QUERY --------
        $data = \App\Models\OrderItems::select(
                DB::raw('DAYOFWEEK(order_items.created_at) as weekday'),
                'categories.name as category_name',
                DB::raw('COUNT(order_items.id) as total')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'product_categories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.vendor_id', $vendorId)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('weekday', 'categories.name')
            ->get();

        $categories = $data->pluck('category_name')->unique()->values();
        $days = collect([1,2,3,4,5,6,7]); // 1=Sun ... 7=Sat

        return $categories->flatMap(function ($cat, $catIndex) use ($days, $data) {

            return $days->map(function ($day) use ($cat, $catIndex, $data) {

                $found = $data->first(fn($r) =>
                    $r->weekday == $day &&
                    $r->category_name == $cat
                );

                return [
                    'x' => $day - 1,   // convert day to 0–6
                    'y' => $catIndex,
                    'v' => $found?->total ?? 0,
                    'category' => $cat,
                ];
            });

        })->values();
    }
}

//////////////////////////////////////////////////////////



if (!function_exists('get_monthly_sales')) {
    function get_monthly_sales($year = null)
    {
        $year = $year ?: date('Y');

        $query = Order::query()->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total');

        $user = Auth::user();
        if ($user && $user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }

        $data = $query
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }

        return $result;
    }
}

if (!function_exists('get_monthly_purchase')) {
    function get_monthly_purchase($year = null)
    {
        $year = $year ?: date('Y');

        $query = Purchase::query()->selectRaw('MONTH(purchase_date) as month, SUM(total_amount) as total');

        $user = Auth::user();
        if ($user && $user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }

        $data = $query
            ->whereYear('purchase_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }

        return $result;
    }
}


if (!function_exists('get_sales_range')) {
    function get_sales_range($start, $end)
    {
        $query = Order::query();

        $user = Auth::user();
        if ($user && $user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }

        return intval($query->whereBetween('created_at', [$start, $end])->sum('total_amount'));
    }
}


if (!function_exists('get_purchase_range')) {
    function get_purchase_range($start, $end)
    {
        $query = Purchase::query();

        $user = Auth::user();
        if ($user && $user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }

        return intval($query->whereBetween('purchase_date', [$start, $end])->sum('total_amount'));
    }
}
if (!function_exists('get_sales_filtered')) {

    function get_sales_filtered($params = [])
    {
        $user = Auth::user();

        $query = Order::query();

        // Vendor filter
        if ($user && $user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->id);
        }

        // Custom Date Range
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $query->whereBetween('created_at', [
                $params['from_date'] . " 00:00:00",
                $params['to_date']   . " 23:59:59"
            ]);
        }
        // Predefined Period Filter
        elseif (!empty($params['period'])) {

            switch ($params['period']) {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'week':
                    $query->whereBetween('created_at', [
                        now()->subDays(6)->startOfDay(),
                        now()->endOfDay()
                    ]);
                    break;

                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;

                case '3month':
                    $query->whereBetween('created_at', [
                        now()->subMonths(3)->startOfDay(),
                        now()->endOfDay()
                    ]);
                    break;

                case '6month':
                    $query->whereBetween('created_at', [
                        now()->subMonths(6)->startOfDay(),
                        now()->endOfDay()
                    ]);
                    break;

                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Group by Date
        $sales = $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                       ->groupBy('date')
                       ->orderBy('date')
                       ->get();

        return [
            'labels' => $sales->pluck('date'),
            'values' => $sales->pluck('total'),
            'total'  => $sales->sum('total'),
        ];
    }
}
if (!function_exists('get_sales_by_year')) {
    function get_sales_by_year($year)
    {
        $user = Auth::user();

        $monthly = [];

        // Loop 12 months
        for ($m = 1; $m <= 12; $m++) {

            $query = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $m);

            // Vendor filter
            if ($user && $user->hasRole('Vendor')) {
                $query->where('vendor_id', $user->id);
            }

            $monthly[] = (int) $query->sum('total_amount');
        }

        return $monthly; // returns array of 12 values
    }
}



if (!function_exists('get_sales_purchase_range')) {
    function get_sales_purchase_range($range)
    {
        $user = Auth::user();
        $today = now();

        // FOR CUSTOM RANGE
        if (is_array($range) && ($range['period'] ?? null) === 'custom') {
            
            

            $start = Carbon::parse($range['from_date'])->startOfDay();
            $end   = Carbon::parse($range['to_date'])->endOfDay();

            // Total days difference
            $totalDays = $start->diffInDays($end) + 1;

            // Create date labels (dd/mm or dd MON)
            $labels = [];
            for ($i = 0; $i < $totalDays; $i++) {
                $labels[] = $start->copy()->addDays($i)->format('d M');
            }

            // RESULT ARRAYS
            $sales = [];
            $purchase = [];

            foreach ($labels as $i => $label) {

                $date = $start->copy()->addDays($i);

                $querySales = Order::query();
                $queryPurchase = Purchase::query();

                if ($user && $user->hasRole('Vendor')) {
                    $querySales->where('vendor_id', $user->id);
                    $queryPurchase->where('vendor_id', $user->id);
                }

                // Filter by selected date
                $querySales->whereDate('created_at', $date);
                $queryPurchase->whereDate('purchase_date', $date);

                $sales[] = (int) $querySales->sum('total_amount');
                $purchase[] = (int) $queryPurchase->sum('total_amount');
            }

            return [
                'labels' => $labels,
                'sales' => $sales,
                'purchase' => $purchase,
                'total_sales' => array_sum($sales),
                'total_purchase' => array_sum($purchase),
            ];
        }

        // ----------------------------------------------
        // NORMAL PRESETS (today, week, month, 3M, 6M, 1Y)
        // ----------------------------------------------

        switch ($range) {

            case 'today':
                $start = $today->copy()->startOfDay();
                $end   = $today->copy()->endOfDay();
                $labels = ['Today'];
                break;

            case 'week':
                $start = $today->copy()->startOfWeek();
                $end   = $today->copy()->endOfWeek();
                $labels = [];
                for ($i = 0; $i < 7; $i++) {
                    $labels[] = $start->copy()->addDays($i)->format('D');
                }
                break;

            case 'month':
                $start = $today->copy()->startOfMonth();
                $end   = $today->copy()->endOfMonth();
                $days  = $today->daysInMonth;
                $labels = range(1, $days);
                break;

            case '3month':
                $start = $today->copy()->subMonths(2)->startOfMonth();
                $end   = $today->copy()->endOfMonth();
                $labels = [];
                for ($i = 0; $i < 3; $i++) {
                    $labels[] = $start->copy()->addMonths($i)->format('M');
                }
                break;

            case '6month':
                $start = $today->copy()->subMonths(5)->startOfMonth();
                $end   = $today->copy()->endOfMonth();
                $labels = [];
                for ($i = 0; $i < 6; $i++) {
                    $labels[] = $start->copy()->addMonths($i)->format('M');
                }
                break;

            case 'year':
            default:
                $start = $today->copy()->startOfYear();
                $end   = $today->copy()->endOfYear();
                $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                break;
        }

        // RESULT ARRAYS
        $sales = [];
        $purchase = [];

        foreach ($labels as $index => $label) {

            $querySales = Order::query();
            $queryPurchase = Purchase::query();

            if ($user && $user->hasRole('Vendor')) {
                $querySales->where('vendor_id', $user->id);
                $queryPurchase->where('vendor_id', $user->id);
            }

            // Apply filters based on range type
            if ($range === 'today') {

                $querySales->whereBetween('created_at', [$start, $end]);
                $queryPurchase->whereBetween('purchase_date', [$start, $end]);

            } elseif ($range === 'week') {

                $dayDate = $start->copy()->addDays($index);
                $querySales->whereDate('created_at', $dayDate);
                $queryPurchase->whereDate('purchase_date', $dayDate);

            } elseif ($range === 'month') {

                $day = $index + 1;

                $querySales->whereDay('created_at', $day)
                    ->whereMonth('created_at', $today->month)
                    ->whereYear('created_at', $today->year);

                $queryPurchase->whereDay('purchase_date', $day)
                    ->whereMonth('purchase_date', $today->month)
                    ->whereYear('purchase_date', $today->year);

            } else {

                $monthDate = $start->copy()->addMonths($index);

                $querySales->whereMonth('created_at', $monthDate->month)
                    ->whereYear('created_at', $monthDate->year);

                $queryPurchase->whereMonth('purchase_date', $monthDate->month)
                    ->whereYear('purchase_date', $monthDate->year);
            }

            $sales[] = (int) $querySales->sum('total_amount');
            $purchase[] = (int) $queryPurchase->sum('total_amount');
        }

        return [
            'labels' => $labels,
            'sales' => $sales,
            'purchase' => $purchase,
            'total_sales' => array_sum($sales),
            'total_purchase' => array_sum($purchase),
        ];
    }
}