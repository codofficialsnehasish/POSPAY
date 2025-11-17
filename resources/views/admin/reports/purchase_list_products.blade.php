@extends('layouts.app')

@section('title','Purchase List')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Purchase Products</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Purchase Products</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">Purchase Products</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Date</th>
                        <th>Seller</th>
                        <th>Invoice #</th>
                        <th>Product</th>
                        <th>MRP</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Line Total</th>
                        {{-- <th>Invoice Total</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @php $sl = 1; @endphp

                    @foreach($purchases as $purchase)
                        @foreach($purchase->items as $item)

                            @php
                                $optionName = $item->variation->name ?? '';
                                $lineTotal = $item->quantity * $item->price;
                                $discountAmount = $item->discount ?? 0;
                                $cgst = $item->cgst_amount ?? 0;
                                $sgst = $item->sgst_amount ?? 0;
                                $productImage = getProductMainImage($item->product->id);

                                $finalLineTotal = $lineTotal - $discountAmount + $cgst + $sgst;
                            @endphp

                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ format_date($purchase->purchase_date) }}</td>

                                <td>{{ $purchase->seller->seller_name ?? '' }}</td>

                                <td>{{ $purchase->invoice_number }}</td>
                                <td>
                                    {{ $item->product->name ?? '' }}
                                    @if($optionName) - {{ $optionName }} @endif
                                </td>

                                <td>₹ {{ number_format($item->price,2) }}</td>

                                <td>{{ $item->quantity }}</td>

                                <td>₹ {{ number_format($discountAmount,2) }}</td>

                                <td>{{ $item->batch_number }}</td>

                                <td>{{ $item->expiry_date }}</td>

                                <td>₹ {{ number_format($finalLineTotal,2) }}</td>


                                {{-- <td>₹ {{ number_format($purchase->total_amount,2) }}</td> --}}
                            </tr>

                        @endforeach
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
