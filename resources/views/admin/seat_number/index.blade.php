@extends('layouts.app')

@section('title', 'Branch')

@section('contents')


    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Branch</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Branch</li>
            </ul>
        </div>

        <div class="card basic-data-table">
            @can('Brand Create')
                <div
                    class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                    <h5 class="card-title mb-0">All Branch</h5>
                    <a href="{{ route('seatnumber.create') }}"
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
                            <th>Vendor</th>
                            <th>Branch</th>
                            <th>Total Seats</th>
                            @canany(['SeatNumber Edit', 'SeatNumber Delete'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coaches as $coach)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>{{ $coach->vendor->name ?? 'N/A' }}</td>
                                <td>{{ $coach->name ?? 'N/A' }}</td>
                                <td>{{ count(get_seat_numbers($coach->id)) }}</td>
                                @canany(['SeatNumber Edit', 'SeatNumber Delete'])
                                    <td>
                                        @can('SeatNumber Edit')
                                            <a href="{{ route('seatnumber.edit',$coach->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>

                                            </a>
                                        @endcan

                                        @can('SeatNumber Delete')
                                            <a class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                href="javascript:void(0);" onclick="deleteItem(this)"
                                                data-url="{{ route('seatnumber.destroy',$coach->id) }}" data-item="Coach and seat number"
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
