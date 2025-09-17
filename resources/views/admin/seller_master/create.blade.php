@extends('layouts.app')

@section('title','Create Seller')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Seller</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Create Seller</li>
        </ul>
    </div>

    <form action="{{ route('sellers.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Seller Details</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Seller Name</label>
                            <input type="text" name="seller_name" value="{{ old('seller_name')}}" class="form-control" placeholder="Enter Seller Name" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email')}}" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone')}}" class="form-control" placeholder="Enter Phone Number">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Enter Address">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" value="{{ old('city')}}" class="form-control" placeholder="Enter City">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" value="{{ old('state')}}" class="form-control" placeholder="Enter State">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" value="{{ old('country')}}" class="form-control" placeholder="Enter Country">
                        </div>
                        <div class="col-12">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_number" value="{{ old('gst_number')}}" class="form-control" placeholder="Enter GST Number">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-0 mb-3">
                <div class="card-header">Publish</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label mb-3 d-flex">Status</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="status_active" name="status" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="status_inactive" name="status" class="form-check-input" value="0">
                            <label class="form-check-label" for="status_inactive">Inactive</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

</div>
@endsection
