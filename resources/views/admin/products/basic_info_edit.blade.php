@extends('layouts.app')

@section('title', 'Products')

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

        <form action="{{ route('products.add-basic-edit-info') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            @include('admin.products.nav-tabs-edit')
                            <input type="hidden" name="product_id" value="{{ request()->segment(4) }}">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="basicinfo" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Name</label>
                                                <div>
                                                    <input data-parsley-type="text" value="{{ $product->name }}" type="text"
                                                        class="form-control" required placeholder="Enter Product Title"
                                                        name="product_name">
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Hsn Code</label>
                                                <select name="hsncode_id" id="hsn_code"
                                                    class="form-select select2 select2-single" data-placeholder="Choose ...">
                                                    <option value="" disabled selected>
                                                        choose</option>
                                                    @foreach ($hsn_codes as $hsn_code)
                                                        <option value="{{ $hsn_code->id }}"
                                                            data-gst_percentage="{{ $hsn_code->gst_rate }}"
                                                            {{ $product->hsncode_id == $hsn_code->id ? 'selected' : '' }}>
                                                            {{ $hsn_code->hsncode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Sort Description</label>
                                            <textarea class="editor" name="sort_description" id="">{{ $product->sort_description }}</textarea>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Long Description</label>
                                            <textarea class="editor" name="long_description" id="">{{ $product->long_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-light">
                            <div class="d-flex flex-wrap">
                                <span class="me-2">Category</span>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- <div class="category-tree" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                @if (!empty($categorys))
                                    @foreach ($categorys as $category)
                                       
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ isset($selectedCategories) && in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category{{ $category->id }}"> {{ $category->name }} </label>
                                        </div>
                                        @include('admin.products.subcategory', [
                                            'subcategories' => $category->subcategory,
                                            'parent_id' => $category->id,
                                            'margin' => 20,
                                            'selectedCategories' => isset($selectedCategories) ? $selectedCategories : [],
                                        ])
                                    @endforeach
                                @endif
                            </div> --}}

                            <div class="category-tree"
                                style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                @if (!empty($categories))
                                    <ul class="list-unstyled">
                                        @foreach ($categories as $category)
                                            @if ($category->level == 0)
                                                <!-- Top-level categories -->
                                                <li>
                                                    <div class="d-flex align-items-center">
                                                        {{-- @if ($category->hasChildren()) --}}
                                                        <button type="button"
                                                            class="btn btn-link btn-sm p-0 me-2 toggle-subcategories"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseCategory{{ $category->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapseCategory{{ $category->id }}">
                                                            <iconify-icon icon="lucide:plus"></iconify-icon>
                                                        </button>
                                                        {{-- @endif --}}
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input radius-4 border border-neutral-400 category-checkbox"
                                                                type="checkbox" name="categories[]"
                                                                value="{{ $category->id }}" id="category{{ $category->id }}"
                                                                {{ isset($selectedCategories) && in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="category{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- Subcategories -->
                                                    {{-- @if ($category->hasChildren()) --}}
                                                    <div id="collapseCategory{{ $category->id }}" class="collapse ms-4">
                                                        @include('admin.products.subcategory', [
                                                            'subcategories' => $categories,
                                                            'parent_id' => $category->id,
                                                            'selectedCategories' => isset($selectedCategories)
                                                                ? $selectedCategories
                                                                : [],
                                                        ])
                                                    </div>
                                                    {{-- @endif --}}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-primary text-light">
                            <div class="d-flex flex-wrap">
                                <span class="me-2">Brand</span>
                            </div>
                        </div>
                        <div class="card-body">
                    
                            {{-- <label for="parent_id" class="form-label">Brand</label> --}}
                            <select class="form-select select2" id="brand_id" name="brand_id">
                                <option selected disabled value="">Choose...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{$brand->id == $product->brand_id ? "selected" :''}}>{{ $brand->name }}</option>
                                    
                                @endforeach
                            </select>

                        
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-primary text-light">
                            Publish
                        </div>
                        <div class="card-body">
                            {{-- <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Product Type</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="product_type1" name="product_type"
                                        class="form-check-input" value="simple"
                                        {{ check_uncheck($product->product_type, 'simple') }}>
                                    <label class="form-check-label" for="product_type1">Simple</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="product_type2" name="product_type"
                                        class="form-check-input" value="attribute"
                                        {{ check_uncheck($product->product_type, 'attribute') }}>
                                    <label class="form-check-label" for="product_type2">Attribute</label>
                                </div>
                            </div> --}}
                            <!-- Barcode input (hidden by default if attribute is selected) -->
                            {{-- <div class="mb-3" id="barcodeField">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" id="barcode" name="barcode" class="form-control" value="{{ $product->barcode }}" placeholder="Enter barcode">
                            </div>
                            <div class="mb-3" id="measureField">
                                <label for="measure" class="form-label">Measure</label>
                                <input type="text" id="measure" name="measure" class="form-control" value="{{ $product->measure }}" placeholder="Enter measure">
                            </div> --}}
                            {{-- <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Veg Or Non Veg</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="veg" name="veg_non_veg" class="form-check-input"
                                        value="1" {{ check_uncheck($product->veg, 1) }}>
                                    <label class="form-check-label" for="veg">Veg</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="nonveg" name="veg_non_veg" class="form-check-input"
                                        value="0" {{ check_uncheck($product->veg, 0) }}>
                                    <label class="form-check-label" for="nonveg">Non Veg</label>
                                </div>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Product Availability</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="avaliable" name="is_available" class="form-check-input"
                                        value="1" {{ check_uncheck($product->is_available, 1) }}>
                                    <label class="form-check-label" for="avaliable">Avaliable</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="notavaliable" name="is_available" class="form-check-input"
                                        value="0" {{ check_uncheck($product->is_available, 0) }}>
                                    <label class="form-check-label" for="notavaliable">Not Avaliable</label>
                                </div>
                            </div>
                            {{-- <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Special Product</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="special" name="is_special" class="form-check-input"
                                        value="1" {{ check_uncheck($product->is_special, 1) }}>
                                    <label class="form-check-label" for="special">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="notspecial" name="is_special" class="form-check-input"
                                        value="0" {{ check_uncheck($product->is_special, 0) }}>
                                    <label class="form-check-label" for="notspecial">No</label>
                                </div>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">GST</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_gst_included1" name="is_gst_included"
                                        class="form-check-input" value="1" {{ check_uncheck($product->is_gst_included, 1) }}>
                                    <label class="form-check-label" for="is_gst_included1">Included</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_gst_included2" name="is_gst_included"
                                        class="form-check-input" value="0" {{ check_uncheck($product->is_gst_included, 0) }}>
                                    <label class="form-check-label" for="is_gst_included2">Excluded</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Visiblity</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="show" name="is_visible" class="form-check-input"
                                        value="1" {{ check_uncheck($product->is_visible, 1) }}>
                                    <label class="form-check-label" for="show">Show</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="hide" name="is_visible" class="form-check-input"
                                        value="0" {{ check_uncheck($product->is_visible, 0) }}>
                                    <label class="form-check-label" for="hide">Hide</label>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                        Save & Next
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    
    <script>
        // document.addEventListener("DOMContentLoaded", function () {
        //     const productTypeRadios = document.querySelectorAll('input[name="product_type"]');
        //     const barcodeField = document.getElementById("barcodeField");
        //     const measureField = document.getElementById("measureField");
            
    
        //     function toggleBarcode() {
        //         const selectedValue = document.querySelector('input[name="product_type"]:checked').value;
        //         if (selectedValue === "simple") {
        //             barcodeField.style.display = "block"; // show
        //             measureField.style.display = "block"; // show
        //         } else {
        //             barcodeField.style.display = "none"; // hide
        //             measureField.style.display = "none"; // hide
        //         }
        //     }
    
        //     // Initial check
        //     toggleBarcode();
    
        //     // Listen for change
        //     productTypeRadios.forEach(radio => {
        //         radio.addEventListener("change", toggleBarcode);
        //     });
        // });
    </script>

@endsection