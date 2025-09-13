@extends('layouts.app')

@section('title', 'Vendor Edit')

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Edit Vendor</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Edit Vendor</li>
            </ul>
        </div>

        <form action="{{ route('vendor.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $vendor->id }}">
            <div class="row gy-4">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Vendor Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Store ID</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter Store ID"
                                            name="store_id" id="store_id"
                                            value="{{ old('store_id', $vendor->store_number) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Store Name</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter Store Name"
                                            name="name" id="name" value="{{ old('name', $vendor->name) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Store Location</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter Store Location"
                                            name="store_location" id="store_location"
                                            value="{{ old('store_location', $vendor->store_location) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Store Address</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter Address"
                                            name="address" id="address" value="{{ old('address', $vendor->address) }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Branches</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                                        </span>
                                        <select name="branch[]" class="form-select select2 select2-multiple"
                                            multiple="multiple" multiple data-placeholder="Choose ...">
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}"
                                                    {{ in_array($coach->id, $selected_branches) ? 'selected' : '' }}>
                                                    {{ $coach->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#branchModal">
                                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                Add New
                            </button>

                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Phone</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="+1 (555) 000-0000"
                                            name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Email</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="mage:email"></iconify-icon>
                                        </span>
                                        <input type="email" class="form-control" placeholder="Enter Email"
                                            name="email" id="email" value="{{ old('email', $vendor->email) }}"
                                            required>
                                    </div>
                                </div>
                            </div>




                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Password</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            value="{{ old('password') }}" class="form-control" placeholder="*******">
                                    </div>
                                </div>
                            </div>





                        </div>


                    </div>
                </div>
                <div class="col-md-3">


                    <div class="card p-0 mb-3">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Admin</h6>
                        </div>
                        <div class="row mb-24 gy-3 align-items-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label mb-3 d-flex">Admin</label>
                                    @if ($user->hasRole('Super Admin'))
                                        <select name="admin_id" class="form-select select"
                                            data-placeholder="Choose Admin" required>
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}"
                                                    {{ old('admin_id',$vendor->admin_id) == $admin->id ? 'selected' : '' }}>
                                                    {{ $admin->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="admin_id" value="{{ $user->id }}">
                                        <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card p-0 mb-3">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Image Upload</h6>
                        </div>
                        @php
                            $imageUrl = $vendor->getFirstMediaUrl('vendor-image');
                        @endphp
                        <div class="card-body p-24">
                            <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                <div
                                    class="uploaded-img {{ $imageUrl ? '' : 'd-none' }} position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                    <button type="button"
                                        class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                        <iconify-icon icon="radix-icons:cross-2"
                                            class="text-xl text-danger-600"></iconify-icon>
                                    </button>
                                    {{-- <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                        src="assets/images/user.png" alt="image"> --}}
                                    <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                        src="{{ $vendor->getFirstMediaUrl('vendor-image') ?? asset('images/default-user.png') }}"
                                        alt="image">

                                    {{-- @php
                                            echo "<pre>";
                                                print_r($vendor->getFirstMediaUrl('vendor-image'));
                                                die;
                                        @endphp --}}
                                </div>

                                <label
                                    class="upload-file h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                    for="upload-file">
                                    <iconify-icon icon="solar:camera-outline"
                                        class="text-xl text-secondary-light"></iconify-icon>
                                    <span class="fw-semibold text-secondary-light">Upload</span>
                                    <input id="upload-file" type="file" name="image" hidden>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Publish</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="status"
                                        class="form-check-input" value="1" {{ check_uncheck($vendor->status, 1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status"
                                        class="form-check-input" value="0" {{ check_uncheck($vendor->status, 0) }}>
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div>
                            <div class="md-3">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="modal fade" id="branchModal" tabindex="-1" aria-labelledby="branchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="branchModalLabel">Add New Branch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form method="post" class="row gy-3 needs-validation" novalidate id="coach_create_form">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name</label>
                                <input type="text" name="name" id="name" class="form-control radius-8"
                                    placeholder="Enter Name" required>
                                <div class="invalid-feedback">
                                    Please provide name.
                                </div>
                            </div>



                            <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                                <button type="reset"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8"
                                    id="save_button">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        // =============================== Upload Single Image js start here ================================================
        const fileInput = document.getElementById("upload-file");
        const imagePreview = document.getElementById("uploaded-img__preview");
        const uploadedImgContainer = document.querySelector(".uploaded-img");
        const removeButton = document.querySelector(".uploaded-img__remove");

        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length) {
                const src = URL.createObjectURL(e.target.files[0]);
                imagePreview.src = src;
                uploadedImgContainer.classList.remove("d-none");
            }
        });
        removeButton.addEventListener("click", () => {
            imagePreview.src = "";
            uploadedImgContainer.classList.add("d-none");
            fileInput.value = "";
        });
        // =============================== Upload Single Image js End here ================================================
    </script>

    <script>
        $(document).ready(function() {
            var index = $('.stock-input-row').length;

            // $('.add-price-meta-btn').click(function() {

            //     var lastInner = $('.inner-feilds').last(); // Get the last .inner element
            //     var html = `
        //    <div class="row mb-4 stock-input-row">
        //         <div class="row mb-24 gy-3 align-items-center">
        //             <label class="form-label mb-0 col-sm-2" >Branch Name</label>
        //               <div class="col-sm-10">
        //                                 <div class="icon-field">
        //                                     <span class="icon">
        //                                         <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
        //                                     </span>
        //                 <input class="form-control" name="branch[` + index + `][name]" type="text" id="branch_name_` +
            //                 index + `" placeholder="Branch Name" value=""  required  autocomplete="off"/>
        //                      </div>
        //         </div>
        //              <div class="invalid-feedback">This field is required.</div>
        //         </div>


        //       <div class="col-sm-1" style="display: flex;align-items: center !important;">
        //          <span class="text-danger stock-delete-btn" data-feather="trash-2" style="cursor: pointer;">
        //          </span>
        //       </div>
        //    </div>`;
            //     // $('.inner-feilds').append(html);
            //     lastInner.append(html);
            //     index++;
            //     feather.replace();
            // });

            // $('.inner-feilds').on('click', '.stock-delete-btn', function() {
            //     $(this).closest('.stock-input-row').remove();
            // });


            $('#coach_create_form').on('submit', function(e) {
                console.log(1234);

                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('vendor.coach.create') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {

                        $('#save_button').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            toastr.success(response.message);
                            $('#coach_create_form')[0].reset();

                            let newOption = new Option(response.coach.name, response.coach.name,
                                true, true);
                            $('select[name="branch[]"]').append(newOption).trigger('change');

                            // Close the modal if needed
                            $('#branchModal').modal('hide');

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
                        $('#save_button').prop('disabled', false).text('Saved');
                    }
                });
            });





        });
    </script>
@endsection
