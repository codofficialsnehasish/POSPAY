@extends('layouts.app')

@section('title', 'Stock Transactions')

@section('contents')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Stock Transactions</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Stock Transactions</li>
            </ul>
        </div>

        <div class="card basic-data-table">
            <div
                class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <h5 class="card-title mb-0">All Stock Transactions</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>S.L</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Variation</th>
                            <th>Batch No</th>
                            <th>Type</th>
                            <th>Qty In</th>
                            <th>Qty Out</th>
                            <th>Opening Balance</th>
                            <th>Closing Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockTransactions as $tx)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tx->transaction_date }}</td>
                                <td class="text-wrap">{{ $tx->product->name ?? 'N/A' }}</td>
                                <td class="text-wrap">{{ $tx->variationOption->name ?? 'N/A' }}</td>
                                <td class="text-wrap">{{ $tx->batch_number ?? '-' }}</td>
                                <td>{{ ucfirst($tx->transaction_type) }}</td>
                                <td>{{ $tx->quantity_in }}</td>
                                <td>{{ $tx->quantity_out }}</td>
                                <td>{{ $tx->opening_balance }}</td>
                                <td>{{ $tx->closing_balance }}</td>
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
