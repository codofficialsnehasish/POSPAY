@extends('layouts.app')
@section('title', 'Add Purchase')
@section('css')
<link href="{{ asset('assets/dashboard-assets/vendors/choices/choices.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
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

            <div id="orderSummary" class="card mt-3 p-3 border-primary">
                <h5 class="card-title">Order Summary</h5>
                <div class="d-flex justify-content-between">
                    <span>Total Quantity:</span> <span id="totalQty">0</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Gross Amount:</span> <span id="totalGross">₹ 0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Discount:</span> <span id="totalDiscount">₹ 0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>CGST:</span> <span id="totalCgst">₹ 0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>SGST:</span> <span id="totalSgst">₹ 0.00</span>
                </div>
                <div class="d-flex justify-content-between fw-bold mt-2">
                    <span>Grand Total:</span> <span id="grandTotal">₹ 0.00</span>
                </div>
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
                            <input type="hidden" id="modal_edit_index">

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

        <!-- Save Pruchase Modal -->
        <div class="modal fade" id="savePruchase" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="savePruchaseForm">
                        <div class="modal-header">
                            <span class="modal-title" id="savePruchaseModalTitle">Save Purchase</span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Supplier</label>
                                <select name="seller_id" class="form-select select2" id="seller_id" required>
                                    <option value="" selected disabled>Select Supplier</option>
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
<script src="{{ asset('assets/dashboard-assets/vendors/choices/choices.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/dashboard-assets/plugins/select2/js/select2-custom.js') }}"></script>
<script>
    $('.select2').select2({
        dropdownParent: $('#savePruchase'),
        width: '100%'
    });
