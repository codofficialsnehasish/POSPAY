@extends('layouts.app')

@section('title','Expiry List')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Expiry List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Expiry List</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">Expiring Products</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Product</th>
                        <th>Variation</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiryItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->variationOption->name ?? 'N/A' }}</td>
                        <td>{{ $item->batch_number ?? 'N/A' }}</td>
                        <td>{{ $item->closing_balance ?? $item->quantity_in ?? 0 }}</td>
                        <td>{{ $item->expiry_date ? format_datetime($item->expiry_date) : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let table = new DataTable("#dataTable");
</script>
@endsection
