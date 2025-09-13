@extends('layouts.app')

@section('title', 'Branch and Seat Number Create')

@section('contents')

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Branch And Seat Number Create</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="i{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Branch And Seat Number Create</li>
            </ul>
        </div>

        <form action="{{ route('seatnumber.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Branch And Seat Number Details</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendors</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            @if (old('vendor_id') == $vendor->id) selected @endif>{{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Branch Option</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coach_option" id="choose_existing"
                                        value="existing" checked>
                                    <label class="form-check-label" for="choose_existing">Choose Existing Branch</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coach_option" id="add_new"
                                        value="new">
                                    <label class="form-check-label" for="add_new">Add New Branch</label>
                                </div>
                            </div>

                            <!-- Existing Coach Dropdown -->
                            <div class="mb-3" id="existing_coach_section">
                                <label for="coach_id" class="form-label">Select Branch</label>
                                <select name="coach_id" id="coach_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                    @endforeach
                                </select>
                                @error('coach_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New Coach Input -->
                            <div class="mb-3" id="new_coach_section" style="display: none;">
                                <label for="new_coach_name" class="form-label">New Branch Name (e.g. D1)</label>
                                <input type="text" name="new_coach_name" id="new_coach_name" class="form-control"
                                    maxlength="10">
                                @error('new_coach_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

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
                                    <textarea name="manual_seats" id="manual_seats" class="form-control" rows="3"
                                        placeholder="E.g. BC14, FB9, Z01"></textarea>
                                </div>
                            </div>

                            {{-- <div class="mb-3">
                                <label for="seat_prefix" class="form-label">Seat Prefix (e.g. C)</label>
                                <input type="text" name="seat_prefix" id="seat_prefix" class="form-control"
                                    maxlength="5" required>
                                @error('seat_prefix')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="start" class="form-label">Start Number</label>
                                    <input type="number" name="start" id="start" class="form-control" required>
                                    @error('start')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="end" class="form-label">End Number</label>
                                    <input type="number" name="end" id="end" class="form-control" required>
                                    @error('end')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>
            <div class="md-3">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-info">Save</button>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script>
        // ================================================ Upload image & show it\'s name js end ================================================
    </script>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chooseExisting = document.getElementById('choose_existing');
            const addNew = document.getElementById('add_new');
            const existingSection = document.getElementById('existing_coach_section');
            const newSection = document.getElementById('new_coach_section');

            function toggleCoachFields() {
                if (chooseExisting.checked) {
                    existingSection.style.display = 'block';
                    newSection.style.display = 'none';
                } else {
                    existingSection.style.display = 'none';
                    newSection.style.display = 'block';
                }
            }

            chooseExisting.addEventListener('change', toggleCoachFields);
            addNew.addEventListener('change', toggleCoachFields);
        });
    </script>

@endsection
