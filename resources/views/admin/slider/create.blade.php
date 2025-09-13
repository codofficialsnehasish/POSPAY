@extends('layouts.app')

@section('title','Slider')

@section('contents')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create Slider</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Create Slider</li>
        </ul>
    </div>

    <form action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Slider Details</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" value="{{ old('title')}}" class="form-control" placeholder="Enter Title">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" cols="50" placeholder="Enter a description...">{{ old('description') }}</textarea>
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
                        <div class="uploaded-img d-none position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                            <button type="button" class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                <iconify-icon icon="radix-icons:cross-2" class="text-xl text-danger-600"></iconify-icon>
                            </button>
                            <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover" src="assets/images/user.png" alt="image">
                        </div>

                        <label class="upload-file h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1" for="upload-file">
                            <iconify-icon icon="solar:camera-outline" class="text-xl text-secondary-light"></iconify-icon>
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
                        <label class="form-label mb-3 d-flex">Visibility</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline1" name="is_visible" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="customRadioInline1">Visible</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="customRadioInline2" name="is_visible" class="form-check-input" value="0">
                            <label class="form-check-label" for="customRadioInline2">Invisible</label>
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