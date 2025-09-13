@extends('layouts.app')

@section('title', 'Hsncodes')

@section('contents')


    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Hsncodes</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Hsncodes</li>
            </ul>
        </div>

        <div class="card basic-data-table">
            @can('Hsncode Create')
                <div
                    class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                    <h5 class="card-title mb-0">All Hsncodes</h5>
                    <a href="{{ route('hsncode.create') }}"
                        class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New
                    </a>
                </div>
            @endcan

            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>
                                S.L
                            </th>
                            <th>Hsncode</th>
                            <th>GST Rate</th>
                            <th>Status</th>
                            <th>Created At</th>
                            @canany(['Hsncode Edit', 'Hsncode Delete'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hsncodes as $hsncode)
                            <tr>
                                <td>

                                    {{ $loop->iteration }}


                                </td>
                                <td class="text-wrap">{{ $hsncode->hsncode }}</td>
                                <td class="text-wrap">{{ $hsncode->gst_rate }}</td>

                                <td>{!! check_visibility($hsncode->is_visible) !!}</td>
                                <td class="text-wrap">{{ format_datetime($hsncode->created_at) }}</td>
                                @canany(['Hsncode Edit', 'Hsncode Delete'])
                                    <td>
                                        @can('Hsncode Edit')
                                            <a href="{{ route('hsncode.edit', $hsncode->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        @endcan

                                        @can('Hsncode Delete')
                                            <a class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                href="javascript:void(0);" onclick="deleteItem(this)"
                                                data-url="{{ route('hsncode.destroy', $hsncode->id) }}" data-item="Hsncode"
                                                alt="delete"> <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon></a>
                                        @endcan
                                    </td>
                                @endcanany

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let table = new DataTable("#dataTable");
    </script>
@endsection
