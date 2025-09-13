@extends('layouts.app')

@section('title', 'Branch and Seat Number Edit')

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Edit Branch and Seat Number</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Edit Branch and Seat Number</li>
            </ul>
        </div>

        <form action="{{ route('seatnumber.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Branch And Seat Number Details</h5>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="coach_id" id="coach_id" class="form-control"
                                value="{{ $coach->id }}">
                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendors</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            @if (old('vendor_id', $coach->vendor_id) == $vendor->id) selected @endif>{{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3" id="new_coach_section">
                                <label for="new_coach_name" class="form-label">Branch Name (e.g. D1)</label>
                                <input type="text" name="coach_name" id="coach_name" class="form-control" maxlength="10"
                                    value="{{ $coach->name }}">
                                @error('new_coach_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label for="coach_id" class="form-label">Coach</label>
                                <select name="coach_id" id="coach_id" class="form-control" required>
                                    <option value="">Select Coach</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}"
                                            @if (old('coach_id', $coach->id) == $coach->id) selected @endif>{{ $coach->name }}</option>
                                    @endforeach
                                </select>
                                @error('coach_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="mb-3">
                                <label class="form-label">Choose Method</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="method" id="auto_method"
                                        value="auto" checked>
                                    <label class="form-check-label" for="auto_method">Auto Generate</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="method" id="manual_method"
                                        value="manual">
                                    <label class="form-check-label" for="manual_method">Manual Entry</label>
                                </div>
                            </div>

                            <!-- Auto Generate Section -->
                            <div id="auto_section">
                                <div class="mb-3">
                                    <label for="seat_prefix" class="form-label">Seat Prefix (e.g. C)</label>
                                    <input type="text" name="seat_prefix" id="seat_prefix" class="form-control"
                                        maxlength="5">
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="start" class="form-label">Start Number</label>
                                        <input type="number" name="start" id="start" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="end" class="form-label">End Number</label>
                                        <input type="number" name="end" id="end" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Entry Section -->
                            <div id="manual_section" style="display: none;">
                                <div class="mb-3">
                                    <label for="manual_seats" class="form-label">Enter Seat Numbers (comma
                                        separated)</label>
                                    <textarea name="manual_seats" id="manual_seats" class="form-control" rows="3" placeholder="E.g. BC14, FB9, Z01"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-3">

                    <div class="card">
                        <div class="card-header">Publish</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Visibility</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="is_visible" class="form-check-input"
                                        value="1" {{ check_uncheck($data->is_visible, 1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Visible</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="is_visible"
                                        class="form-check-input" value="0" {{ check_uncheck($data->is_visible, 0) }}>
                                    <label class="form-check-label" for="customRadioInline2">Invisible</label>
                                </div>
                            </div>
                            <div class="md-3">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="md-3">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-info">Save</button>
            </div>

        </form>
        <hr>

        <h4 class="mt-4">Existing Seat Numbers</h4>
        @if ($seats->isEmpty())
            <p>No seat numbers found for this coach.</p>
        @else
            <div class="row">
                @foreach ($seats as $seat)
                    <div class="col-md-2 col-sm-3 col-4 mb-2">
                        <div class="badge bg-secondary p-2 w-100 text-center">{{ $seat->name }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script>
        const autoRadio = document.getElementById('auto_method');
        const manualRadio = document.getElementById('manual_method');
        const autoSection = document.getElementById('auto_section');
        const manualSection = document.getElementById('manual_section');

        autoRadio.addEventListener('change', toggleSections);
        manualRadio.addEventListener('change', toggleSections);

        function toggleSections() {
            if (autoRadio.checked) {
                autoSection.style.display = 'block';
                manualSection.style.display = 'none';
            } else {
                autoSection.style.display = 'none';
                manualSection.style.display = 'block';
            }
        }
    </script>
@endsection
