@extends('layouts.app')

@section('title','Order Details')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Order Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Order Details</li>
        </ul>
    </div>

    {{-- Order Information --}}
    <div class="card mb-4">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">Order #{{ $order->order_number }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Order ID:</div>
                <div class="col-md-9">{{ $order->id }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Contact Number:</div>
                <div class="col-md-9">{{ $order->contact_number }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Order Status:</div>
                <div class="col-md-9">{{ $order->order_status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Payment Status:</div>
                <div class="col-md-9">{{ $order->payment_status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Payment Method:</div>
                <div class="col-md-9">{{ $order->payment_method ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Created At:</div>
                <div class="col-md-9">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Total Amount:</div>
                <div class="col-md-9">{{ number_format($order->total_amount,2) }}</div>
            </div>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">Order Items</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if(!empty($item->image_url))
                                <img src="{{ $item->image_url }}" alt="Product Image" width="50" height="50">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="50" height="50">
                            @endif
                        </td>
                        <td>{{ $item->product_name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price,2) }}</td>
                        <td>{{ number_format($item->price * $item->quantity,2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No items in this order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Back button --}}
    <div class="mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

</div>
@endsection

@section('script')
<script>
    let table = new DataTable("#dataTable");
</script>
@endsection
