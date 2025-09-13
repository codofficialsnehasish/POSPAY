@extends('layouts.app')

@section('title', 'Attendee Edit')

@section('css')
<style>
    span.select2.select2-container.select2-container--default.select2-container--focus.select2-container--below.select2-container--open {
    border: solid black 1px;
}
</style>
    
@endsection

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Edit Attendee</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Edit Attendee</li>
            </ul>
        </div>

        <form action="{{ route('attendee.update') }}" method="post" enctype="multipart/form-data">

            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
            @csrf
            <div class="row gy-4">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Attendee Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">First Name</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter First Name"
                                            name="first_name" id="first_name"
                                            value="{{ old('first_name', $data->first_name) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Last Name</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Enter Last Name"
                                            name="last_name" id="last_name" value="{{ old('last_name', $data->last_name) }}"
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
                                        <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                            id="email" value="{{ old('email', $data->email) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-24 gy-3 align-items-center">
                                <label class="form-label mb-0 col-sm-2">Phone</label>
                                <div class="col-sm-10">
                                    <div class="icon-field">
                                        <span class="icon">
                                            <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control" placeholder="+1 (555) 000-0000"
                                            name="phone" id="phone" value="{{ old('phone', $data->phone) }}" required>
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
                                        <input type="password" name="password" id="password" value="{{ old('password') }}"
                                            class="form-control" placeholder="*******">
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-0 mb-3">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Image Upload</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                <div
                                    class="uploaded-img d-none position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                    <button type="button"
                                        class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                        <iconify-icon icon="radix-icons:cross-2"
                                            class="text-xl text-danger-600"></iconify-icon>
                                    </button>
                                    {{-- <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                        src="{{ $data->getFirstMediaUrl('user-image') }}" alt="image"> --}}

                                    <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                        src="{{ $data->getFirstMediaUrl('user-image') ?? asset('images/default-user.png') }}"
                                        alt="image" >

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
                                        class="form-check-input" value="1" {{ check_uncheck($data->status, 1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status"
                                        class="form-check-input" value="0" {{ check_uncheck($data->status, 0) }}>
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
@endsection
