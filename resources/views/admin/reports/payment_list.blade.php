@extends('layouts.app')

@section('title','Payment List')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Payment List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Payment List</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">All Payments</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Transaction #</th>
                        <th>User</th>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Paid At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->gateway_transaction_id }}</td>
                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                        <td>{{ number_format($payment->amount,2) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->payment_status }}</td>
                        <td>{{ $payment->paid_at ? format_datetime($payment->paid_at) : 'Pending' }}</td>
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
