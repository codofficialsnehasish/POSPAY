@extends('layouts.app')

@section('title', 'Attendees')

@section('contents')


    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Attendees</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Attendees</li>
            </ul>
        </div>

        <div class="card h-100 p-0 radius-12">
            <div
                class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                @can('Attendee Create')
           

                    <a href="{{ route('attendee.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New
                    </a>
                @endcan

            </div>

            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Registred Date</th>
                                <th>Status</th>
                                @canany(['Attendee Edit', 'Attendee Delete'])
                                    <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="w60">
                                        <img class="avatar" width="50" src="{{ $user->getFirstMediaUrl('user-image') }}"
                                            alt="">
                                    </td>
                                    <td><span class="font-16">{{ $user->name }}</span></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->getRoleNames()->first() }}</td>
                                    <td>{{ format_datetime($user->created_at) }}</td>
                                    <td>{!! check_status($user->status) !!}</td>
                                    <td>

                                        @can('Attendee Edit')
                                            <a href="{{ route('attendee.edit', $user->id) }}"
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                title="Edit"><iconify-icon icon="lucide:edit"
                                                    class="menu-icon"></iconify-icon></a>
                                        @endcan
                                        @can('Attendee Delete')
                                            <a class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                href="javascript:void(0);" onclick="deleteItem(this)"
                                                data-url="{{ route('user.destroy', $user->id) }}" data-item="Route"
                                                alt="delete"> <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

    <!-- Modal Start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('permission.create') }}" method="post" class="row gy-3 needs-validation"
                        novalidate>
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
                            <div class="col-12 mb-20">
                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Group
                                    Name</label>
                                <input type="text" name="group_name" id="group_name" class="form-control radius-8"
                                    placeholder="Enter Group Name" required>
                                <div class="invalid-feedback">
                                    Please provide group name.
                                </div>
                            </div>


                            <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                                <button type="reset"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
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

@endsection

@section('script')
    <script>
        let table = new DataTable("#dataTable");
    </script>

    <script>
        (() => {
            "use strict"

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll(".needs-validation")

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener("submit", event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add("was-validated")
                }, false)
            })
        })()
    </script>

@endsection
