@extends('layouts.app')

@section('title','Transaction Details')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Transaction Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Transaction Details</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title mb-0">All Transactions of Selected Date</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Date & Time</th>
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr class="clickable-row" data-href="{{ route('transaction.get-order-by-id',$row['order_id']) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row['date_time'] }}</td>
                        <td>{{ $row['bill_no'] }}</td>
                        <td>{{ number_format($row['amount'],2) }}</td>
                        <td>{{ $row['mode'] }}</td>
                        <td>{{ $row['transaction_dtls'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No transactions found for this date.</td>
                    </tr>
                    @endforelse
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
<script>
    // Make row clickable
    document.querySelectorAll(".clickable-row").forEach(function(row) {
        row.addEventListener("click", function() {
            window.location = this.dataset.href;
        });
    });
</script>
@endsection
