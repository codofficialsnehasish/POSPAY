@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
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
            {{-- <div class="col-12">
                <div class="card radius-12">
                    <div class="card-body p-16">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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


                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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

                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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

                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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

                            <div class="col-xxl-2 col-xl-4 col-sm-6">
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
            </div> --}}

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
                            <select id="salesYearSelect" class="form-select form-select-sm w-auto">
                                @foreach(range(date('Y'), date('Y') - 5) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
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
                                @foreach(['1D','1W','1M','3M','6M','1Y'] as $range)
                                    <button type="button" class="btn btn-outline-primary range-btn" data-range="{{ $range }}">{{ $range }}</button>
                                @endforeach
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
                                    @foreach(top_selling_products(20) as $prod)
                                        <tr>
                                            <td width="20">
                                                <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}"
                                                    class="rounded" width="20" height="20" style="object-fit: cover;">
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
                                                <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}"
                                                    class="rounded" width="20" height="20" style="object-fit: cover;">
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
                                        <tr><td colspan="3" class="text-center text-muted">All stocks are sufficient</td></tr>
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
                                                <img src="{{ $sale['product_image'] }}" 
                                                    alt="{{ $sale['product_name'] }}"
                                                    class="rounded" width="50" height="50" 
                                                    style="object-fit: cover;">
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
                                        <tr><td colspan="3" class="text-center text-muted">No recent sales</td></tr>
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






        
            {{-- <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Top Selling Product</h6>
                        </div>
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Items</th>
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
                                            <td style="max-height: 100px;">
                                                <div class="d-flex align-items-center">
                                                        <img class="img-thumbnail rounded me-2"
                                                            style="object-fit: contain;height: 100px;"
                                                            src="{{ $prod['image_url'] }}"
                                                            width="100"
                                                            alt="{{ $prod['name'] }}">
                                                    <div class="flex-grow-1">
                                                        <h6 class="text-md mb-0 fw-normal">{{ $prod['name'] }}</h6>
                                                        <span class="text-sm text-secondary-light fw-normal">
                                                            {{ $prod['category'] }}
                                                        </span>
                                                    </div>
                                                </div>
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
            </div> --}}

            {{-- <div class="col-12">
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
            </div> --}}

            {{-- <div class="col-12">
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
            </div> --}}
            @if(auth()->user()->hasRole('Super Admin'))
            {{-- <div class="col-12">
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
            </div> --}}
            @endif
        </div>

    </div>

