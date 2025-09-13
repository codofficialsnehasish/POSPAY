@extends('layouts.app')

@section('title', 'Role Permission')

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Role Permission</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Assign Permissions</li>
            </ul>
        </div>

        <form class="custom-validation" action="{{ route('role.give-permissions', ['roleId' => $role->id]) }}"
            method="post">
            @csrf
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">Assign Permissions</h5>
                            <button type="submit" class="btn btn-light btn-sm">
                                <i class="bi bi-save"></i> Save
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row gy-4">
                                @foreach ($permissions->groupBy('group_name') as $groupName => $groupedPermissions)
                                    <div class="col-12">
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <!-- Group Header -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 text-primary fw-bold">
                                                    {{ $groupName ?? 'Uncategorized' }}
                                                </h6>
                                                <div class="form-check d-flex align-items-center gap-1">
                                                    <input type="checkbox" class="form-check-input select-all"
                                                        id="selectAll-{{ $loop->index }}">
                                                    <label class="form-check-label small text-muted"
                                                        for="selectAll-{{ $loop->index }}">Select All</label>
                                                </div>
                                            </div>

                                            <!-- Permissions List -->
                                            <div class="d-flex flex-wrap gap-3">
                                                @foreach ($groupedPermissions as $permission)
                                                    <div class="form-check d-flex align-items-center gap-1">
                                                        <input class="form-check-input permission-checkbox"
                                                            name="permission[]" id="permission-{{ $permission->id }}"
                                                            type="checkbox" value="{{ $permission->name }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                                        <label class="form-check-label small fw-semibold mb-0"
                                                            for="permission-{{ $permission->id }}">
                                                            {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bottom Save Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-save"></i> Save
                                </button>
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
                imgContainer.classList.add("position-relative", "h-120-px", "w-120-px", "border",
                    "input-form-light", "radius-8", "overflow-hidden", "border-dashed", "bg-neutral-50");

                const removeButton = document.createElement("button");
                removeButton.type = "button";
                removeButton.classList.add("uploaded-img__remove", "position-absolute", "top-0", "end-0",
                    "z-1", "text-2xxl", "line-height-1", "me-8", "mt-8", "d-flex");
                removeButton.innerHTML =
                    "<iconify-icon icon=\'radix-icons:cross-2\' class=\'text-xl text-danger-600\'></iconify-icon>";

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
                li.classList.add("uploaded-image-name-list", "text-primary-600", "fw-semibold", "d-flex",
                    "align-items-center", "gap-2");

                // Create the Link Iconify icon element
                var iconifyIcon = document.createElement("iconify-icon");
                iconifyIcon.setAttribute("icon", "ph:link-break-light");
                iconifyIcon.classList.add("text-xl", "text-secondary-light");

                // Create the Cross Iconify icon element
                var crossIconifyIcon = document.createElement("iconify-icon");
                crossIconifyIcon.setAttribute("icon", "radix-icons:cross-2");
                crossIconifyIcon.classList.add("remove-image", "text-xl", "text-secondary-light",
                    "text-hover-danger-600");

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

    <!-- Script for Select All functionality -->
    <script>
        document.querySelectorAll('.select-all').forEach((selectAllCheckbox) => {
            selectAllCheckbox.addEventListener('change', function () {
                const groupBox = this.closest('.border');
                const checkboxes = groupBox.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    </script>
@endsection
