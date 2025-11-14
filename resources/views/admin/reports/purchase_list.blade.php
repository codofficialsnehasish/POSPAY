@extends('layouts.app')

@section('title','Purchase List')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Purchase List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Purchase List</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">All Purchases</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Date</th>
                        <th>Seller</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Invoice #</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ format_date($purchase->purchase_date) }}</td>
                        <td>{{ $purchase->seller->seller_name }}</td>
                        <td>
                            @foreach($purchase->items as $item)
                                {{ $item->product->name ?? '' }}
                                @if($item->variation)
                                    - {{ $item->variation->name }}
                                @endif
                                <br>
                            @endforeach
                        </td>
                        <td>{{ $purchase->invoice_number }}</td>
                        <td>{{ number_format($purchase->total_amount,2) }}</td>
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
