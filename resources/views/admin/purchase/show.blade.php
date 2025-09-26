@extends('layouts.app')
@section('title', 'Purchase Details')
@section('contents')

<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-3">Purchase Details - {{ $purchase->invoice_number }}</h6>

    <div class="card p-24 mb-24">
        <div><strong>Supplier:</strong> {{ $purchase->seller->seller_name ?? '' }}</div>
        <div><strong>Invoice No:</strong> {{ $purchase->invoice_number }}</div>
        <div><strong>Date:</strong> {{ format_datetime($purchase->purchase_date) }}</div>
        <div><strong>Total Amount:</strong> ₹ {{ number_format($purchase->total_amount, 2) }}</div>
    </div>

    <div class="card p-24 mb-24">
        <h6 class="mb-3">Products</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>MRP</th>
                    <th>Quantity</th>
                    <th>Discount</th>
                    <th>Batch No</th>
                    <th>Expiry Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalItems = 0;
                    $totalMRP = 0;
                    $totalDiscount = 0;
                    $totalGST = 0;
                @endphp

                @foreach($purchase->items as $item)
                    @php
                        $optionName = $item->variation->name ?? '';
                        $lineTotal = $item->quantity * $item->price;
                        $discountAmount = $item->discount ?? 0;
                        $cgst = $item->cgst_amount ?? 0;
                        $sgst = $item->sgst_amount ?? 0;

                        $totalItems += $item->quantity;
                        $totalMRP += $lineTotal;
                        $totalDiscount += $discountAmount;
                        $totalGST += ($cgst + $sgst);

                        $productImage = getProductMainImage($item->product->id);
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ $productImage }}" alt="{{ $item->product->name ?? '' }}" style="width:50px; height:50px; object-fit:cover;">
                        </td>
                        <td>{{ $item->product->name ?? '' }} @if($optionName) - {{ $optionName }} @endif</td>
                        <td>₹ {{ number_format($item->price,2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹ {{ number_format($discountAmount,2) }}</td>
                        <td>{{ $item->batch_number }}</td>
                        <td>{{ $item->expiry_date }}</td>
                        <td>₹ {{ number_format($lineTotal - $discountAmount + $cgst + $sgst, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <h6>Order Summary</h6>
            <table class="table table-bordered w-50">
                <tr>
                    <th>Total Items</th>
                    <td>{{ $totalItems }}</td>
                </tr>
                <tr>
                    <th>Total MRP</th>
                    <td>₹ {{ number_format($totalMRP, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Discount</th>
                    <td>₹ {{ number_format($totalDiscount, 2) }}</td>
                </tr>
                <tr>
                    <th>Total GST</th>
                    <td>₹ {{ number_format($totalGST, 2) }}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td>₹ {{ number_format($totalMRP - $totalDiscount + $totalGST, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection
