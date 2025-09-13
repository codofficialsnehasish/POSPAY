<form class="row gy-3 needs-validation" novalidate id="add_variation_option_form">
    @csrf


    <input type="hidden" name="variation_id" id="variation_id" value="{{ $variation->id }}">
    <div class="row">
        <div class="col-12 mb-20">
            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Default Option
            </label>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_default1" name="is_default" class="form-check-input" value="1"
                    checked>
                <label class="form-check-label" for="is_default1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_default2" name="is_default" class="form-check-input" value="0">
                <label class="form-check-label" for="is_default2">No</label>
            </div>
            <div class="invalid-feedback">
                Please provide name.
            </div>
        </div>
        {{-- <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Name</label>
            <input type="text" name="option_name" id="option_name" class="form-control radius-8"
                placeholder="Enter Name" required>
            <div class="invalid-feedback">
                Please provide name.
            </div>
        </div> --}}

        <div class="row">
            <div class="col-md-6 mb-20">
                <label for="measure" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Measure
                </label>
                <select name="measure" id="measure" class="form-control radius-8" required>
                    <option value="">Select Measure</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit->short_name }}">{{ $unit->name }} ({{$unit->short_name}})</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Please select measure.
                </div>
            </div>
            <div class="col-md-6 mb-20">
                <label for="unit" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Unit
                </label>
                <input type="number" name="unit" id="unit" class="form-control radius-8"
                    placeholder="Enter Unit" required>
                <div class="invalid-feedback">
                    Please provide unit.
                </div>
            </div>
    
        </div>

        <div class="row">
            {{-- <div class="col-md-6 mb-20">
                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Barcode</label>
                <input type="text" name="barcode" id="barcode" class="form-control radius-8"
                    placeholder="Enter Barcode" required>
                <div class="invalid-feedback">
                    Please provide barcode.
                </div>
            </div> --}}

            <div class="col-md-6 mb-20">
                <label for="barcode" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Barcode</label>
                <div class="input-group">
                    <input type="text" name="barcode" id="barcode" class="form-control radius-8" placeholder="Enter Barcode" required>
                    <button type="button" id="generateBarcodeBtn" class="btn btn-outline-primary">
                    Generate</button>
                    <button type="button" id="clearBarcodeBtn" class="btn btn-outline-secondary">
                    Clear</button>
                </div>

                <div class="invalid-feedback">
                    Please provide barcode.
                </div>
            </div>
    
            <div class="col-md-6 mb-20">
                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Quantity</label>
                <input type="text" name="quantity" id="quantity" class="form-control radius-8"
                    placeholder="Enter quantity" required>
                <div class="invalid-feedback">
                    Please provide name.
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6 mb-20">
                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    MRP</label>
                <input type="text" name="mrp" id="mrp" class="form-control radius-8" placeholder="Enter MRP"
                    required>
                <div class="invalid-feedback">
                    Please provide mrp.
                </div>
            </div>
            <div class="col-md-6 mb-20">
                <div class="form-check style-check d-flex align-items-center">
                    <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                        No Discount</label>
                    <input class="form-check-input radius-4 border border-neutral-400" type="checkbox" name="no_discount" id="no_discount" value="1">
                </div>
            </div>
            <div class="col-md-6 mb-20 d-flex justify-content-between" id="discountDiv">
                <div class="col-5 mb-20">
                    <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                        Discount Rate</label>
                    <input type="text" name="discount_rate" id="discount_rate" class="form-control radius-8"
                        placeholder="Enter discount rate" >
                    <div class="invalid-feedback">
                        Please provide discount rate.
                    </div>
                </div>
    
                <div class="col-6 mb-20">
                    <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                        Discount Amount</label>
                    <input type="text" name="discount_amount" id="discount_amount" class="form-control radius-8"
                        placeholder="Enter discount amount" >
                    <div class="invalid-feedback">
                        Please provide discount amount.
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-20">
                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Price</label>
                <input type="text" name="price" id="price" class="form-control radius-8" placeholder="Enter Price"
                    required>
                <div class="invalid-feedback">
                    Please provide price.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
            <button type="reset"
                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8"
                data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8"
                id="save_variation_option_btn">
                Save
            </button>
        </div>
    </div>
</form>


