@extends('layouts.app')

@section('title', 'Orders')
@section('css')


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <link href="{{ asset('assets/dashboard-assets/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
@endsection
@section('contents')


    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Orders</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Orders</li>
            </ul>

        </div>

        <div class="card basic-data-table">

            <div class="row gy-4">
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div
                        class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div>
                                <span class="mb-2 fw-medium text-secondary-light text-md">Total Orders</span>
                                <h6 class="fw-semibold mb-1">₹{{ $total_amount }}</h6>
                            </div>
                            <span
                                class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                <i class="ri-shopping-cart-fill"></i>
                            </span>
                        </div>

                    </div>
                </div>
                {{-- <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div
                        class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div>
                                <span class="mb-2 fw-medium text-secondary-light text-md">Total Payment Recevied</span>
                                <h6 class="fw-semibold mb-1">₹35,000</h6>
                            </div>
                            <span
                                class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                <i class="ri-handbag-fill"></i>
                            </span>
                        </div>

                    </div>
                </div> --}}

            </div>
            <form action="{{ route('order.filter') }}" class="mb-2" method="GET" id="filterForm">

                <div class="row">
                    <div class="col-lg-6">
                        <select class="form-select single-select-field" id="vendor_id" name="vendor_id">
                            <option value="" selected disabled> Select Vendor</option>
                            @if (!empty($vendors))
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                         <div class="col-lg-6">
                        <select class="form-select single-select-field" id="category_id" name="category_id">
                            <option value="" selected disabled> Select Category</option>
                            @if (!empty($categories))
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            @endif
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <select class="form-select single-select-field" id="brand_id" name="brand_id">
                            <option value="" selected disabled> Select Brand</option>
                            @if (!empty($brands))
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach

                            @endif
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <select class="form-select single-select-field" id="payment_method" name="payment_method">
                            <option value="" selected disabled> Payment Method</option>
                            <option value="Card" {{ request('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                            <option value="UPI" {{ request('payment_method') == 'UPI' ? 'selected' : '' }}>UPI</option>
                            <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>

                        </select>
                    </div>


                    <div class="col-lg-2 col-md-2 col-sm-2 padding-same mt-2" id="filterPeriodContainer">
                        <select class="form-select" id="filter_period" name="filter_period">
                            {{-- <option value="">Choose period</option> --}}
                            <option value="all" {{ request('filter_period') == 'all' ? 'selected' : '' }}>All
                                Orders</option>
                            <option value="today" {{ request('filter_period') == 'today' ? 'selected' : '' }}>Today
                            </option>
                            <option value="weekly" {{ request('filter_period') == 'weekly' ? 'selected' : '' }}>Weekly
                            </option>
                            <option value="monthly"
                                {{ request('filter_period', 'monthly') == 'monthly' ? 'selected' : '' }}>
                                This Month
                            </option>
                            <option value="last_month" {{ request('filter_period') == 'last_month' ? 'selected' : '' }}>
                                Last Month
                            </option>
                            <option value="quarterly" {{ request('filter_period') == 'quarterly' ? 'selected' : '' }}>This
                                Quarterly
                            </option>
                            <option value="yearly" {{ request('filter_period') == 'yearly' ? 'selected' : '' }}>
                                This Year</option>
                            <option value="custom_date" {{ request('filter_period') == 'custom_date' ? 'selected' : '' }}>
                                Custom Date
                            </option>
                        </select>



                    </div>

                    <!-- Custom Date Filter Fields (Initially Hidden) -->
                    <div class="col-lg-2 col-md-2 col-sm-2 mt-2 fixing_padding" id="customDateFieldsForm"
                        style="display: none;">
                        <input type="date" class="form-control datetimepicker" id="from_date" placeholder="dd-mm-yyyy"
                            name="custom_from_date" value="{{ request('custom_from_date') }}"
                            data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}'>
                        <p>to</p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 mt-2 padding-same" id="customDateFieldsTo"
                        style="display: none;">
                        <input type="date" class="form-control datetimepicker" placeholder="dd-mm-yyyy" id="to_date"
                            name="custom_to_date" value="{{ request('custom_to_date') }}"
                            data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}'>
                    </div>


                    <div class="col-lg-2 padding-fixing width-fixing">
                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;">Filter</button>
                        <button type="button" class="btn btn-phoenix-secondary me-2 mb-2 mb-sm-0"
                            id="resetButton">Reset</button>

                    </div>

                </div>
            </form>
            <div
                class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <h5 class="card-title mb-0">All Orders</h5>
                {{-- <a href="{{ route('coupon.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Add New
                </a> --}}
            </div>
            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            {{-- <th>
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">
                                        S.L
                                    </label>
                                </div>
                            </th> --}}
                            <th>Date</th>
                            <th>Order Number</th>
                            <th>Order Type</th>
                            <th>Total Amount</th>
                            <th>Oder Taken </th>
                            <th>Order Status</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                {{-- <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">
                                        {{ $loop->iteration }}
                                    </label>
                                </div>
                            </td> --}}
                                <td class="text-wrap">{{ format_datetime($order->created_at) }}</td>
                                <td class="text-wrap"><a
                                        href="{{ route('order.details', $order->id) }}">{{ $order->order_number }}</a>
                                </td>
                                <td class="text-wrap">{{ ucfirst($order->order_type) }}</td>
                                <td class="text-wrap">{{ $order->total_amount }}</td>
                                <td class="text-wrap">{{ $order->user->name }}</td>
                                <td class="text-wrap">{{ $order->order_status }}</td>
                                <td class="text-wrap">{{ $order->payment_method }} ({{ $order->payment_status }}) </td>
                                <td>
                                    <a href="{{ route('order.details', $order->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:eye"></iconify-icon>
                                    </a>
                                    {{-- <form action="{{ route('order.destroy', $order->id) }}"
                                        onsubmit="return confirm('Are you sure?')" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                            type="submit"><iconify-icon
                                                icon="mingcute:delete-2-line"></iconify-icon></button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/plugins/select2/js/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/dashboard-assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        let table = new DataTable("#dataTable");
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterPeriod = document.getElementById("filter_period");
            const filterPeriodContainer = document.getElementById("filterPeriodContainer");
            // const customDateFields = document.getElementById("customDateFields");
            const customDateFieldsForm = document.getElementById("customDateFieldsForm");
            const customDateFieldsTo = document.getElementById("customDateFieldsTo");




            function toggleCustomDateFields() {
                if (filterPeriod.value === "custom_date") {
                    // filterPeriodContainer.style.display = "none"; // Hide the dropdown
                    customDateFieldsForm.style.display = "flex"; // Show the custom date inputs
                    customDateFieldsTo.style.display = "flex"; // Show the custom date inputs
                } else {
                    // filterPeriodContainer.style.display = "block"; // Show the dropdown
                    customDateFieldsForm.style.display = "none"; // Hide the custom date inputs
                    customDateFieldsTo.style.display = "none"; // Hide the custom date inputs

                }
            }

            // Run function on page load (in case "custom_date" is pre-selected)
            toggleCustomDateFields();

            // Attach event listener
            filterPeriod.addEventListener("change", toggleCustomDateFields);
        });
        document.getElementById('resetButton').addEventListener('click', function() {
            // Reset all form inputs to their default values
            document.getElementById('filterForm').reset();
            // Remove any selected options from the "Choices" plugin if used
            const choicesSelects = document.querySelectorAll('[data-choices]');
            choicesSelects.forEach(select => {
                const choicesInstance = select.choices;
                if (choicesInstance) {
                    choicesInstance.clearStore(); // Clear selected choices
                }
            });
            // Optionally reload the page without any filters
            window.location.href = "{{ route('order.index') }}";
        });
    </script>
@endsection
