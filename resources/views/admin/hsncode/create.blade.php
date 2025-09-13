@extends('layouts.app')

@section('title','Hsncode Create')

@section('contents')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Hsncode</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Create Hsncode</li>
        </ul>
    </div>

    <form action="{{ route('hsncode.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hsncode Details</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Hsncode</label>
                            <input type="text" name="hsncode" value="{{ old('hsncode')}}" id="hsncode" class="form-control" placeholder="Enter Hsncode">
                        </div>
                 
                        <div class="col-12">
                            <label class="form-label">GST Rate</label>
                            <input type="text" name="gst_rate" value="{{ old('gst_rate')}}" id="gst_rate" class="form-control" placeholder="Enter GST Rate"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">

                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
       
            <div class="card">
                <div class="card-header">Publish</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label mb-3 d-flex">Visibility</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline1" name="is_visible" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="customRadioInline1">Visible</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline2" name="is_visible" class="form-check-input" value="0">
                            <label class="form-check-label" for="customRadioInline2">Invisible</label>
                        </div>
                    </div>
                    <div class="md-3">
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

@section('script')
<script>
    
    // ================================================ Upload image & show it\'s name js end ================================================
</script>
@endsection