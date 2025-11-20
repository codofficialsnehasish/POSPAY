@extends('layouts.app')

@section('title', 'Products')

@section('css')

    <link href="{{ asset('assets/dashboard-assets/vendors/choices/choices.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
        <style>
        span.selection {
    width: 220px;
}
table.dataTable thead>tr>th.dt-orderable-asc span.dt-column-order, table.dataTable thead>tr>th.dt-orderable-desc span.dt-column-order, table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order, table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order{
    right: 0px;
}

.availability-toggle {
    display: inline-flex;
    border: 2px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
}

.toggle-option {
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    background: #f5f5f5;
    color: #666;
    user-select: none;
    transition: 0.3s;
}

.toggle-option.active.available {
    background: #28a745;
    color: #fff;
}

.toggle-option.active.unavailable {
    background: #dc3545;
    color: #fff;
}

.toggle-option:hover {
    background: #e9e9e9;
}


        </style>
@endsection

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Products</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Products</li>
            </ul>


        </div>

        <div class="card basic-data-table">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <!-- Title on left -->
                <h5 class="card-title mb-0">All Products</h5>

                <!-- Buttons on right -->
                <div class="d-flex align-items-center gap-2">
                    @can('Product Basic Info Create')
                    <a href="{{ route('products.basic-info-create') }}"
                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New
                    </a>
                    @endcan
                    @can('Product Bulk Upload')
                    <a href="{{ route('products.bulk-upload-form') }}"
                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="mdi:upload-multiple" class="icon text-xl line-height-1"></iconify-icon>
                        Bulk Upload
                    </a>
                    @endcan
                </div>
            </div>

            <form action="{{ route('products.multiple.filter') }}" class="mb-2 p-3" method="GET" id="filterForm">

                <div class="row">
                    <div class="col-lg-3">
                        <select class="form-select single-select-field" id="brand_id" name="brand_id"
                            data-choices="data-choices"
                            data-options='{"removeItemButton":true,"placeholder":true,"searchResultLimit":20}'>
                            <option value="" selected disabled> Brand</option>
                            @if (!empty($brands))
                                @foreach ($brands as $value)
                                    <option value="{{ $value->id }}"
                                        {{ request('brand_id') == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select class="form-select single-select-field" id="category_id" name="category_id"
                            data-choices="data-choices"
                            data-options='{"removeItemButton":true,"placeholder":true,"searchResultLimit":20}'>
                            <option value="" selected disabled> Categories</option>
                            @if (!empty($categories))
                                @foreach ($categories as $value)
                                    <option value="{{ $value->id }}"
                                        {{ request('category_id') == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
     

                    <div class="col-lg-2 padding-fixing width-fixing" style="width:250px">
                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;width:100px;padding:6px;">Filter</button>
                        <button type="button" class="btn btn-primary btn-phoenix-secondary me-2 mb-2 mb-sm-0"
                            id="resetButton" style="width:100px;padding:6px;">Reset</button>

                    </div>

                </div>
            </form>

            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th class="text-wrap">
                                <div class="form-check style-check d-flex align-items-center">
                                    {{-- <input class="form-check-input" type="checkbox"> --}}
                                    <label class="form-check-label">
                                        S.L
                                    </label>
                                </div>
                            </th>
                            @if(!auth()->user()->hasRole('Vendor'))
                            <th class="text-wrap" style="width: 150px;">Date</th>
                            @endauth
                            <th class="text-wrap">Image</th>
                            <th class="text-wrap">Categories</th>
                            <th class="text-wrap">Title</th>
                            <th class="text-wrap">Variations</th>
                            <th class="text-wrap">Amount</th>
                            {{-- <th class="text-wrap">Description</th> --}}
                            @if(auth()->user()->hasRole('Vendor'))
                            <th class="text-wrap">Availability</th>
                            @else
                            <th class="text-wrap">Status</th>
                            @endif
                            {{-- <th class="text-wrap">Barcode</th> --}}
                            @canany(['Product Basic Info Edit','Product Delete'])
                            <th class="text-wrap">Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products->isNotEmpty())
                            {{-- @foreach ($products as $prouct)
                                <tr class="product-row" data-id="{{ $prouct->id }}">
                                    <td>
                                        <div class="form-check style-check d-flex align-items-center">
                                            <!-- <input class="form-check-input" type="checkbox"> -->
                                            <label class="form-check-label">
                                                {{ $loop->iteration }}
                                            </label>
                                        </div>
                                    </td>
                                    @if(!auth()->user()->hasRole('Vendor'))
                                    <td class="text-wrap" style="width: 150px;">{{ format_datetime($prouct->created_at) }}</td>
                                    @endif
                                    <td style="max-height: 100px;">
                                        @if(getProductMainImage($prouct->id))
                                            <img class="img-thumbnail rounded me-2"
                                                style="object-fit: contain;height: 100px;"
                                                src="{{ getProductMainImage($prouct->id) }}"
                                                width="100"
                                                alt="">
                                        @endif
                                    </td>
                                    <td class="text-wrap">
                                        @if($prouct->categories->count())
                                            {{ $prouct->categories->pluck('name')->join(', ') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-wrap">
                                        {{ $prouct->name }}
                                    </td>
                                    <td class="text-wrap">
                                        @foreach($prouct->variations as $variation)
                                            @foreach($variation->options as $option)
                                                {{ $option->name }}
                                                <br>
                                            @endforeach
                                            <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($prouct->variations as $variation)
                                            @foreach($variation->options as $option)
                                                ₹{{ $option->price }}
                                                <br>
                                            @endforeach
                                            <br>
                                        @endforeach
                                    </td>
                                    @if(auth()->user()->hasRole('Vendor'))
                                        @php
                                            $vendorProduct = \App\Models\VendorProduct::where('vendor_id', auth()->id())
                                                            ->where('product_id', $prouct->id)
                                                            ->first();
                                        @endphp

                                        <td>
                                            <div class="availability-toggle" data-id="{{ $prouct->id }}">
                                                <span class="toggle-option available {{ ($vendorProduct && $vendorProduct->availability) ? 'active' : '' }}">
                                                    Available
                                                </span>

                                                <span class="toggle-option unavailable {{ (!$vendorProduct || !$vendorProduct->availability) ? 'active' : '' }}">
                                                    Unavailable
                                                </span>

                                                <input type="hidden" class="availability-value"
                                                    value="{{ ($vendorProduct && $vendorProduct->availability) ? 1 : 0 }}">
                                            </div>
                                        </td>


                                    @else
                                    <td>{!! check_visibility($prouct->is_visible) !!}</td>
                                    @endif
                                    @canany(['Product Basic Info Edit','Product Delete'])
                                    <td>
                                        @can('Product Basic Info Edit')
                                        <a href="{{ route('products.basic-info-edit', $prouct->id) }}"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        @endcan
                                        @can('Product Delete')
                                        <form action="{{ route('products.delete', $prouct->id) }}"
                                            onsubmit="return confirm('Are you sure?')" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                type="submit"><iconify-icon
                                                    icon="mingcute:delete-2-line"></iconify-icon></button>
                                        </form>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach --}}

                            @foreach ($products as $product)

                                {{-- PRODUCT WITHOUT VARIATIONS --}}
                                @if ($product->variations->count() == 0)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        @if(!auth()->user()->hasRole('Vendor'))
                                            <td>{{ format_datetime($product->created_at) }}</td>
                                        @endif

                                        <td>
                                            @if(getProductMainImage($product->id))
                                                <img class="img-thumbnail rounded"
                                                    style="object-fit: contain;height: 100px;"
                                                    src="{{ getProductMainImage($product->id) }}" width="100" />
                                            @endif
                                        </td>

                                        <td>{{ $product->categories->pluck('name')->join(', ') ?: 'N/A' }}</td>

                                        <td>{{ $product->name }}</td>

                                        <td>—</td>

                                        <td>—</td>

                                        {{-- Status / Availability --}}
                                        @if(auth()->user()->hasRole('Vendor'))
                                            @php
                                                $vendorProduct = \App\Models\VendorProduct::where('vendor_id', auth()->id())
                                                                    ->where('product_id', $product->id)
                                                                    ->first();
                                            @endphp
                                            <td>
                                                <div class="availability-toggle" data-id="{{ $product->id }}">
                                                    <span class="toggle-option available {{ ($vendorProduct && $vendorProduct->availability) ? 'active' : '' }}">
                                                        Available
                                                    </span>
                                                    <span class="toggle-option unavailable {{ (!$vendorProduct || !$vendorProduct->availability) ? 'active' : '' }}">
                                                        Unavailable
                                                    </span>

                                                    <input type="hidden" class="availability-value"
                                                        value="{{ ($vendorProduct && $vendorProduct->availability) ? 1 : 0 }}">
                                                </div>
                                            </td>
                                        @else
                                            <td>{!! check_visibility($product->is_visible) !!}</td>
                                        @endif


                                        {{-- ACTIONS --}}
                                        @canany(['Product Basic Info Edit','Product Delete'])
                                            <td>
                                                @can('Product Basic Info Edit')
                                                    <a href="{{ route('products.basic-info-edit', $product->id) }}"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                    </a>
                                                @endcan

                                                @can('Product Delete')
                                                    <form action="{{ route('products.delete', $product->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endcanany
                                    </tr>
                                @endif



                                {{-- PRODUCT WITH VARIATIONS (MULTIPLE ROWS) --}}
                                @foreach ($product->variations as $variation)
                                    @foreach ($variation->options as $option)
                                    <tr>

                                        {{-- Same S.L for all rows of same product --}}
                                        <td>{{ $loop->parent->parent->iteration }}</td>

                                        @if(!auth()->user()->hasRole('Vendor'))
                                            <td>{{ format_datetime($product->created_at) }}</td>
                                        @endif

                                        <td>
                                            @if(getProductMainImage($product->id))
                                                <img class="img-thumbnail rounded"
                                                    style="object-fit: contain;height: 100px;"
                                                    src="{{ getProductMainImage($product->id) }}" width="100" />
                                            @endif
                                        </td>

                                        <td>{{ $product->categories->pluck('name')->join(', ') ?: 'N/A' }}</td>

                                        <td>{{ $product->name }}</td>

                                        <td>
                                            {{--<strong>{{ $variation->name }}:</strong>--}} {{ $option->name }}
                                        </td>

                                        <td>₹{{ $option->price }}</td>


                                        {{-- Status / Availability --}}
                                        @if(auth()->user()->hasRole('Vendor'))
                                            @php
                                                $vendorProduct = \App\Models\VendorProduct::where('vendor_id', auth()->id())
                                                                    ->where('product_id', $product->id)
                                                                    ->first();
                                            @endphp
                                            <td>
                                                <div class="availability-toggle" data-id="{{ $product->id }}">
                                                    <span class="toggle-option available {{ ($vendorProduct && $vendorProduct->availability) ? 'active' : '' }}">
                                                        Available
                                                    </span>
                                                    <span class="toggle-option unavailable {{ (!$vendorProduct || !$vendorProduct->availability) ? 'active' : '' }}">
                                                        Unavailable
                                                    </span>

                                                    <input type="hidden" class="availability-value"
                                                        value="{{ ($vendorProduct && $vendorProduct->availability) ? 1 : 0 }}">
                                                </div>
                                            </td>
                                        @else
                                            <td>{!! check_visibility($product->is_visible) !!}</td>
                                        @endif


                                        {{-- ACTIONS --}}
                                        @canany(['Product Basic Info Edit','Product Delete'])
                                            <td>
                                                @can('Product Basic Info Edit')
                                                    <a href="{{ route('products.basic-info-edit', $product->id) }}"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                    </a>
                                                @endcan

                                                @can('Product Delete')
                                                    <form action="{{ route('products.delete', $product->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endcanany

                                    </tr>
                                    @endforeach
                                @endforeach

                            @endforeach


                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/dashboard-assets/vendors/choices/choices.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/plugins/select2/js/select2-custom.js') }}"></script>
    <script>
        let table = new DataTable("#dataTable");
    </script>

    <script>
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
            window.location.href = "{{ route('product.index') }}";
        });
    </script>

    <script>
        $(document).ready(function () {

            $(document).on("click", ".toggle-option", function () {

                let parent = $(this).closest(".availability-toggle");
                let productId = parent.data("id");

                // Switch UI state
                parent.find(".toggle-option").removeClass("active");
                $(this).addClass("active");

                let newStatus = $(this).hasClass("available") ? 1 : 0;

                // Update hidden input (if used)
                parent.find(".availability-value").val(newStatus);

                // AJAX request to update DB
                $.ajax({
                    url: "{{ route('products.updateAvailability') }}",
                    method: "POST",
                    data: {
                        id: productId,
                        is_visible: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log("Product availability updated.");
                    },
                    error: function() {
                        alert("Something went wrong!");
                    }
                });
            });

        });

    </script>

    <script>
        $(document).on("click", ".availability-toggle .toggle-option", function () {
            let parent = $(this).closest(".availability-toggle");
            let valueInput = parent.find(".availability-value");

            parent.find(".toggle-option").removeClass("active");
            $(this).addClass("active");

            if ($(this).hasClass("available")) {
                valueInput.val(1);
            } else {
                valueInput.val(0);
            }
        });

    </script>
    

@endsection
