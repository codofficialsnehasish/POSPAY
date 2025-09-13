@extends('layouts.app')

@section('title', 'Dashboard')

@section('contents')

    <div class="dashboard-main-body">

        {{-- <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">POS & Inventory</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">POS & Inventory</li>
        </ul>
        </div> --}}

        <div class="row gy-4">
            <div class="col-12">
                <div class="card radius-12">
                    <div class="card-body p-16">
                        <div class="row gy-4">
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Total Orders</span>
                                            <h6 class="fw-semibold mb-1">{{ format_price(total_orders()) }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                            <i class="ri-shopping-cart-2-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Today Orders</span>
                                            <h6 class="fw-semibold mb-1">{{ format_price(order_total_by_period('today')) }}
                                            </h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                            <i class="ri-handbag-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-3 left-line line-bg-success position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Weekly Orders</span>
                                            <h6 class="fw-semibold mb-1">{{ format_price(order_total_by_period('weekly')) }}
                                            </h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                            <i class="ri-calendar-2-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Monthly Orders</span>
                                            <h6 class="fw-semibold mb-1">
                                                {{ format_price(order_total_by_period('monthly')) }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                            <i class="ri-calendar-event-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Last Month
                                                Orders</span>
                                            <h6 class="fw-semibold mb-1">
                                                {{ format_price(order_total_by_period('last_month')) }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                            <i class="ri-calendar-check-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Quarterly
                                                Orders</span>
                                            <h6 class="fw-semibold mb-1">
                                                {{ format_price(order_total_by_period('quarterly')) }}
                                            </h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                            <i class="ri-pie-chart-2-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-3 left-line line-bg-success position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Yearly Orders</span>
                                            <h6 class="fw-semibold mb-1">{{ format_price(order_total_by_period('yearly')) }}
                                            </h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                            <i class="ri-bar-chart-2-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            {{-- Total Vendors --}}


                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Total Vendors</span>
                                            <h6 class="fw-semibold mb-1">{{ total_vendors() }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                            <i class="ri-user-3-fill"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            {{-- Total Products --}}
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Total Products</span>
                                            <h6 class="fw-semibold mb-1">{{ total_products() }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-200 text-warning-600">
                                            <i class="ri-box-3-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Brands --}}
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-3 left-line line-bg-info position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Total Brands</span>
                                            <h6 class="fw-semibold mb-1">{{ total_brands() }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-info-200 text-info-600">
                                            <i class="ri-price-tag-3-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Categories --}}
                            <div class="col-xxl-3 col-xl-4 col-sm-6">
                                <div
                                    class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-success position-relative overflow-hidden">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Total
                                                Categories</span>
                                            <h6 class="fw-semibold mb-1">{{ total_categories() }}</h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                            <i class="ri-shapes-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Top Selling Product</h6>
                            {{-- <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a> --}}
                        </div>
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Items</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Discount </th>
                                        <th scope="col">Sold</th>
                                        <th scope="col" class="text-center">Total Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $topProducts =  top_selling_products()
                                    @endphp
                                    @foreach ($topProducts as $prod)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}"
                                                        class="flex-shrink-0 me-12 radius-8">
                                                    <div class="flex-grow-1">
                                                        <h6 class="text-md mb-0 fw-normal">{{ $prod['name'] }}</h6>
                                                        <span class="text-sm text-secondary-light fw-normal">
                                                            {{ $prod['category'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₹{{ number_format($prod['price'], 2) }}</td>
                                            <td>
                                                @if ($prod['discount'])
                                                    {{ $prod['discount'] }}%
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $prod['sold'] }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">
                                                    {{ $prod['total_orders'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Monthly Charts</h6>
                        </div>
                        <div class="table-responsive scroll-sm">
                            <canvas id="monthlySalesChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Daily Charts</h6>
                        </div>
                        <div class="table-responsive scroll-sm">
                            <canvas id="dailySalesChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Orders By Vendor</h6>
                        </div>
                        <div class="table-responsive scroll-sm">
                            <canvas id="vendorOrderChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('assets/dashboard-assets/js/homeOneChart.js') }}"></script>


    <script>
        const monthlySalesData = {!! json_encode(monthly_sales_data()) !!};
        const dailySalesData = @json(daily_sales_data());
        const dailyLabels = @json(collect(range(0, 6))->map(fn($i) => now()->subDays(6 - $i)->format('d M'))->toArray());

        const monthLabels = {!! json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};


        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Monthly Sales (INR)',
                    data: monthlySalesData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });


        const ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Daily Sales (INR)',
                    data: dailySalesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const vendorOrderCounts = @json(vendor_wise_order_count());
        const vendorLabels = vendorOrderCounts.map(v => v.name);
        const vendorData = vendorOrderCounts.map(v => v.count);
        const ctxVendor = document.getElementById('vendorOrderChart').getContext('2d');
        new Chart(ctxVendor, {
            type: 'bar',
            data: {
                labels: vendorLabels,
                datasets: [{
                    label: 'Orders by Vendor',
                    data: vendorData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Makes it horizontal
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <script>
        const orderLabels = @json(collect(daily_order_stats())->pluck('date'));
        const orderCounts = @json(collect(daily_order_stats())->pluck('count'));

        console.log(orderLabels);
        console.log(orderCounts);



        var options = {
            chart: {
                type: 'bar',
                height: 100,
                sparkline: {
                    enabled: true
                }
            },
            series: [{
                name: 'Orders',
                data: orderCounts
            }],
            xaxis: {
                categories: orderLabels
            },
            colors: ['#487FFF'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Orders";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#barChart"), options);
        chart.render();
    </script>

@endsection
