@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />

<style>
    .category-pill {
        transition: all 0.2s ease-in-out;
    }

    .category-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    #topCategoryChart {
        max-width: 320px;
        max-height: 320px;
        margin: 0 auto;
    }

    .filter-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #e9ecef;
    }

    .filter-btn-group .btn {
        border-radius: 8px;
        margin: 2px;
    }

    .date-range-inputs {
        border-left: 2px solid #dee2e6;
        padding-left: 15px;
    }

    @media (max-width: 768px) {
        .date-range-inputs {
            border-left: none;
            border-top: 2px solid #dee2e6;
            padding-left: 0;
            padding-top: 15px;
            margin-top: 15px;
        }
    }
</style>
@endsection

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
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Order Nos.</span>
                                        <h6 class="fw-semibold mb-1">
                                            {{ total_order_count() }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                        <i class="ri-file-list-3-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Returned Nos.</span>
                                        <h6 class="fw-semibold mb-1">
                                            {{ 0 }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                        <i class="ri-arrow-go-back-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-3 left-line line-bg-success position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Categories</span>
                                        <h6 class="fw-semibold mb-1">{{ total_category_count() }}
                                        </h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                        <i class="ri-stack-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">SKUs</span>
                                        <h6 class="fw-semibold mb-1">
                                            {{ total_variation_option_count() }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                        <i class="ri-barcode-box-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Low Stock</span>
                                        <h6 class="fw-semibold mb-1">
                                            {{ low_stock_variation_option_count(10) }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                        <i class="ri-alert-line"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Avg Order Val</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ average_order_value() }}
                                        </h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                        <i class="ri-line-chart-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Sales</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ total_orders() }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                        <i class="ri-shopping-cart-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Purchase</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ total_purchase_amount() }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                        <i class="ri-shopping-bag-3-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-3 left-line line-bg-success position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Stock</span>
                                        <h6 class="fw-semibold mb-1">{{ total_stock_count() }}
                                        </h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                        <i class="ri-archive-stack-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Cash</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ total_payment_amount('Cash') }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                        <i class="ri-money-rupee-circle-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Card</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ total_payment_amount('Card') }}</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                        <i class="ri-bank-card-2-fill"></i>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-sm-4">
                            <div
                                class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">UPI</span>
                                        <h6 class="fw-semibold mb-1">
                                            ₹{{ total_payment_amount('UPI') }}
                                        </h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                        <i class="ri-smartphone-line"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- === Sales Statistics (Year Filter) === --}}
        <div class="col-xl-6 col-md-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold text-lg mb-0">📊 Sales Statistics</h5>
                        <!--<select id="salesYearSelect" class="form-select form-select-sm w-auto">-->
                        <!--    @foreach(range(date('Y'), date('Y') - 5) as $year)-->
                        <!--        <option value="{{ $year }}">{{ $year }}</option>-->
                        <!--    @endforeach-->
                        <!--</select>-->
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-0">Total Revenue:</h6>
                        <h4 class="fw-bold text-primary mb-0" id="totalRevenue">₹0</h4>
                    </div>
                    <canvas id="salesStatisticsChart" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- === Sales vs Purchase (Range Filter) === --}}
        <div class="col-xl-6 col-md-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                        <h5 class="fw-bold text-lg mb-2">💹 Sales vs Purchase</h5>
                        <div class="btn-group btn-group-sm" id="rangeFilterButtons" role="group">
                            <!--@foreach(['1D','1W','1M','3M','6M','1Y'] as $range)-->
                            <!--    <button type="button" class="btn btn-outline-primary range-btn" data-range="{{ $range }}">{{ $range }}</button>-->
                            <!--@endforeach-->


                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"today"}'>1D</button>
                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"week"}'>1W</button>
                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"month"}'>1M</button>
                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"3month"}'>3M</button>
                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"6month"}'>6M</button>
                            <button type="button" class="btn btn-outline-primary range-btn" data-range='{"period":"year"}'>1Y</button>
                            <button type="button" class="btn btn-outline-primary" id="customRangeBtn">
                                <i class="fa fa-calendar-alt"></i> Custom
                            </button>

                            <!--<input type="date" id="fromDate" class="form-select form-select-sm w-auto">-->
                            <!--<input type="date" id="toDate" class="form-select form-select-sm w-auto">-->
                            <!--<button onclick="applyCustomFilter()" class="btn btn-sm-primary">Apply</button>-->
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="text-muted mb-0">Total Sales:</h6>
                            <h5 class="fw-bold text-success mb-0" id="totalSales">₹0</h5>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Total Purchase:</h6>
                            <h5 class="fw-bold text-danger mb-0" id="totalPurchase">₹0</h5>
                        </div>
                    </div>
                    <canvas id="salesPurchaseChart" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- === Top Selling Products === --}}
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-lg mb-0">🥇 Top Selling Products</h6>
                    </div>

                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach(top_selling_products(10) as $prod)
                                <tr>
                                    <td width="20">
                                        <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}" class="rounded"
                                            width="20" height="20" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="mb-0 text-dark text-normal">{{ $prod['name'] }}</div>
                                        <small class="text-muted">{{ $prod['category'] ?? 'Uncategorized' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-success">{{ $prod['sold'] }}</span>
                                        <small class="d-block text-muted">sold</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- === Low Stock Products === --}}
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-lg mb-0">⚠️ Low Stock Products</h6>
                    </div>

                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach(low_stock_products(20) as $prod)
                                <tr>
                                    <td width="20">
                                        <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}" class="rounded"
                                            width="20" height="20" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="mb-0 text-dark text-normal">{{ $prod['name'] }}</div>
                                        <small class="text-muted">{{ $prod['category'] ?? 'Uncategorized' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-danger">{{ $prod['stock'] }}</span>
                                        <small class="d-block text-muted">left</small>
                                    </td>
                                </tr>
                                @endforeach
                                @if(empty(low_stock_products(5)))
                                <tr>
                                    <td colspan="3" class="text-center text-muted">All stocks are sufficient</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @php
        $recentSales = recent_sales(20);
        @endphp

        {{-- === Recent Sales === --}}
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-lg mb-0">🕒 Recent Sales</h6>
                    </div>

                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @forelse($recentSales as $sale)
                                <tr>
                                    <td width="60">
                                        <img src="{{ $sale['product_image'] }}" alt="{{ $sale['product_name'] }}"
                                            class="rounded" width="50" height="50" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="mb-0 text-dark text-normal">{{ $sale['product_name'] }}</div>
                                        <small class="text-muted">{{ $sale['customer_name'] ?? 'Unknown' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-success">
                                            ₹{{ number_format($sale['amount'],2) }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ \Carbon\Carbon::parse($sale['date'])->diffForHumans() }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No recent sales</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- === Top Customers === --}}
        {{-- <div class="col-xl-4 col-md-6 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-lg mb-0">👑 Top Customers</h6>
                    </div>
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach(top_customers() as $cust)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $cust['name'] }}</h6>
                                            <small class="text-muted">{{ $cust['email'] }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold text-success">
                                            ₹{{ number_format($cust['total_spent'], 2) }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ $cust['total_orders'] }} orders
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- === Top Categories (Doughnut Chart + List) === --}}
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20 text-center">
                    <h6 class="fw-bold text-lg mb-3">🏷️ Top Categories</h6>

                    {{-- Doughnut Chart --}}
                    <div class="d-flex justify-content-center">
                        <canvas id="topCategoryChart" height="320" width="320"></canvas>
                    </div>

                    {{-- Row-wise Category Pills --}}
                    <div id="categoryList" class="d-flex flex-wrap justify-content-start gap-2 mt-4"></div>
                </div>
            </div>
        </div>


        {{-- === Order Statistics (Heatmap) === --}}
        <div class="col-xl-4 col-md-12 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <h6 class="fw-bold text-lg mb-3">🔥 Order Statistics (Time × Day)</h6>

                    <div style="position: relative; height: 420px; width: 100%;">
                        <canvas id="orderHeatmapChart"></canvas>
                    </div>

                    <p class="text-center text-muted mt-2 mb-0">
                        Each square = 1 hour × 1 day
                    </p>
                </div>
            </div>
        </div>

        {{-- === Category Sales Heatmap === --}}
        <div class="col-xl-4 col-md-12 col-12">
            <div class="card shadow radius-12 h-100">
                <div class="card-body p-20">
                    <h6 class="fw-bold text-lg mb-3">📊 Day-wise Category Sales</h6>

                    <div style="position: relative; height: 420px; width: 100%;">
                        <canvas id="categoryHeatmapChart"></canvas>
                    </div>

                    <p class="text-center text-muted mt-2 mb-0">
                        Each square = Category × Day
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@2.0.1"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<script src="{{ asset('assets/dashboard-assets/js/homeOneChart.js') }}"></script>


