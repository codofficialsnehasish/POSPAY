@extends('layouts.app')

@section('title','Bulk Upload Products')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Bulk Upload Products</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Bulk Upload</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 radius-12">
                <div class="card-header bg-primary text-white py-16 px-24 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upload Excel File</h5>
                    <a href="{{ route('products.template-download') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="mdi:file-excel" class="text-success"></iconify-icon>
                        Download Template
                    </a>
                </div>
                <div class="card-body p-24">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('products.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Choose Excel File</label>
                            <input type="file" name="file" class="form-control form-control-lg" required>
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
