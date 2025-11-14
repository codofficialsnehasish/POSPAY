@extends('layouts.app')

@section('title','Sale Report')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Sale Report</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Sale Report</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        {{-- <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title mb-0">All Sales</h5>
        </div> --}}
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th class="text-wrap">S.L</th>
                        <th class="text-wrap">Date</th>
                        <th class="text-wrap">User</th>
                        <th class="text-wrap">Order #</th>
                        <th class="text-wrap">Total Amount</th>
                        <th class="text-wrap">Order Status</th>
                        <th class="text-wrap">Payment Status</th>
                        <th class="text-wrap">Payment Mode</th>
                        <th class="text-wrap">Transaction No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="text-wrap">{{ $loop->iteration }}</td>
                        <td class="text-wrap">{{ format_datetime_excel($order->created_at) }}</td>
                        <td class="text-wrap">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="text-wrap">{{ $order->order_number }}</td>
                        <td class="text-wrap">{{ number_format($order->total_amount,2) }}</td>
                        <td class="text-wrap">{{ $order->order_status }}</td>
                        <td class="text-wrap">{{ $order->payment_status }}</td>
                        <td class="text-wrap">{{ $order->payment_method }}</td>
                        <td class="text-wrap">{{ optional($order->transactions)->gateway_transaction_id ?? '-' }}</td>
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
