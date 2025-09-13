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


        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        @include('admin.products.nav-tabs-edit')

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active p-3" id="basicinfo" role="tabpanel">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="table-responsive scroll-sm">
                                            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                                                <thead>
                                                    <tr>
                                                        <th>Sl.no</th>
                                                        <th>Name</th>
                                                        {{-- <th>Type</th> --}}
                                                        <th></th>
                                                        <th>Visible</th>
                                                        {{-- @canany(['Permission Edit', 'Permission Delete']) --}}
                                                            <th>Action</th>
                                                        {{-- @endcanany --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($variations as $variation)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $variation->name }}</td>
                                                            {{-- <td>{{ Str::ucfirst($variation->variation_type) }} --}}
                                                            
                                                            </td>
                                                            <td style="display: flex">
                                                                {{-- @can('Permission Edit') --}}
                                                          

                                                                <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 px-24 py-4 rounded-pill fw-medium text-sm d-flex justify-content-center align-items-center"
                                                                    style="margin-right: 10px;"  onclick="add_product_variation_option({{$variation->id}})">
                                                                    Add <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1 mx-1"></iconify-icon>
                                                                </button>

                                                               
                                                            {{-- @endcan --}}
                                                            {{-- @can('Permission Delete') --}}
                                                                <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 px-24 py-4 rounded-pill fw-medium text-sm d-flex justify-content-center align-items-center"
                                                                    
                                                                    
                                                                    style="margin-right: 10px;" onclick="view_product_variation_option({{$variation->id}})">

                                                                    View <iconify-icon icon="mdi:eye" class="icon text-xl line-height-1 mx-1"></iconify-icon>
                                                                </button>
                                                            {{-- @endcan --}}
                                                            </td>
                                                            <td>{!! check_visibility($variation->is_visible) !!}</td>


                                                            {{-- @canany(['Permission Edit', 'Permission Delete']) --}}
                                                                <td class="d-flex">
                                                                    {{-- @can('Permission Edit') --}}
                                                                        <button type="button"
                                                                            class="edit-variation-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editVariationModal"
                                                                            data-id="{{ $variation->id }}"
                                                                            style="margin-right: 10px;">
                                                                            <iconify-icon icon="lucide:edit"
                                                                                class="menu-icon"></iconify-icon>
                                                                        </button>
                                                                    {{-- @endcan --}}
                                                                    {{-- @can('Permission Delete') --}}
                                                                        {{-- <a class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                                            href="javascript:void(0);" onclick="deleteItem(this)"
                                                                            data-url="{{ route('products.delete-variation',$variation->id) }}"
                                                                            data-item="Variation" alt="delete"> <iconify-icon
                                                                                icon="fluent:delete-24-regular"
                                                                                class="menu-icon"></iconify-icon></a> --}}
                                                                    {{-- @endcan --}}
                                                                </td>
                                                            {{-- @endcanany --}}

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- <button type="button"
                                            class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#addVariationModal">
                                            <iconify-icon icon="ic:baseline-plus"
                                                class="icon text-xl line-height-1"></iconify-icon>
                                            Add Variation
                                        </button> --}}
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
                            <form action="{{ route('products.variation-edit-process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ request()->segment(4) }}">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    Save & Next
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Cancel
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    @include('admin.products.variations._form_variations')

@endsection

@section('script')

    @include('admin.products.product_script')

@endsection
