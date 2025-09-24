@extends('layouts.app')
@section('title', 'Purchase Details')
@section('contents')

<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-3">Purchase Details - {{ $purchase->invoice_number }}</h6>

    <div class="card p-24 mb-24">
        <div><strong>Supplier:</strong> {{ $purchase->seller_name }}</div>
        <div><strong>Invoice No:</strong> {{ $purchase->invoice_number }}</div>
        <div><strong>Date:</strong> {{ format_datetime($purchase->purchase_date) }}</div>
        <div><strong>Total Amount:</strong> {{ number_format($purchase->total_amount,2) }}</div>

        <h6 class="mt-4">Products</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>MRP</th>
                    <th>Quantity</th>
                    <th>Batch No</th>
                    <th>Expiry Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '' }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->batch_number }}</td>
                    <td>{{ $item->expiry_date }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
