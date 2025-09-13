<form method="post" class="row gy-3 needs-validation" novalidate id="form_edit_product_variation">
    @csrf

  
    <div class="row">
        <div class="col-12 mb-20">
            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Choose Exsiting
                Variations&nbsp;</label>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_exsiting_variations1" name="is_exsiting_variations"
                    class="form-check-input" value="1">
                <label class="form-check-label" for="is_exsiting_variations1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_exsiting_variations2" name="is_exsiting_variations"
                    class="form-check-input" value="0">
                <label class="form-check-label" for="is_exsiting_variations2">No</label>
            </div>
            <div class="invalid-feedback">
                Please provide name.
            </div>
        </div>
        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Name</label>
            <input type="text" name="name" id="name" class="form-control radius-8"
                placeholder="Enter Name" required value="{{$variation->name}}">
            <div class="invalid-feedback">
                Please provide name.
            </div>
        </div>

        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Existing Variations</label>
            <select class="form-select" name="existing_variation_id" id="existing_variation_id">
                @foreach ($existing_variations as $variation)
                    <option value="{{ $variation->id }} ">{{ $variation->name }}</option>
                @endforeach
            </select>

            <div class="invalid-feedback">
                Please provide name.
            </div>
        </div>

        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Variation Type</label>
            <select name="variation_type" class="form-control"
                onchange="show_hide_form_option_images(this.value);" required="">
                <option value="radio_button" {{$variation->variation_type == "radio_button" ? "checked" :'' }}>Radio Button (Single Selection)</option>
                <option value="dropdown" {{$variation->variation_type == "dropdown" ? "checked" :'' }}>Dropdown (Single Selection)</option>
                <option value="checkbox" {{$variation->variation_type == "checkbox" ? "checked" :'' }}>Checkbox (Multiple Selection)</option>
                <option value="text" {{$variation->variation_type == "text" ? "checked" :'' }}>Text</option>
                <option value="number" {{$variation->variation_type == "number" ? "checked" :'' }}>Number</option>
            </select>
            <div class="invalid-feedback">
                Please provide variations.
            </div>
        </div>
        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Variation Type</label>
            <div class="form-check form-check-inline">
                <input type="radio" id="option_display_type1" name="option_display_type"
                    class="form-check-input" value="1" checked>
                <label class="form-check-label" for="option_display_type1">Color</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="option_display_type2" name="option_display_type"
                    class="form-check-input" value="0">
                <label class="form-check-label" for="option_display_type2">Text</label>
            </div>
            <div class="invalid-feedback">
                Please provide variations.
            </div>
        </div>

        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Show Option Images on Slider When an Option is
                Selected</label>
            <div class="form-check form-check-inline">
                <input type="radio" id="show_images_on_slider1" name="show_images_on_slider"
                    class="form-check-input" value="1" checked>
                <label class="form-check-label" for="show_images_on_slider1">Color</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="show_images_on_slider2" name="show_images_on_slider"
                    class="form-check-input" value="0">
                <label class="form-check-label" for="show_images_on_slider2">Text</label>
            </div>
            <div class="invalid-feedback">
                Please provide variations.
            </div>
        </div>
        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Use Different Price for Options</label>
            <div class="form-check form-check-inline">
                <input type="radio" id="use_different_price1" name="use_different_price"
                    class="form-check-input" value="1" checked>
                <label class="form-check-label" for="use_different_price1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="use_different_price2" name="use_different_price"
                    class="form-check-input" value="0">
                <label class="form-check-label" for="use_different_price2">No</label>
            </div>
            <div class="invalid-feedback">
                Please provide variations.
            </div>
        </div>
        <div class="col-12 mb-20">
            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                Visiblity</label>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_visible1" name="is_visible" class="form-check-input"
                    value="1" checked>
                <label class="form-check-label" for="is_visible1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="is_visible2" name="is_visible" class="form-check-input"
                    value="0">
                <label class="form-check-label" for="is_visible2">No</label>
            </div>
            <div class="invalid-feedback">
                Please provide variations.
            </div>
        </div>


        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
            <button type="reset"
                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8"
                data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit"
                class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8"
                id="save_variation">
                Save
            </button>
        </div>
    </div>
</form>