@extends('layouts.app')

@section('title','Products')

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

        <form action="{{ route('products.product-addons-update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            @include('admin.products.nav-tabs-edit')
                            <input type="hidden" name="product_id" value="{{ request()->segment(4) }}">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="basicinfo" role="tabpanel">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-neutral-900">Choose Addons Products </label>
                                            <select name="addons[]" class="form-select select2 select2-multiple" multiple="multiple" multiple data-placeholder="Choose ...">
                                                @foreach($all_products as $all_product)
                                                <option value="{{ $all_product->id }}" @if(in_array($all_product->id, $product->addons->pluck('addons_id')->toArray())) selected @endif>{{ $all_product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-neutral-900">Choose Complementary Products </label>
                                            <select name="complamentary[]" class="form-select select2 select2-multiple" multiple="multiple" multiple data-placeholder="Choose ...">
                                                @foreach($all_products as $all_product)
                                                <option value="{{ $all_product->id }}" @if(in_array($all_product->id, $product->complamentary->pluck('complamentary_id')->toArray())) selected @endif>{{ $all_product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header bg-primary text-light">
                            Publish
                        </div>
                        <div class="card-body">
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