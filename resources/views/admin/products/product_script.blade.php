<script>
    $(document).ready(function() {
        $('#form_add_product_variation').on('submit', function(e) {
            console.log(1234);

            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '{{ route('products.add-variation') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {

                    $('#save_variation').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        toastr.success(response.message);
                        $('#form_add_product_variation')[0].reset();

                        setTimeout(function() {
                            location
                                .reload(); // Reloads the page after 1 second (optional delay)
                        }, 1000);

                    } else {
                        toastr.error('Something went wrong!');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An unexpected error occurred!');
                    }
                    console.log(xhr.responseText);
                },
                complete: function() {
                    $('#save_variation').prop('disabled', false).text('Saved');
                }
            });
        });

        $(document).on('click', '.edit-variation-btn', function() {

            let variationId = $(this).data('id');

            let productId = $('#product_id').val();

            $.ajax({
                url: '{{ route('products.edit-variation') }}',
                type: 'GET',
                data: {
                    id: variationId,
                    product_id: productId,
                },
                beforeSend: function() {
                    $('#form_edit_product_variation_html').html('<p>Loading...</p>');
                },
                success: function(response) {
                    $('#form_edit_product_variation_html').html(response.html);
                },
                error: function() {
                    $('#form_edit_product_variation_html').html(
                        '<p>Error loading form</p>');
                }
            });
        });
    });

    function generateBarcode() {
        let barcode = "";
        for (let i = 0; i < 12; i++) {
            barcode += Math.floor(Math.random() * 10);
        }

        // Calculate check digit
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            let digit = parseInt(barcode[i]);
            sum += (i % 2 === 0) ? digit : digit * 3;
        }
        let checkDigit = (10 - (sum % 10)) % 10;

        return barcode + checkDigit;
    }

    $(document).on('click', '#generateBarcodeBtn', function(e) {
        $('#barcode').val(generateBarcode());
    });

    // Clear button click
    $(document).on('click', '#clearBarcodeBtn', function(e) {
        $('#barcode').val('');
    });

    $(document).on('submit', '#add_variation_option_form', function(e) {
        e.preventDefault();

        // Check if form is valid
        if (!this.checkValidity()) {
            this.reportValidity(); // Show browser validation messages
            return false; // Stop AJAX
        }

        let formData = new FormData(this);

        $.ajax({
            url: '{{ route('products.store-variation-option') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#save_variation_option_btn').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message);
                    $('#add_variation_option_form')[0].reset();

                    setTimeout(function() {
                        location
                            .reload(); // You can replace this with partial reload if needed
                    }, 1000);
                } else {
                    toastr.error('Something went wrong!');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON) {
                    // ✅ Validation errors
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message); // show all messages
                            });
                        });
                    } 
                    // ✅ Other error messages
                    else if (xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } 
                    else {
                        toastr.error('An unexpected error occurred!');
                    }
                } else {
                    toastr.error('An unexpected error occurred!');
                }
            },

            complete: function() {
                $('#save_variation_option_btn').prop('disabled', false).text('Saved');
            }
        });
    });



    function add_product_variation_option(variationId) {


        $.ajax({
            url: '{{ route('products.add-variation-option') }}',
            type: 'GET',
            data: {
                id: variationId,

            },
            beforeSend: function() {
                $('#add_variation_option_html').html('<p>Loading...</p>');
            },
            success: function(response) {
                $('#add_variation_option_html').html(response.html);

                $("#addVariationOptionModal").modal('show');
            },
            error: function() {
                $('#add_variation_option_html').html(
                    '<p>Error loading form</p>');
            }
        });

    }



    function view_product_variation_option(variationId) {


        $.ajax({
            url: '{{ route('products.view-variation-option') }}',
            type: 'GET',
            data: {
                id: variationId,

            },
            beforeSend: function() {
                $('#view_variation_option_html').html('<p>Loading...</p>');
            },
            success: function(response) {
                $('#view_variation_option_html').html(response.html);

                $("#viewVariationOptionModal").modal('show');
            },
            error: function() {
                $('#view_variation_option_html').html(
                    '<p>Error loading form</p>');
            }
        });

    }



    function edit_product_variation_option(variationId, optionId) {

        $.ajax({
            url: '{{ route('products.edit-variation-option') }}',
            type: 'GET',
            data: {
                variation_id: variationId,
                option_id: optionId,


            },
            beforeSend: function() {
                $('#edit_variation_option_html').html('<p>Loading...</p>');
            },
            success: function(response) {
                $('#edit_variation_option_html').html(response.html);

                $("#editVariationOptionModal").modal('show');
                if ($('#no_discount').is(':checked')) {
                    $('#discountDiv').addClass('d-none');
                    $('#discount_rate').val('');
                    $('#discount_amount').val('');
                    $('#price').val(mrp.toFixed(2));
                }
            },
            error: function() {
                $('#edit_variation_option_html').html(
                    '<p>Error loading form</p>');
            }
        });

    }

    $(document).on('submit', '#edit_variation_option_form', function(e) {
        e.preventDefault();


        let formData = new FormData(this);

        $.ajax({
            url: '{{ route('products.update-variation-option') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#update_variation_option_btn').prop('disabled', true).text('Updating...');
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message);
                    $('#edit_variation_option_form')[0].reset();

                    setTimeout(function() {
                        location
                            .reload(); // You can replace this with partial reload if needed
                    }, 1000);
                } else {
                    toastr.error('Something went wrong!');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('An unexpected error occurred!');
                }
                console.log(xhr.responseText);
            },
            complete: function() {
                $('#update_variation_option_btn').prop('disabled', false).text('Updated');
            }
        });
    });


    // $(document).on('change', 'input[name=is_exsiting_variations]', function() {
    //     var value = $('input[name=is_exsiting_variations]:checked').val();
    //     if (value == 1) {
    //         $(".hide-if-exsiting_variations").removeClass("d-none");

    //         $(".hide-if-no_exsiting_variations").addClass("d-none");
    //     } else {
    //         $(".hide-if-no_exsiting_variations").removeClass("d-none");

    //         $(".hide-if-exsiting_variations").addClass("d-none");

    //     }
    // });

    $(document).on('change', 'input[name=is_exsiting_variations]', function() {
        var value = $('input[name=is_exsiting_variations]:checked').val();
        if (value == 1) {
            $(".hide-if-exsiting_variations").removeClass("d-none");
            $(".hide-if-no_exsiting_variations").addClass("d-none");
        } else {
            $(".hide-if-no_exsiting_variations").removeClass("d-none");
            $(".hide-if-exsiting_variations").addClass("d-none");
        }
    });

    // Run once on page load to apply correct visibility
    $('input[name=is_exsiting_variations]:checked').trigger('change');



    $(document).on('input', '#mrp, #discount_amount', function() {
        calculateDiscountByAmount();
    });

    $(document).on('input', '#discount_rate', function() {
        calculateDiscountByRate();
    });

    function calculateDiscountByAmount() {
        var mrp = parseFloat($('#mrp').val()) || 0;
        var discountAmount = parseFloat($('#discount_amount').val()) || 0;

        if (mrp > 0 && discountAmount >= 0) {
            var discountRate = (discountAmount / mrp) * 100;
            discountRate = discountRate.toFixed(2);
            var price = mrp - discountAmount;
            price = price.toFixed(2);
            $('#discount_rate').val(discountRate);
            $('#price').val(price);
        } else {
            $('#discount_rate').val('');
            $('#price').val('');
        }
    }

    function calculateDiscountByRate() {
        var mrp = parseFloat($('#mrp').val()) || 0;
        var discountRate = parseFloat($('#discount_rate').val()) || 0;
        if (mrp > 0 && discountRate >= 0) {
            var discountAmount = (discountRate / 100) * mrp;
            discountAmount = discountAmount.toFixed(2);
            var price = mrp - discountAmount;
            price = price.toFixed(2);
            $('#discount_amount').val(discountAmount);
            $('#price').val(price);
        } else {
            $('#discount_amount').val('');
            $('#price').val('');
        }
    }



    $(document).on('change', 'input[name="no_discount"]', function() {
        var mrp = parseFloat($('#mrp').val()) || 0;

        if ($(this).is(':checked')) {
            $('#discountDiv').addClass('d-none');
            $('#discount_rate').val('');
            $('#discount_amount').val('');
            $('#price').val(mrp.toFixed(2));

        } else {
            $('#discountDiv').removeClass('d-none');
            calculateDiscountByAmount();
        }
    });



    $('input[name="no_discount"]').trigger('change');
</script>