<script>
    const monthlySalesData = {!! json_encode(monthly_sales_data())!!};
    const dailySalesData = @json(daily_sales_data());
    const dailyLabels = @json(collect(range(0, 6)) -> map(fn($i) => now() -> subDays(6 - $i) -> format('d M')) -> toArray());

    const monthLabels = {!! json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])!!};
</script>

<script>
    const orderLabels = @json(collect(daily_order_stats()) -> pluck('date'));
    const orderCounts = @json(collect(daily_order_stats()) -> pluck('count'));



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
                formatter: function (val) {
                    return val + " Orders";
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#barChart"), options);
    chart.render();
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        // === Gradient Helper ===
        function gradientFill(ctx, color1, color2) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }

        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


        /* ---------------------------------------------
        SALES STATISTICS (YEAR FILTER)
        ----------------------------------------------*/
        const ctxSales = document.getElementById('salesStatisticsChart').getContext('2d');
        let salesChart;

        async function renderSalesChart(params = {}) {

            const query = new URLSearchParams(params).toString();
            const res = await fetch(`/chart/sales?${query}`);
            const result = await res.json();

            const labels = result.labels;
            const data = result.values;

            document.getElementById('totalRevenue').innerText =
                "₹" + result.total.toLocaleString();

            if (salesChart) salesChart.destroy();

            salesChart = new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: "Sales",
                        data,
                        backgroundColor: gradientFill(ctxSales, '#487FFF', '#A0BFFF'),
                        borderRadius: 8,
                        borderSkipped: false,
                        hoverBackgroundColor: '#315ECF'
                    }]
                },
                options: {
                    responsive: true,
                    animation: { duration: 1200, easing: 'easeOutQuart' },
                    legend: { display: false },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => '₹' + v.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        // Load current year on page load
        renderSalesChart({ period: "today" });


        /* ---------------------------------------------
        SALES VS PURCHASE (RANGE FILTER)
        ----------------------------------------------*/
        const ctxSP = document.getElementById('salesPurchaseChart').getContext('2d');
        let spChart;

        async function renderSPChart(params = {}) {

            // 🔥 Fetch real data by selected range
            const query = new URLSearchParams(params).toString();
            const res = await fetch(`/chart/sales-purchase?${query}`);
            const data = await res.json();
            
            const labels = data.labels;
            const sales = data.sales;
            const purchase = data.purchase;

            document.getElementById('totalSales').innerText = "₹" + data.total_sales.toLocaleString();
            document.getElementById('totalPurchase').innerText = "₹" + data.total_purchase.toLocaleString();

            if (spChart) spChart.destroy();

            spChart = new Chart(ctxSP, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Sales',
                            data: sales,
                            backgroundColor: gradientFill(ctxSP, '#487FFF', '#A0BFFF'),
                            borderRadius: 8,
                            barThickness: 18,
                            borderSkipped: false
                        },
                        {
                            label: 'Purchase',
                            data: purchase,
                            backgroundColor: gradientFill(ctxSP, '#FF6384', '#FFB3C1'),
                            borderRadius: 8,
                            barThickness: 18,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 1300, easing: 'easeOutQuint' },
                    plugins: {
                        legend: {
                            display: true,
                            labels: { color: '#444', boxWidth: 12, padding: 20 }
                        },
                        tooltip: {
                            backgroundColor: '#1e1e2d',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                label: ctx => `${ctx.dataset.label}: ₹${ctx.formattedValue}`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#666' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { color: '#666', callback: v => '₹' + v.toLocaleString() }
                        }
                    }
                }
            });
        }

        renderSPChart({ period: "today" });
        loadTopCategories({ period: "today" });
        loadHeatmap({ period: "today" });
        loadCategoryHeatmap({ period: "today" });

        document.querySelectorAll('.range-btn').forEach(btn => {
            btn.addEventListener('click', function () {

                document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));

                this.classList.add('active');

                const params = JSON.parse(this.dataset.range);
                const { period } = JSON.parse(this.dataset.range);
                
                
                renderSalesChart(params);
                renderSPChart(params);
                loadTopCategories(params);
                loadHeatmap(params);
                loadCategoryHeatmap(params);
            });
        });
        
        
        
        let picker = null;

        document.getElementById('customRangeBtn').addEventListener('click', function () {
        
            // Remove active class from preset buttons
            document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
        
            if (picker) {
                picker.show();
                return;
            }
        
            picker = new Litepicker({
                element: document.getElementById('customRangeBtn'),
                singleMode: false,
                selectForward: true,
                autoApply: true,
                numberOfMonths: 2,
                numberOfColumns: 2,
                format: "YYYY-MM-DD",
                allowRepick: true, // IMPORTANT: ensures onSelect always fires
        
                setup: (picker) => {
        
                    picker.on('selected', (date1, date2) => {
                        if (!date1 || !date2) return;
        
                        console.log("Range Selected:", date1.format('YYYY-MM-DD'), date2.format('YYYY-MM-DD'));
        
                        const params = {
                            period: "custom",
                            from_date: date1.format("YYYY-MM-DD"),
                            to_date: date2.format("YYYY-MM-DD")
                        };
        
                        console.log('ok');  // <-- THIS WILL WORK NOW
        
                        renderSalesChart(params);
                        renderSPChart(params);
                        loadTopCategories(params);
                        loadHeatmap(params);
                        loadCategoryHeatmap(params);
                    });
        
                }
            });
        
            picker.show();
        });
        

    });

