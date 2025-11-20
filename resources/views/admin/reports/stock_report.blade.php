@extends('layouts.app')

@section('title','Stock Report')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Current Stock Report</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Stock Report</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        {{-- <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">Current Stock</h5>
        </div> --}}
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Product</th>
                        <th>Variation</th>
                        <th>Sale Price</th>
                        <th>Current Qty</th>
                        <th>Total Stock Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        {{-- Product Name --}}
                        <td>{{ $stock->product_name ?? 'N/A' }}</td>

                        {{-- Variation / Option Name --}}
                        <td>{{ $stock->option_name ?? 'N/A' }}</td>

                        {{-- Selling Price --}}
                        <td>{{ number_format($stock->price, 2) }}</td>

                        {{-- Current Stock Qty --}}
                        <td>{{ $stock->stock }}</td>

                        {{-- Total Amount --}}
                        <td>{{ number_format($stock->price * $stock->stock, 2) }}</td>
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
