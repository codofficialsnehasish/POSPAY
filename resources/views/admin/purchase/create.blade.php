@extends('layouts.app')
@section('title', 'Add Purchase')
@section('css')
<style>
    .suggestion-box {
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        max-height: 250px;
        /* overflow-y: auto; */
        /* width: 50%; */
        z-index: 9999;
        border-radius: 4px;
    }

    .suggestion-box div {
        padding: 8px 12px;
        cursor: pointer;
    }

    .suggestion-box div:hover {
        background-color: #f0f0f0;
    }
</style>
@endsection
@section('contents')
<div class="dashboard-main-body">
    <h6 class="fw-semibold mb-3">Add Purchase</h6>

    
    <div class="card p-24 mb-24">
        <form id="purchaseForm" method="POST" action="{{ route('purchase.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <div class="mb-3">
                        <input type="search" id="searchProduct" class="form-control" placeholder="Enter product name or barcode for Search">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" style="float: right;">Save Items</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="productResults" class="suggestion-box" style="display: none;"></div>
            </div>
            
            <div class="col-md-12">
                {{-- <h6>Selected Products</h6> --}}
                <table class="table table-bordered" id="selectedProductsTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>MRP</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="selectedProducts"></tbody>
                </table>
            </div>

            
        </form>

        <!-- Product Modal -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="productForm">
                        <div class="modal-header">
                            <span class="modal-title" id="productModalTitle">Add Product</span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="option_id" id="modal_option_id">

                            <div class="mb-3">
                                <label for="modal_product_name">Product Name</label>
                                <input type="text" class="form-control" id="modal_product_name" readonly>
                                <span id="modal_product_stock" style="float: right;"></span>
                            </div>
                            <div class="mb-3">
                                <label for="modal_mrp">MRP</label>
                                <input type="number" class="form-control" id="modal_mrp" name="mrp" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal_quantity">Quantity</label>
                                <input type="number" class="form-control" id="modal_quantity" name="quantity" required>
                                <span id="modal_order_amount" style="float: right;"></span>
                            </div>

                            <div class="mb-3">
                                <label for="modal_discount">Discount</label>
                                <input type="number" class="form-control" id="modal_discount" name="discount">
                                <span id="modal_discount_persentage" style="float: right;"></span>
                            </div>

                            <div class="mb-3">
                                <label for="modal_batch_no">Batch No</label>
                                <input type="text" class="form-control" id="modal_batch_no" name="batch_no">
                            </div>

                            <div class="mb-3">
                                <label for="modal_expiry_date">Expiry Date</label>
                                <input type="date" class="form-control" id="modal_expiry_date" name="expiry_date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add to List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Modal -->
        <div class="modal fade" id="savePruchase" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="productForm">
                        <div class="modal-header">
                            <span class="modal-title" id="productModalTitle">Save Purchase</span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Supplier</label>
                                <select name="seller_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}">{{ $seller->seller_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="">
                                <label>Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Purchase</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {

    $('#purchaseForm').on('submit', function (e) {
        e.preventDefault(); // 🚫 stops form from submitting/reloading
        // your code to handle the barcode data here
        var savePruchaseModal = new bootstrap.Modal(document.getElementById('savePruchase'));
        savePruchaseModal.show();
    });

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Trigger search on typing
    $('#searchProduct').on('keyup', function() {
        let query = $(this).val();
        if(query.length < 2) { $('#productResults').html(''); return; }

        $.ajax({
            url: "{{ route('purchases.search-products') }}",
            method: 'GET',
            data: { search: query },
            dataType: 'json',
            success: function(res){
                if(res.status) {
                    let html = '';
                    if(res.data.length === 0) html = '<div>No products found</div>';
                    else {
                        res.data.forEach(prod => {
                            if(prod.stock <= 0){
                                html += `<div class="suggestion-item bg-danger text-white" data-prod='${JSON.stringify(prod)}'>
                                            ${prod.product_name} - ( Stock: ${prod.stock} )
                                        </div>`;
                            }else{
                                html += `<div class="suggestion-item" data-prod='${JSON.stringify(prod)}'>
                                            ${prod.product_name} - ( Stock: ${prod.stock} )
                                        </div>`;
                            }
                        });
                    }
                    $('#productResults').html(html).show();

                    // 🔥 if only one product, auto trigger its click
                    if (res.data.length === 1) {
                        // wait for DOM to render first
                        setTimeout(() => {
                            $('#productResults .suggestion-item').first().trigger('click');
                        }, 100);
                    }
                }
            },
            error: function(err) {
                console.error(err);
                $('#productResults').html('<div class="text-danger">Error fetching products</div>');
            }
        });
    });


    // Add product to selected list
    $(document).on('click', '.suggestion-item', function() {
        let prod = $(this).data('prod');

        // Fill modal hidden fields and inputs
        $('#modal_option_id').val(prod.variation_option_id);
        $('#productForm')[0].reset(); // reset other inputs

        // Set modal title with product name + stock
        // $('#productModalTitle').text(`${prod.product_name} - Stock: ${prod.stock}`);
        $('#modal_mrp').val(prod.product_price);
        $('#modal_product_name').val(prod.product_name);
        $('#modal_product_stock').text(`Q/A: ${prod.stock}`);

        $('#productResults').hide();
        // Show modal
        var productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    });

    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        let data = $(this).serializeArray();
        let prodData = {};
        data.forEach(item => prodData[item.name] = item.value);

        let productName = $('#modal_product_name').val();

        let html = `
            <tr class="product-row">
                <td>
                    <input type="hidden" name="products[][option_id]" value="${prodData.option_id}">
                    ${productName}
                </td>
                <td class="mrp-cell" data-value="${prodData.mrp}">${prodData.mrp}</td>
                <td class="qty-cell" data-value="${prodData.quantity}">${prodData.quantity}</td>
                <td class="disc-cell" data-value="${prodData.discount}">${prodData.discount}</td>
                <td class="batch-cell" data-value="${prodData.batch_no}">${prodData.batch_no}</td>
                <td class="expiry-cell" data-value="${prodData.expiry_date}">${prodData.expiry_date}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary edit-product">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger remove-product">Remove</button>
                </td>
            </tr>
            `;

        $('#selectedProducts').append(html);

        var productModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        productModal.hide();
    });


    // Remove selected product
    $(document).on('click', '.remove-product', function() {
        $(this).closest('.selected-product').remove();
    });
});

</script>
@endsection
