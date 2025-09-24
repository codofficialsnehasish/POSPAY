@extends('layouts.app')
@section('title', 'Purchases')
@section('contents')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Purchases</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Purchases</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title mb-0">All Purchases</h5>
            <a href="{{ route('purchase.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="purchaseTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Invoice No</th>
                        <th>Supplier</th>
                        <th>Total Amount</th>
                        <th>Purchase Date</th>
                        <th>Products</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase->invoice_number }}</td>
                        <td>{{ $purchase->seller_name }}</td>
                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                        <td>{{ format_datetime($purchase->purchase_date) }}</td>
                        <td>
                            @foreach($purchase->items as $item)
                                <div>{{ $item->product->name ?? '' }} - {{ $item->quantity }} pcs</div>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-sm btn-success">Edit</a>
                            <button class="btn btn-sm btn-danger delete-item" data-url="{{ route('purchase.destroy', $purchase->id) }}">Delete</button>
                        </td>
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
$(document).ready(function(){
    let table = new DataTable("#purchaseTable");

    $(document).on('click', '.delete-item', function(){
        let url = $(this).data('url');
        if(confirm('Are you sure to delete this purchase?')){
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res){
                    alert(res.message);
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection
