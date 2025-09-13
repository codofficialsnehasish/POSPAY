    <!-- Modal Start -->
    <div class="modal fade" id="addVariationModal" tabindex="-1" aria-labelledby="addVariationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="addVariationModalLabel">Add Variation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form method="post" class="row gy-3 needs-validation" novalidate id="form_add_product_variation">
                        @csrf

                        <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                        <div class="row">
                            <div class="col-12 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Choose Exsiting
                                    Variations&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_exsiting_variations1" name="is_exsiting_variations"
                                        class="form-check-input" value="1" disabled >
                                    <label class="form-check-label" for="is_exsiting_variations1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_exsiting_variations2" name="is_exsiting_variations"
                                        class="form-check-input" value="0" checked>
                                    <label class="form-check-label" for="is_exsiting_variations2">No</label>
                                </div>
                                <div class="invalid-feedback">
                                    Please provide name.
                                </div>
                            </div>
                            <div class="col-12 mb-20 hide-if-no_exsiting_variations">
                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Name</label>
                                <input type="text" name="name" id="name" class="form-control radius-8"
                                    placeholder="Enter Name" required>
                                <div class="invalid-feedback">
                                    Please provide name.
                                </div>
                            </div>

                            <div class="col-12 mb-20 hide-if-exsiting_variations">
                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Existing Variations</label>
                                <select class="form-select" name="existing_variation_id" id="existing_variation_id">
                                    @foreach ($variations as $variation)
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
                                    <option value="radio_button">Radio Button (Single Selection)</option>
                                    <option value="dropdown">Dropdown (Single Selection)</option>
                                    <option value="checkbox">Checkbox (Multiple Selection)</option>
                                    <option value="text" checked="">Text</option>
                                    <option value="number">Number</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please provide variations.
                                </div>
                            </div>
                            <div class="col-12 mb-20">
                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Option Display Type</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="option_display_type1" name="option_display_type"
                                        class="form-check-input" value="text" checked>
                                    <label class="form-check-label" for="option_display_type1">Text</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="option_display_type2" name="option_display_type"
                                        class="form-check-input" value="color" disabled >
                                    <label class="form-check-label" for="option_display_type2">Color</label>
                                </div>
                                <div class="invalid-feedback">
                                    Please provide variations.
                                </div>
                            </div>

                            {{-- <div class="col-12 mb-20">
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
                            </div> --}}
                            <div class="col-12 mb-20">
                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Visiblity</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_visible1" name="is_visible"
                                        class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="is_visible1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_visible2" name="is_visible"
                                        class="form-check-input" value="0">
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
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <div class="modal fade" id="editVariationModal" tabindex="-1" aria-labelledby="editVariationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="editVariationModalLabel">Edit Variation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24" id="form_edit_product_variation_html">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addVariationOptionModal" tabindex="-1"
        aria-labelledby="addVariationOptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="addVariationOptionModalLabel">Add Variation Option</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24" id="add_variation_option_html">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewVariationOptionModal" tabindex="-1"
        aria-labelledby="addVariationOptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="addVariationOptionModalLabel">View Variation Option</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24" id="view_variation_option_html">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVariationOptionModal" tabindex="-1"
        aria-labelledby="editVariationOptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="editVariationOptionModalLabel">Edit Variation Option</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24" id="edit_variation_option_html">

                </div>
            </div>
        </div>
    </div>
