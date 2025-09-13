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
                                            <i class="ri-shopping-cart-fill"></i>
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
                                            <i class="ri-shopping-cart-fill"></i>
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
                                            <i class="ri-shopping-cart-fill"></i>
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
                                            <i class="ri-shopping-cart-fill"></i>
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
                                            <span class="mb-2 fw-medium text-secondary-light text-md">Yearly Orders</span>
                                            <h6 class="fw-semibold mb-1">{{ format_price(order_total_by_period('yearly')) }}
                                            </h6>
                                        </div>
                                        <span
                                            class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                            <i class="ri-shopping-cart-fill"></i>
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

            {{-- <div class="col-xxl-8">
                <div class="card h-100">
                    <div class="card-body p-24 mb-8">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Income Vs Expense </h6>
                            <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                                <option>Yearly</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Today</option>
                            </select>
                        </div>
                        <ul class="d-flex flex-wrap align-items-center justify-content-center my-3 gap-24">
                            <li class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-8-px h-8-px rounded-pill bg-primary-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Income </span>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <h6 class="mb-0">$26,201</h6>
                                    <span class="text-success-600 d-flex align-items-center gap-1 text-sm fw-bolder">
                                        10%
                                        <i class="ri-arrow-up-s-fill d-flex"></i>
                                    </span>
                                </div>
                            </li>
                            <li class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-8-px h-8-px rounded-pill bg-warning-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Expenses </span>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <h6 class="mb-0">$18,120</h6>
                                    <span class="text-danger-600 d-flex align-items-center gap-1 text-sm fw-bolder">
                                        10%
                                        <i class="ri-arrow-down-s-fill d-flex"></i>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div id="incomeExpense" class="apexcharts-tooltip-style-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Users</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-20">
                        <div class="d-flex flex-column gap-24">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard-assets/images/user-grid/user-grid-img1.png') }}"
                                        alt=""
                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0">Psychiatry</h6>
                                        <span class="text-sm text-secondary-light fw-normal">Super Admin</span>
                                    </div>
                                </div>
                                <span class="text-warning-main fw-medium text-md">Pending</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard-assets/images/user-grid/user-grid-img2.png') }}"
                                        alt=""
                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0">Orthopedic</h6>
                                        <span class="text-sm text-secondary-light fw-normal">Admin</span>
                                    </div>
                                </div>
                                <span class="text-success-main fw-medium text-md">Active</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard-assets/images/user-grid/user-grid-img3.png') }}"
                                        alt=""
                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0">Cardiology</h6>
                                        <span class="text-sm text-secondary-light fw-normal">Manager</span>
                                    </div>
                                </div>
                                <span class="text-success-main fw-medium text-md">Active</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard-assets/images/user-grid/user-grid-img4.png') }}"
                                        alt=""
                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0">Pediatrics</h6>
                                        <span class="text-sm text-secondary-light fw-normal">Admin</span>
                                    </div>
                                </div>
                                <span class="text-success-main fw-medium text-md">Active</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard-assets/images/user-grid/user-grid-img1.png') }}"
                                        alt=""
                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0">Neurology </h6>
                                        <span class="text-sm text-secondary-light fw-normal">Manager</span>
                                    </div>
                                </div>
                                <span class="text-success-main fw-medium text-md">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Top Suppliers</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">SL</th>
                                        <th scope="col">Name </th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">1</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Esther Howard</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$30,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">2</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Wade Warren</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$40,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">3</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Jenny Wilson</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$50,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">4</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Kristin Watson</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$60,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">5</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Eleanor Pena</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$70,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">6</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Darlene Robertson</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$80,00.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Top Customer</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">SL</th>
                                        <th scope="col">Name </th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">1</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Savannah Nguyen</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$30,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">2</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Annette Black</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$40,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">3</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Theresa Webb</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$50,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">4</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Marvin McKinney</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$60,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">5</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Brooklyn Simmons</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$70,00.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">6</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Dianne Russell</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$80,00.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg">Overall Report</h6>
                            <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                                <option>Yearly</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Today</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <div class="mt-32">
                            <div id="userOverviewDonutChart" class="mx-auto apexcharts-tooltip-z-none"></div>
                        </div>
                        <div class="d-flex flex-wrap gap-20 justify-content-center mt-48">
                            <div class="d-flex align-items-center gap-8">
                                <span class="w-16-px h-16-px radius-2 bg-primary-600"></span>
                                <span class="text-secondary-light">Purchase</span>
                            </div>
                            <div class="d-flex align-items-center gap-8">
                                <span class="w-16-px h-16-px radius-2 bg-lilac-600"></span>
                                <span class="text-secondary-light">Sales</span>
                            </div>
                            <div class="d-flex align-items-center gap-8">
                                <span class="w-16-px h-16-px radius-2 bg-warning-600"></span>
                                <span class="text-secondary-light">Expense</span>
                            </div>
                            <div class="d-flex align-items-center gap-8">
                                <span class="w-16-px h-16-px radius-2 bg-success-600"></span>
                                <span class="text-secondary-light">Gross Profit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Purchase & Sales</h6>
                            <select class="form-select form-select-sm w-auto bg-base text-secondary-light">
                                <option>This Month</option>
                                <option>This Week</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <ul class="d-flex flex-wrap align-items-center justify-content-center my-3 gap-3">
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-8-px rounded-pill bg-warning-600"></span>
                                <span class="text-secondary-light text-sm fw-semibold">Purchase: $<span
                                        class="text-primary-light fw-bold">500</span>
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-8-px rounded-pill bg-success-600"></span>
                                <span class="text-secondary-light text-sm fw-semibold">Sales: $<span
                                        class="text-primary-light fw-bold">800</span>
                                </span>
                            </li>
                        </ul>
                        <div id="purchaseSaleChart" class="margin-16-minus y-value-left"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-8">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Recent Transactions</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <div class="table-responsive scroll-sm">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">SL</th>
                                        <th scope="col">Date </th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Paid Amount</th>
                                        <th scope="col">Due Amount</th>
                                        <th scope="col">Payable Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">1</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">21 Jun 2024</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Cash</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$0.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$150.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$150.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">2</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">21 Jun 2024</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Bank</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$570 </span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$0.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$570.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">3</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">21 Jun 2024</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">PayPal</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$300.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$100.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$200.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">4</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">21 Jun 2024</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">Cash</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$0.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$150.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$150.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary-light">3</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">21 Jun 2024</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">PayPal</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$300.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$100.00</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary-light">$200.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- <div class="row gy-4 mt-1">
            <div class="col-xxl-6 col-xl-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <h6 class="text-lg mb-0">Sales Statistic</h6>
                            <select class="form-select bg-base form-select-sm w-auto">
                                <option>Yearly</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Today</option>
                            </select>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-8">
                            <h6 class="mb-0">$27,200</h6>
                            <span
                                class="text-sm fw-semibold rounded-pill bg-success-focus text-success-main border br-success px-8 py-4 line-height-1 d-flex align-items-center gap-1">
                                10% <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                            </span>
                            <span class="text-xs fw-medium">+ $1500 Per Day</span>
                        </div>
                        <div id="chart" class="pt-28 apexcharts-tooltip-style-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6">
                <div class="card h-100 radius-8 border">
                    <div class="card-body p-24">
                        <h6 class="mb-12 fw-semibold text-lg mb-16">Total Orders</h6>
                        <div class="d-flex align-items-center gap-2 mb-20">
                            <h6 class="fw-semibold mb-0">5,000</h6>
                            <p class="text-sm mb-0">
                                <span
                                    class="bg-danger-focus border br-danger px-8 py-2 rounded-pill fw-semibold text-danger-main text-sm d-inline-flex align-items-center gap-1">
                                    10%
                                    <iconify-icon icon="iconamoon:arrow-down-2-fill" class="icon"></iconify-icon>
                                </span>
                                - 20 Per Day
                            </p>
                        </div>

                        <div id="barChart" class="barChart"></div>

                    </div>
                </div>
            </div>


            <div class="col-xxl-3 col-xl-6">
                <div class="card h-100 radius-8 border-0 overflow-hidden">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg">Users Overview</h6>
                            <div class="">
                                <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                                    <option>Today</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                    <option>Yearly</option>
                                </select>
                            </div>
                        </div>


                        <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>

                        <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                                <span class="text-secondary-light text-sm fw-normal">New:
                                    <span class="text-primary-light fw-semibold">500</span>
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                                <span class="text-secondary-light text-sm fw-normal">Subscribed:
                                    <span class="text-primary-light fw-semibold">300</span>
                                </span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>





        </div> --}}

        <div class="col-xxl-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Top Selling Product</h6>
                        <a href="javascript:void(0)"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            View All
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
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
                            {{-- <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/product/product-img1.png" alt=""
                                                class="flex-shrink-0 me-12 radius-8 me-12">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-normal">Blue t-shirt</h6>
                                                <span class="text-sm text-secondary-light fw-normal">Fashion</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$500.00</td>
                                    <td>15%</td>
                                    <td>300</td>
                                    <td class="text-center">
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">70</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/product/product-img2.png" alt=""
                                                class="flex-shrink-0 me-12 radius-8 me-12">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-normal">Nike Air Shoe</h6>
                                                <span class="text-sm text-secondary-light fw-normal">Fashion</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$150.00</td>
                                    <td>N/A</td>
                                    <td>200</td>
                                    <td class="text-center">
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">70</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/product/product-img3.png" alt=""
                                                class="flex-shrink-0 me-12 radius-8 me-12">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-normal">Woman Dresses</h6>
                                                <span class="text-sm text-secondary-light fw-normal">Fashion</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$300.00</td>
                                    <td>$50.00</td>
                                    <td>1500</td>
                                    <td class="text-center">
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">70</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/product/product-img4.png" alt=""
                                                class="flex-shrink-0 me-12 radius-8 me-12">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-normal">Smart Watch</h6>
                                                <span class="text-sm text-secondary-light fw-normal">Fashion</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$400.00</td>
                                    <td>$50.00</td>
                                    <td>700</td>
                                    <td class="text-center">
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">70</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/product/product-img5.png" alt=""
                                                class="flex-shrink-0 me-12 radius-8 me-12">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-normal">Hoodie Rose</h6>
                                                <span class="text-sm text-secondary-light fw-normal">Fashion</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$300.00</td>
                                    <td>25%</td>
                                    <td>500</td>
                                    <td class="text-center">
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">70</span>
                                    </td>
                                </tr>
                            </tbody> --}}
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
        <h6 class="mb-2 fw-bold text-lg">Monthly Charts</h6>
        <canvas id="monthlySalesChart" height="120"></canvas>
        <h6 class="mb-2 fw-bold text-lg">Daily Charts</h6>
        <canvas id="dailySalesChart" height="120"></canvas>

        <canvas id="vendorOrderChart" height="100"></canvas>

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
