@extends('layouts.app')

@section('title','Coupon')

@section('contents')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Coupon</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit Coupon</li>
        </ul>
    </div>

    <form action="{{ route('coupon.update',$coupon->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row gy-4">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Coupon Details</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" value="{{ $coupon->code }}" class="form-control" placeholder="Enter Coupon Code">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="type">Discount Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="percentage" @if($coupon->type == 'percentage') selected @endif>Percentage</option>
                                <option value="flat" @if($coupon->type == 'flat') selected @endif>Flat</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="value" value="{{ $coupon->value }}" class="form-control" placeholder="Enter Discount Value">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Minimum Purchase</label>
                            <input type="number" name="minimum_purchase" value="{{ $coupon->minimum_purchase }}" class="form-control" placeholder="Enter Minimum Purchase">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" value="{{ $coupon->start_date }}" class="form-control" placeholder="Enter Start Date">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" value="{{ $coupon->end_date }}" class="form-control" placeholder="Enter End Date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Publish</div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="usage_type" class="form-label">Usage Type</label>
                        <select name="usage_type" id="usage_type" class="form-control" required>
                            <option value="one-time" @if($coupon->usage_type == 'one-time') selected @endif>One-Time</option>
                            <option value="multiple" @if($coupon->usage_type == 'multiple') selected @endif>Multiple</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-3 d-flex">Active</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline1" name="is_active" class="form-check-input" value="1" {{ check_uncheck($coupon->is_active,1) }}>
                            <label class="form-check-label" for="customRadioInline1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline2" name="is_active" class="form-check-input" value="0" {{ check_uncheck($coupon->is_active,0) }}>
                            <label class="form-check-label" for="customRadioInline2">No</label>
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

    // ================================================ Upload Multiple image js Start here ================================================
    const fileInputMultiple = document.getElementById("upload-file-multiple");
    const uploadedImgsContainer = document.querySelector(".uploaded-imgs-container");

    fileInputMultiple.addEventListener("change", (e) => {
        const files = e.target.files;

        Array.from(files).forEach(file => {
            const src = URL.createObjectURL(file);

            const imgContainer = document.createElement("div");
            imgContainer.classList.add("position-relative", "h-120-px", "w-120-px", "border", "input-form-light", "radius-8", "overflow-hidden", "border-dashed", "bg-neutral-50");

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("uploaded-img__remove", "position-absolute", "top-0", "end-0", "z-1", "text-2xxl", "line-height-1", "me-8", "mt-8", "d-flex");
            removeButton.innerHTML = "<iconify-icon icon=\'radix-icons:cross-2\' class=\'text-xl text-danger-600\'></iconify-icon>";

            const imagePreview = document.createElement("img");
            imagePreview.classList.add("w-100", "h-100", "object-fit-cover");
            imagePreview.src = src;

            imgContainer.appendChild(removeButton);
            imgContainer.appendChild(imagePreview);
            uploadedImgsContainer.appendChild(imgContainer);

            removeButton.addEventListener("click", () => {
                URL.revokeObjectURL(src);
                imgContainer.remove();
            });
        });

        // Clear the file input so the same file(s) can be uploaded again if needed
        fileInputMultiple.value = "";
    });
    // ================================================ Upload Multiple image js End here  ================================================

    // ================================================ Upload image & show it\'s name js start  ================================================
    document.getElementById("file-upload-name").addEventListener("change", function(event) {
        var fileInput = event.target;
        var fileList = fileInput.files;
        var ul = document.getElementById("uploaded-img-names");

        // Add show-uploaded-img-name class to the ul element if not already added
        ul.classList.add("show-uploaded-img-name");

        // Append each uploaded file name as a list item with Font Awesome and Iconify icons
        for (var i = 0; i < fileList.length; i++) {
            var li = document.createElement("li");
            li.classList.add("uploaded-image-name-list", "text-primary-600", "fw-semibold", "d-flex", "align-items-center", "gap-2");

            // Create the Link Iconify icon element
            var iconifyIcon = document.createElement("iconify-icon");
            iconifyIcon.setAttribute("icon", "ph:link-break-light");
            iconifyIcon.classList.add("text-xl", "text-secondary-light");

            // Create the Cross Iconify icon element
            var crossIconifyIcon = document.createElement("iconify-icon");
            crossIconifyIcon.setAttribute("icon", "radix-icons:cross-2");
            crossIconifyIcon.classList.add("remove-image", "text-xl", "text-secondary-light", "text-hover-danger-600");

            // Add event listener to remove the image on click
            crossIconifyIcon.addEventListener("click", (function(liToRemove) {
                return function() {
                    ul.removeChild(liToRemove); // Remove the corresponding list item
                };
            })(li)); // Pass the current list item as a parameter to the closure

            // Append both icons to the list item
            li.appendChild(iconifyIcon);

            // Append the file name text to the list item
            li.appendChild(document.createTextNode(" " + fileList[i].name));

            li.appendChild(crossIconifyIcon);

            // Append the list item to the unordered list
            ul.appendChild(li);
        }
    });
    // ================================================ Upload image & show it\'s name js end ================================================
</script>
@endsection