@endsection

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@2.0.1"></script>


    <script src="{{ asset('assets/dashboard-assets/js/homeOneChart.js') }}"></script>


    <script>
        const monthlySalesData = {!! json_encode(monthly_sales_data()) !!};
        const dailySalesData = @json(daily_sales_data());
        const dailyLabels = @json(collect(range(0, 6))->map(fn($i) => now()->subDays(6 - $i)->format('d M'))->toArray());

        const monthLabels = {!! json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};


        // const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        // new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: monthLabels,
        //         datasets: [{
        //             label: 'Monthly Sales (INR)',
        //             data: monthlySalesData,
        //             backgroundColor: 'rgba(54, 162, 235, 0.6)',
        //             borderColor: 'rgba(54, 162, 235, 1)',
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true,
        //                 ticks: {
        //                     callback: function(value) {
        //                         return '₹' + value.toLocaleString();
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // });


        // const ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
        // new Chart(ctxDaily, {
        //     type: 'bar',
        //     data: {
        //         labels: dailyLabels,
        //         datasets: [{
        //             label: 'Daily Sales (INR)',
        //             data: dailySalesData,
        //             backgroundColor: 'rgba(255, 99, 132, 0.6)',
        //             borderColor: 'rgba(255, 99, 132, 1)',
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });

        // const vendorOrderCounts = @json(vendor_wise_order_count());
        // const vendorLabels = vendorOrderCounts.map(v => v.name);
        // const vendorData = vendorOrderCounts.map(v => v.count);
        // const ctxVendor = document.getElementById('vendorOrderChart').getContext('2d');
        // new Chart(ctxVendor, {
        //     type: 'bar',
        //     data: {
        //         labels: vendorLabels,
        //         datasets: [{
        //             label: 'Orders by Vendor',
        //             data: vendorData,
        //             backgroundColor: 'rgba(75, 192, 192, 0.6)',
        //             borderColor: 'rgba(75, 192, 192, 1)',
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         indexAxis: 'y', // Makes it horizontal
        //         scales: {
        //             x: {
        //                 beginAtZero: true,
        //                 ticks: {
        //                     stepSize: 1
        //                 }
        //             }
        //         }
        //     }
        // });
    </script>

    <script>
        const orderLabels = @json(collect(daily_order_stats())->pluck('date'));
        const orderCounts = @json(collect(daily_order_stats())->pluck('count'));



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

    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {

            // Gradient helper
            function gradientFill(ctx, color1, color2) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                return gradient;
            }

            // === Sales Statistics Chart ===
            const ctxSales = document.getElementById('salesStatisticsChart').getContext('2d');
            let salesChart;
            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

            function renderSalesChart(year) {
                const data = months.map(() => Math.floor(Math.random() * 60000) + 10000); // demo data
                const totalRevenue = data.reduce((a,b) => a+b, 0);
                document.getElementById('totalRevenue').innerText = '₹' + totalRevenue.toLocaleString();

                if (salesChart) salesChart.destroy();

                salesChart = new Chart(ctxSales, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: `Sales in ${year}`,
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
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e1e2d',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: ctx => `₹${ctx.formattedValue}`
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#777' } },
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => '₹' + v.toLocaleString(), color: '#777' },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            }
                        }
                    }
                });
            }

            renderSalesChart(new Date().getFullYear());
            document.getElementById('salesYearSelect').addEventListener('change', e => renderSalesChart(e.target.value));


            // === Sales vs Purchase Chart ===
            const ctxSP = document.getElementById('salesPurchaseChart').getContext('2d');
            let spChart;

            function renderSPChart(range) {
                const labelsMap = {
                    '1D': ['Today'],
                    '1W': ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                    '1M': Array.from({length: 30}, (_,i)=>i+1),
                    '3M': ['Jan','Feb','Mar'],
                    '6M': ['Jan','Feb','Mar','Apr','May','Jun'],
                    '1Y': months
                };
                const labels = labelsMap[range];

                const sales = labels.map(() => Math.floor(Math.random() * 50000) + 10000);
                const purchase = labels.map(() => Math.floor(Math.random() * 40000) + 8000);

                const totalSales = sales.reduce((a,b)=>a+b,0);
                const totalPurchase = purchase.reduce((a,b)=>a+b,0);

                document.getElementById('totalSales').innerText = '₹' + totalSales.toLocaleString();
                document.getElementById('totalPurchase').innerText = '₹' + totalPurchase.toLocaleString();

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

            renderSPChart('1M');

            document.querySelectorAll('.range-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    renderSPChart(this.dataset.range);
                });
            });

        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // === Gradient Helper ===
            function gradientFill(ctx, color1, color2) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                return gradient;
            }

            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];


            /* ---------------------------------------------
            SALES STATISTICS (YEAR FILTER)
            ----------------------------------------------*/
            const ctxSales = document.getElementById('salesStatisticsChart').getContext('2d');
            let salesChart;

            async function renderSalesChart(year) {

                // 🔥 Fetch real data
                const res = await fetch(`/chart/sales/${year}`);
                const result = await res.json();

                const data = result.months;  // 12 numbers
                const totalRevenue = result.total;

                document.getElementById('totalRevenue').innerText = "₹" + totalRevenue.toLocaleString();

                if (salesChart) salesChart.destroy();

                salesChart = new Chart(ctxSales, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: `Sales in ${year}`,
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
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e1e2d',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: ctx => `₹${ctx.formattedValue}`
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#777' } },
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => '₹' + v.toLocaleString(), color: '#777' },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            }
                        }
                    }
                });
            }

            // Load current year on page load
            renderSalesChart(new Date().getFullYear());

            document.getElementById('salesYearSelect')
                .addEventListener('change', e => renderSalesChart(e.target.value));



            /* ---------------------------------------------
            SALES VS PURCHASE (RANGE FILTER)
            ----------------------------------------------*/
            const ctxSP = document.getElementById('salesPurchaseChart').getContext('2d');
            let spChart;

            async function renderSPChart(range) {

                // 🔥 Fetch real data by selected range
                const res = await fetch(`/chart/sales-purchase/${range}`);
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

            renderSPChart('1M');

            document.querySelectorAll('.range-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    renderSPChart(this.dataset.range);
                });
            });

        });
    </script>


    <script>
        // ====== Top Categories Doughnut ======
        const topCategoryData = @json(top_categories());
        const catLabels = topCategoryData.map(c => c.name);
        const catValues = topCategoryData.map(c => c.count);
        const catColors = topCategoryData.map(c => c.color);

        // Render Doughnut Chart (smaller & smoother)
        new Chart(document.getElementById('topCategoryChart'), {
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
                cutout: '50%', // smaller ring (increased from 70%)
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.parsed} orders`
                        }
                    }
                },
                animation: { animateRotate: true, animateScale: true }
            }
        });

        // ====== Create matching color pills ======
        const categoryList = document.getElementById('categoryList');
        topCategoryData.forEach(cat => {
            const pill = document.createElement('div');
            pill.className = 'd-flex align-items-center px-3 py-1 rounded-pill category-pill';
            pill.style.backgroundColor = `${cat.color}15`; // soft background
            pill.style.border = `1px solid ${cat.color}`;
            pill.innerHTML = `
                <span class="d-inline-block rounded-circle me-2"
                    style="width:10px;height:10px;background-color:${cat.color}"></span>
                <span class="fw-semibold text-dark">${cat.name}</span>
                <span class="ms-2 text-muted small fw-semibold">(${cat.count})</span>
            `;
            categoryList.appendChild(pill);
        });
    </script>

    <script>
        const heatmapData = @json(order_heatmap_data());

        // ✅ Exact label sets
        const dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const hourLabels = [
            '12 am','1 am','2 am','3 am','4 am','5 am','6 am','7 am','8 am','9 am','10 am','11 am',
            '12 pm','1 pm','2 pm','3 pm','4 pm','5 pm','6 pm','7 pm','8 pm','9 pm','10 pm','11 pm'
        ];

        const maxOrders = Math.max(...heatmapData.map(d => d.v), 1);

        // ✅ Chart init
        const ctxHeatmap = document.getElementById('orderHeatmapChart').getContext('2d');

        new Chart(ctxHeatmap, {
            type: 'matrix',
            data: {
                datasets: [{
                    label: 'Orders Heatmap',
                    data: heatmapData.map(d => ({ x: d.x, y: d.y, v: d.v })),
                    backgroundColor(ctx) {
                        const value = ctx.dataset.data[ctx.dataIndex].v;
                        const alpha = value / maxOrders;
                        // return `rgba(255, 159, 64, ${0.25 + alpha * 0.75})`;
                        return `rgba(255, 120, 0, ${0.4 + alpha * 0.6})`;
                    },
                    borderColor: 'rgba(255,255,255,0.9)',
                    borderWidth: 2,
                    width(ctx) {
                        const area = ctx.chart.chartArea;
                        return area ? (area.width / 7) - 6 : 40;
                    },
                    height(ctx) {
                        const area = ctx.chart.chartArea;
                        return area ? (area.height / 24) - 4 : 20;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { top: 20, right: 20, bottom: 20, left: 20 }
                },
                animation: { duration: 800, easing: 'easeOutQuart' },
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        // ✅ Half-unit padding fixes label clipping
                        min: -0.5,
                        max: 6.5,
                        offset: false,
                        grid: { display: false },
                        ticks: {
                            stepSize: 1,
                            // ✅ Force integer rounding to avoid floating indices
                            callback: (value) => {
                                const index = Math.round(value);
                                // return dayLabels[index] ?? '';
                                const label = dayLabels[index] ?? '';
                                // Add uniform left "padding" (~20px visual)
                                return '\u00A0\u00A0\u00A0\u00A0' + label; // 4 non-breaking spaces ≈ 20px
                            },
                            color: '#333',
                            font: { size: 12, weight: '500' },
                            padding: 6
                        },
                        // title: {
                        //     display: true,
                        //     text: 'Day of Week',
                        //     color: '#555',
                        //     font: { size: 13, weight: 'bold' }
                        // }
                    },
                    y: {
                        type: 'linear',
                        reverse: true,
                        min: -0.5,
                        max: 23.5,
                        grid: { display: false },
                        offset: false,
                        ticks: {
                            stepSize: 3, // every 3 hours for clean look
                            callback: (value) => {
                                const index = Math.round(value);
                                return hourLabels[index] ?? '';
                            },
                            color: '#333',
                            font: { size: 11 },
                            padding: 5
                        },
                        // title: {
                        //     display: true,
                        //     text: 'Hour of Day',
                        //     color: '#555',
                        //     font: { size: 13, weight: 'bold' }
                        // }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#000',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: ctx => {
                                const day = dayLabels[ctx.raw.x];
                                const hour = hourLabels[ctx.raw.y];
                                return `${day} ${hour}: ${ctx.raw.v} Orders`;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        const catHeatmapData = @json(category_heatmap_data());

        const catDayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const categoryLabels = [...new Set(catHeatmapData.map(d => d.category))];

        const maxSales = Math.max(...catHeatmapData.map(d => d.v), 1);

        const ctxCat = document.getElementById('categoryHeatmapChart').getContext('2d');

        new Chart(ctxCat, {
            type: 'matrix',
            data: {
                datasets: [{
                    label: 'Category Sales Heatmap',
                    data: catHeatmapData,
                    backgroundColor(ctx) {
                        const value = ctx.dataset.data[ctx.dataIndex].v;
                        const alpha = value / maxSales;
                        return `rgba(54, 162, 235, ${0.2 + alpha * 0.8})`;
                    },
                    borderColor: 'rgba(255,255,255,0.9)',
                    borderWidth: 2,
                    width(ctx) {
                        const area = ctx.chart.chartArea;
                        return area ? (area.width / 7) - 4 : 40;
                    },
                    height(ctx) {
                        const area = ctx.chart.chartArea;
                        return area ? (area.height / categoryLabels.length) - 4 : 40;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 20, right: 20, bottom: 30, left: 20 } },
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        min: -0.5,
                        max: 6.5,
                        grid: { display: false },
                        ticks: {
                            stepSize: 1,
                            callback: (value) => catDayLabels[Math.round(value)] ?? '',
                            color: '#333',
                            font: { size: 12, weight: '500' },
                            padding: 6
                        }
                    },
                    y: {
                        type: 'linear',
                        reverse: false,
                        min: -0.5,
                        max: categoryLabels.length - 0.5,
                        grid: { display: false },
                        ticks: {
                            stepSize: 1,
                            callback: (value) => categoryLabels[Math.round(value)] ?? '',
                            color: '#333',
                            font: { size: 11 },
                            padding: 5
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#000',
                        callbacks: {
                            label: ctx => {
                                const day = catDayLabels[ctx.raw.x];
                                const cat = categoryLabels[ctx.raw.y];
                                return `${cat} on ${day}: ${ctx.raw.v} sales`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
