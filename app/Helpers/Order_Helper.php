<?php

    use App\Models\Order;
    use App\Models\OrderItems;
    use App\Models\Cart;
    use App\Models\Coupon;
    use App\Models\Category;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;

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

    if (!function_exists('yearly_sales_stats')) {
        function yearly_sales_stats($yearsBack = 5) // optional: how many past years to include
        {
            $user = Auth::user();
            $sales = [];

            // Generate a list of years to display, e.g., last 5 years
            $currentYear = now()->year;
            $years = range($currentYear - $yearsBack + 1, $currentYear);

            foreach ($years as $year) {
                $query = Order::query()
                    ->whereYear('created_at', $year);

                if ($user && $user->hasRole('Vendor')) {
                    $query->where('vendor_id', $user->id);
                }

                $sales[] = [
                    'year' => $year,
                    'total' => $query->sum('total_amount'),
                ];
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

    function top_selling_products(int $limit = 5)
    {
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
    function top_categories(int $limit = 5)
    {
        return OrderItems::with('product.categories')
            ->whereHas('order', fn($q) => $q->where('vendor_id', auth()->id()))
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
    }
}


/**
 * HOURLY ORDER STATISTICS
 */
if (!function_exists('order_heatmap_data')) {
    function order_heatmap_data()
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate   = now()->endOfDay();

        $raw = \App\Models\Order::where('vendor_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(created_at) as weekday'), // 1 = Sunday
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('weekday', 'hour')
            ->get();

        // Normalize days → 0=Mon ... 6=Sun for chart
        $dayMap = collect([2,3,4,5,6,7,1]); // reorder Sunday last

        $days = collect(range(0, 6)); // 0..6
        $hours = collect(range(0, 23)); // 0..23

        return $days->flatMap(function ($index) use ($hours, $dayMap, $raw) {
            $day = $dayMap[$index]; // map to MySQL DAYOFWEEK
            return $hours->map(function ($hour) use ($day, $index, $raw) {
                $found = $raw->firstWhere(fn($r) => $r->weekday == $day && $r->hour == $hour);
                return [
                    'x' => $index, // ✅ Day (0–6)
                    'y' => $hour,  // ✅ Hour (0–23)
                    'v' => $found?->total ?? 0,
                ];
            });
        })->values();
    }
}


if (!function_exists('category_heatmap_data')) {
    function category_heatmap_data()
    {
        $vendorId = auth()->id();

        // Fetch total orders grouped by category & day of week
        $data = \App\Models\OrderItems::select(
                DB::raw('DAYOFWEEK(order_items.created_at) as weekday'),
                'categories.name as category_name',
                DB::raw('COUNT(order_items.id) as total')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id') // ✅ correct pivot
            ->join('categories', 'product_categories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.vendor_id', $vendorId)
            ->groupBy('weekday', 'categories.name')
            ->get();

        // Collect all unique category names
        $categories = $data->pluck('category_name')->unique()->values();
        $days = collect(range(1, 7)); // Sunday (1) → Saturday (7)

        // Build full grid (7 days × each category)
        return $categories->flatMap(function ($cat, $catIndex) use ($days, $data) {
            return $days->map(function ($day) use ($cat, $catIndex, $data) {
                $found = $data->first(fn($r) => $r->weekday == $day && $r->category_name == $cat);
                return [
                    'x' => $day - 1, // 0–6 for JS
                    'y' => $catIndex,
                    'v' => $found?->total ?? 0,
                    'category' => $cat,
                ];
            });
        })->values();
    }
}



