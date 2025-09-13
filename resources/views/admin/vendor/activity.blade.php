@extends('layouts.app')

@section('title')
    User Activites
@endsection
@section('css')
    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <style>
        .filter-form {
            position: relative;
            font-size: .8rem;
            width: 30rem;
        }

        .filter-form form {
            display: flex;
        }

        legend.legendcls {
            float: none;
            width: auto;
            font-size: 12px;
            margin-left: 14px;
            /* font-weight: bold !important; */
            margin-bottom: 0;
        }

        .stoc_ks h4 {
            padding: 5px 10px;
            font-size: 14px;
        }

        .fesd_q ul {
            list-style: none;
            padding-left: 0;
            display: flex;
            margin: 0px;
        }

        .fesd_q ul li h4 {
            padding: 5px 9px 5px 10px;
        }

        .webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        .webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered {
            padding: 0px;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered .modal-content.border.border-translucent {
            padding: 20px;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered .modal-content.border.border-translucent h5 {
            font-size: 18px !important;
            !i;
            !;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered .modal-content.border.border-translucent ul li.nav-item {
            /* background: #e9e9e9; */
            margin: 4px;
            border-radius: 10px;
            padding: 0px 4px;
            color: #000;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered .modal-content.border.border-translucent ul li.nav-item button {
            color: #000;
        }

        #adjustmentModal .modal-dialog.modal-dialog-centered .modal-content.border.border-translucent ul li.nav-item button.active {
            background: #3a3a3a;
            color: #fff;
            border-radius: 30px;
            padding: 8px 14px;
        }
    </style>
@endsection

@section('content')
    <nav class="mb-2" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('users')}}">Users</a></li>
            <li class="breadcrumb-item active">Users Activities</li>
        </ol>
    </nav>

    <div class="tab-content mt-3" id="myTabContent">
        <div class="mb-9">
            <div class="row g-3 mb-4">
                <div class="col-auto">
                    <h2 class="mb-0"> </h2>

                </div>
            </div>
            @php
                $fromDate = request('from_date')
                    ? Carbon\Carbon::createFromFormat('d/m/Y', request('from_date'))->startOfDay()
                    : null;
                $toDate = request('to_date')
                    ? Carbon\Carbon::createFromFormat('d/m/Y', request('to_date'))->endOfDay()
                    : null;
            @endphp

            <div id="lealsTable"
                data-list='{"valueNames":["name","email","phone","contact","company","date"],"page":10,"pagination":true}'>

                <div class="table-responsive scrollbar mx-n1 px-1">
                    <table class="table fs-9 mb-0 leads-table border-top border-translucent customize-export"
                        data-title="Products Logs" id="itemsListTable">
                        <thead style="height: 25px;">
                            <tr>
                                <th class="sort white-space-nowrap align-middle text-uppercase ps-0 border-end border-translucent"
                                    scope="col" style="width:15%;">User</th>

                                <th class="sort align-middle ps-4 pe-5 text-uppercase border-end border-translucent"
                                    scope="col" style="width:25%; min-width: 180px;">Description</th>

                                <th class="sort align-middle ps-4 pe-5 text-uppercase border-end border-translucent"
                                    scope="col" style="width:20%;">IP Address</th>

                                <th class="sort align-middle ps-4 pe-5 text-uppercase border-end border-translucent"
                                    scope="col" style="width:25%; min-width: 180px;">Properties</th>

                                <th class="sort align-middle ps-4 pe-5 text-uppercase" scope="col" style="width:15%;">
                                    Date
                                </th>
                            </tr>
                        </thead>

                        <tbody class="list productLogsList" id="leal-tables-body">
                            @forelse ($activities as $log)
                                <tr class="hover-actions-trigger btn-reveal-trigger position-static">

                                    <td class="name align-middle white-space-nowrap ps-0 border-end border-translucent">
                                        {{ optional($log->causer)->name ?? 'System' }}
                                    </td>


                                    <td
                                        class="align-middle white-space-nowrap fw-semibold ps-4 border-end border-translucent">
                                        {{ ucfirst($log->description) }}
                                    </td>


                                    <td class="align-middle fw-semibold ps-4 border-end border-translucent logsEditLists">
                                        {{ $log->properties['ip'] ?? 'N/A' }}
                                    </td>

                                    <td class="align-middle text-body-tertiary text-opacity-85 ps-4 border-end border-translucent fw-semibold text-body-highlight">
                                        <div style="max-height: 100px; overflow-y: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                            @php
                                                $properties = $log->properties->toArray();
                                            @endphp
                    
                                            @if (!empty($properties))
                                                <table class="table table-bordered table-sm m-0">
                                                    <tbody>
                                                        @foreach ($properties as $key => $value)
                                                            <tr>
                                                                <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                                                <td>
                                                                    @if (is_array($value))
                                                                        <ul class="m-0 p-0" style="list-style: none;">
                                                                            @foreach ($value as $subValue)
                                                                                <li>• {{ $subValue }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">No additional details</span>
                                            @endif
                                        </div>
                                    </td>


                                    <td class="align-middle white-space-nowrap text-body-tertiary text-opacity-85 ps-4">
                                      

                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y g:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No logs available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>



                </div>

            </div>



        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
@endsection