</script>


<script>
    // ====== Top Categories Doughnut ======
    async function loadTopCategories(params = {}) {

        const query = new URLSearchParams(params).toString();
        const res = await fetch(`/chart/top-categories?${query}`);
        const data = await res.json();
    
        // Destroy previous pills
        const categoryList = document.getElementById('categoryList');
        categoryList.innerHTML = "";
    
        // Destroy previous chart if exists
         if (window.topCategoryChart instanceof Chart) {
            window.topCategoryChart.destroy();
        }
    
        // Prepare chart data
        const catLabels = data.map(c => c.name);
        const catValues = data.map(c => c.count);
        const catColors = data.map(c => c.color);
    
        // Render chart
        window.topCategoryChart = new Chart(document.getElementById('topCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: catColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    
        // Render pills
        data.forEach(cat => {
            const pill = document.createElement('div');
            pill.className = 'd-flex align-items-center px-3 py-1 rounded-pill category-pill';
            pill.style.backgroundColor = `${cat.color}22`;
            pill.style.border = `1px solid ${cat.color}`;
            pill.innerHTML = `
                <span class="d-inline-block rounded-circle me-2"
                    style="width:10px;height:10px;background-color:${cat.color}"></span>
                <span class="fw-semibold text-dark">${cat.name}</span>
                <span class="ms-2 text-muted small fw-semibold">(${cat.count})</span>
            `;
            categoryList.appendChild(pill);
        });
    }

</script>

<script>
    let heatmapChart = null;

    async function loadHeatmap(params = {}) {
        
        const query = new URLSearchParams(params).toString();
        const res = await fetch(`/chart/order-heatmap?${query}`);
        const dynamicData = await res.json();
    
        const dayLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        const hourLabels = [
            '12 am','1 am','2 am','3 am','4 am','5 am','6 am','7 am','8 am','9 am','10 am','11 am',
            '12 pm','1 pm','2 pm','3 pm','4 pm','5 pm','6 pm','7 pm','8 pm','9 pm','10 pm','11 pm'
        ];
    
        const maxOrders = Math.max(...dynamicData.map(d => d.v), 1);
    
        const ctx = document.getElementById('orderHeatmapChart').getContext('2d');
    
        if (heatmapChart) heatmapChart.destroy();
    
        heatmapChart = new Chart(ctx, {
            type: 'matrix',
            data: {
                datasets: [{
                    data: dynamicData,
                    backgroundColor(ctx) {
                        const v = ctx.dataset.data[ctx.dataIndex].v;
                        const alpha = v / maxOrders;
                        return `rgba(255,120,0,${0.4 + alpha * 0.6})`;
                    },
                    borderColor: 'rgba(255,255,255,0.9)',
                    borderWidth: 2,
                    width(ctx) {
                        const a = ctx.chart.chartArea;
                        return a ? a.width / 7 - 6 : 40;
                    },
                    height(ctx) {
                        const a = ctx.chart.chartArea;
                        return a ? a.height / 24 - 4 : 20;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: "linear",
                        min: -0.5,
                        max: 6.5,
                        ticks: {
                            stepSize: 1,
                            callback: v => "\u00A0\u00A0\u00A0\u00A0" + (dayLabels[Math.round(v)] ?? "")
                        },
                        grid: { display:false }
                    },
                    y: {
                        type: "linear",
                        reverse: true,
                        min: -0.5,
                        max: 23.5,
                        ticks: {
                            stepSize: 3,
                            callback: v => hourLabels[Math.round(v)] ?? ""
                        },
                        grid: { display:false }
                    }
                },
                plugins: { 
                    legend: { display: false } 
                }
            }
        });
    }

</script>

<script>
    let categoryHeatmapChart = null;

    async function loadCategoryHeatmap(params = {}) {
    
        const query = new URLSearchParams(params).toString();
        const res = await fetch(`/chart/category-heatmap?${query}`);
        const catHeatmapData = await res.json();
    
        const dayLabels = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        const categoryLabels = [...new Set(catHeatmapData.map(d => d.category))];
    
        const maxVal = Math.max(...catHeatmapData.map(d => d.v), 1);
    
        const ctx = document.getElementById("categoryHeatmapChart").getContext("2d");
    
        if (categoryHeatmapChart) categoryHeatmapChart.destroy();
    
        categoryHeatmapChart = new Chart(ctx, {
            type: "matrix",
            data: {
                datasets: [{
                    data: catHeatmapData,
                    backgroundColor(ctx) {
                        const value = ctx.dataset.data[ctx.dataIndex].v;
                        const alpha = value / maxVal;
                        return `rgba(54,162,235,${0.2 + alpha * 0.8})`;
                    },
                    borderWidth: 2,
                    borderColor: "rgba(255,255,255,0.9)",
                    width(c) {
                        const a = c.chart.chartArea;
                        return a ? (a.width / 7) - 4 : 40;
                    },
                    height(c) {
                        const a = c.chart.chartArea;
                        return a ? (a.height / categoryLabels.length) - 4 : 40;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: "linear",
                        min: -0.5,
                        max: 6.5,
                        ticks: {
                            callback: v => dayLabels[Math.round(v)] ?? "",
                            stepSize: 1
                        },
                        grid: { display: false }
                    },
                    y: {
                        type: "linear",
                        reverse: false,
                        min: -0.5,
                        max: categoryLabels.length - 0.5,
                        ticks: {
                            callback: v => categoryLabels[Math.round(v)] ?? "",
                            stepSize: 1
                        },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label(ctx) {
                                const day = dayLabels[ctx.raw.x];
                                const cat = categoryLabels[ctx.raw.y];
                                return `${cat} on ${day}: ${ctx.raw.v} sales`;
                            }
                        }
                    }
                }
            }
        });
    }

    

</script>
@endsection