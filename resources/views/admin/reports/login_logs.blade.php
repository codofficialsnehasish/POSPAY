@extends('layouts.app')

@section('title','Login Logs')

@section('contents')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Login Logs</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Login Logs</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h5 class="card-title mb-0">All Login Activities</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>User</th>
                        <th>Vendor</th>
                        <th>Device Type</th>
                        <th>Model</th>
                        <th>Serial No</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td>{{ $log->vendor->name ?? 'N/A' }}</td>
                        <td>{{ $log->device_type ?? 'N/A' }}</td>
                        <td>{{ $log->model ?? 'N/A' }}</td>
                        <td>{{ $log->serial_number ?? 'N/A' }}</td>
                        <td>{{ $log->login_time ? format_datetime($log->login_time) : 'N/A' }}</td>
                        <td>{{ $log->logout_time ? format_datetime($log->logout_time) : 'Active' }}</td>
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