</script>
<script>
$(document).ready(function() {

    $('#purchaseForm').on('submit', function (e) {
        e.preventDefault(); // stop form submission

        let productCount = $('#selectedProducts tr').length;

        if(productCount === 0){
            showToast('error', 'Error', 'Please add at least one product before proceeding.');
            return; // stop further execution
        }

        // show modal
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

        $('#modal_order_amount').text('');
        $('#modal_discount_persentage').text('');
        $('#searchProduct').val('');
        // Show modal
        var productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    });

    function updateOrderDetails() {
        let modal_mrp = parseFloat($('#modal_mrp').val()) || 0;   // MRP per unit
        let qty = parseFloat($('#modal_quantity').val()) || 0;    // quantity
        let discount = parseFloat($('#modal_discount').val()) || 0; // discount amount

        // Total MRP
        let total_mrp = modal_mrp * qty;

        // Show total order amount (after discount)
        let final_amount = total_mrp - discount;

        $('#modal_order_amount').text(
            `Total Order Amount: ₹ ${final_amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
        );

        // Calculate discount percentage
        let discount_percentage = 0;
        if (total_mrp > 0) {
            discount_percentage = (discount / total_mrp) * 100;
        }

        // Show the percentage with 2 decimals
        $('#modal_discount_persentage').text(`Discount Percentage: ${discount_percentage.toFixed(2)} %`);
    }


    // Call the function whenever any input changes
    $('#modal_mrp, #modal_quantity, #modal_discount').on('keyup change', updateOrderDetails);

    // Call once initially (optional)
    updateOrderDetails();




    $('#productForm').on('submit', function (e) {
        e.preventDefault();

        let data = $(this).serializeArray();
        let prodData = {};
        data.forEach(item => prodData[item.name] = item.value);

        let productName = $('#modal_product_name').val();
        let editIndex = $('#modal_edit_index').val();

        // -----------------------
        // EDIT MODE → same as before
        if (editIndex !== '') {
            let row = $('#selectedProducts tr').eq(editIndex);

            row.find('td:eq(0)').html(`
                <input type="hidden" name="products[][option_id]" value="${prodData.option_id}">
                ${productName}
            `);
            row.find('.mrp-cell').data('value', prodData.mrp).text(prodData.mrp);
            row.find('.qty-cell').data('value', prodData.quantity).text(prodData.quantity);
            row.find('.disc-cell').data('value', prodData.discount).text(prodData.discount);
            row.find('.batch-cell').data('value', prodData.batch_no).text(prodData.batch_no);
            row.find('.expiry-cell').data('value', prodData.expiry_date).text(prodData.expiry_date);

            // row.prependTo('#selectedProducts'); // move edited row to top

        } else {
            // -----------------------
            // ADD MODE → check for existing row with same option_id
            let existingRow = $(`#selectedProducts input[name="products[][option_id]"][value="${prodData.option_id}"]`).closest('tr');

            if (existingRow.length > 0) {
                // ✅ FULL MERGE → update all fields
                existingRow.find('td:eq(0)').html(`
                    <input type="hidden" name="products[][option_id]" value="${prodData.option_id}">
                    ${productName}
                `);
                existingRow.find('.mrp-cell').data('value', prodData.mrp).text(prodData.mrp);
                existingRow.find('.qty-cell').data('value', prodData.quantity).text(prodData.quantity);
                existingRow.find('.disc-cell').data('value', prodData.discount).text(prodData.discount);
                existingRow.find('.batch-cell').data('value', prodData.batch_no).text(prodData.batch_no);
                existingRow.find('.expiry-cell').data('value', prodData.expiry_date).text(prodData.expiry_date);

                existingRow.prependTo('#selectedProducts'); // move merged row to top

            } else {
                // ✅ Add new row at top
                let html = `
                    <tr class="product-row">
                        <td>
                            <input type="hidden" name="products[][option_id]" value="${prodData.option_id}">
                            ${productName}
                        </td>
                        <td class="mrp-cell" 
                            data-value="${prodData.mrp}" 
                            data-cgst="${prodData.cgst_amount}" 
                            data-sgst="${prodData.sgst_amount}" 
                            data-gst-included="${prodData.is_gst_included}">
                            ${prodData.mrp}
                        </td>
                        <td class="qty-cell" data-value="${prodData.quantity}">${prodData.quantity}</td>
                        <td class="disc-cell" data-value="${prodData.discount}">${prodData.discount}</td>
                        <td class="batch-cell" data-value="${prodData.batch_no}">${prodData.batch_no}</td>
                        <td class="expiry-cell" data-value="${prodData.expiry_date}">${prodData.expiry_date}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-product">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger remove-product">
                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                            </button>
                        </td>
                    </tr>`;

                $('#selectedProducts').prepend(html);
            }
        }

        // -----------------------
        // Reset modal
        $('#modal_edit_index').val('');
        $('#productForm')[0].reset();
        $('#productModalTitle').text('Add Product');
        $('#productForm button[type=submit]').text('Add to List');

        var productModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        productModal.hide();
        updateOrderSummary();
    });




    $(document).on('click', '.edit-product', function () {
        let row = $(this).closest('tr');

        // store row index
        $('#modal_edit_index').val(row.index());

        // fill modal fields with row’s data
        $('#modal_product_name').val(row.find('td:eq(0)').text().trim());
        $('#modal_option_id').val(row.find('input[name="products[][option_id]"]').val());
        $('#modal_mrp').val(row.find('.mrp-cell').data('value'));
        $('#modal_quantity').val(row.find('.qty-cell').data('value'));
        $('#modal_discount').val(row.find('.disc-cell').data('value'));
        $('#modal_batch_no').val(row.find('.batch-cell').data('value'));
        $('#modal_expiry_date').val(row.find('.expiry-cell').data('value'));

        // Change button text & modal title for edit
        $('#productModalTitle').text('Edit Product');
        $('#productForm button[type=submit]').text('Update Product');

        // Show modal
        var productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    });



    // Remove selected product
    $(document).on('click', '.remove-product', function() {
        let row = $(this).closest('tr'); // the row to remove

        if (confirm('Are you sure you want to remove this product?')) {
            row.remove();
            updateOrderSummary();
        }
    });

    function updateOrderSummary() {
        let totalQty = 0, totalGross = 0, totalDiscount = 0, totalCgst = 0, totalSgst = 0;

        $('#selectedProducts tr').each(function() {
            let qty = parseFloat($(this).find('.qty-cell').data('value')) || 0;
            let mrp = parseFloat($(this).find('.mrp-cell').data('value')) || 0;
            let discount = parseFloat($(this).find('.disc-cell').data('value')) || 0;
            let cgst = parseFloat($(this).find('.mrp-cell').data('cgst')) || 0;
            let sgst = parseFloat($(this).find('.mrp-cell').data('sgst')) || 0;
            let isGstIncluded = parseInt($(this).find('.mrp-cell').data('gst-included')) || 0;

            totalQty += qty;
            totalGross += mrp * qty;
            totalDiscount += discount;

            // Only add GST if it's not included
            if(isGstIncluded === 0){
                totalCgst += cgst * qty;
                totalSgst += sgst * qty;
            }
        });

        let grandTotal = totalGross - totalDiscount + totalCgst + totalSgst;

        // Format numbers with commas
        $('#totalQty').text(totalQty.toLocaleString());
        $('#totalGross').text('₹ ' + totalGross.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#totalDiscount').text('₹ ' + totalDiscount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#totalCgst').text('₹ ' + totalCgst.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#totalSgst').text('₹ ' + totalSgst.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#grandTotal').text('₹ ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $('#savePruchaseForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serializeArray();
        let products = [];

        $('#selectedProducts tr').each(function(){
            let prod = {
                option_id: $(this).find('input[name="products[][option_id]"]').val(),  // matches Laravel
                batch_no: $(this).find('.batch-cell').data('value') || '',
                expiry_date: $(this).find('.expiry-cell').data('value') || '',
                quantity: $(this).find('.qty-cell').data('value'),
                mrp: $(this).find('.mrp-cell').data('value'),
                discount: $(this).find('.disc-cell').data('value') || 0
            };
            products.push(prod);
        });

        // merge products into formData
        let payload = {};
        formData.forEach(item => payload[item.name] = item.value);
        payload['products'] = products;

        $.ajax({
            url: "{{ route('purchase.store') }}", // route to your storePurchase
            method: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            success: function(res){
                showToast('success', 'Success', res.message);
                $('#savePruchase').modal('hide');
                $('#selectedProducts').empty(); // clear table after success
                $('#savePruchaseForm')[0].reset();
                updateOrderSummary(); // reset summary card
            },
            error: function(xhr){
                let message = 'Something went wrong!';

                if(xhr.responseJSON) {
                    // If Laravel sends validation errors
                    if(xhr.responseJSON.errors){
                        message = Object.values(xhr.responseJSON.errors)
                                        .flat()
                                        .join("\n");
                    } 
                    // If Laravel sends a custom error message
                    else if(xhr.responseJSON.message){
                        message = xhr.responseJSON.message;
                    }
                } else if(xhr.statusText){
                    // Fallback: HTTP status text
                    message = xhr.statusText;
                }

                showToast('error', 'Error', message);
            }
        });
    });



});

</script>
@endsection
