@extends('layouts.app')

@section('title', 'Profile')

@section('contents')

<div class="container py-5">

    <!-- Profile Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="profile-header bg-primary text-white rounded-3 p-4 shadow position-relative overflow-hidden">
                <div class="d-flex align-items-center">
                    <div class="profile-avatar me-4" style="margin-left: 30px;">
                        <i class="bi bi-person-fill"></i>
                        <img src="{{ asset('assets/dashboard-assets/images/user.png') }}" class="rounded-circle" alt="User">
                    </div>
                    <div style="margin-left: 30px;">
                        <h3 class="h3 fw-bold mb-1">{{ $user->name }}</h3>
                        <p class="mb-0 opacity-75">{{ $user->email }}</p>
                        <span class="badge bg-light text-primary mt-2">Member since {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary text-white me-3">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Profile Information</h5>
                            <p class="text-muted small mb-0">Update your account's profile details</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" name="name" 
                                       class="form-control border-start-0 ps-0"
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       placeholder="Enter your full name">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email" 
                                       class="form-control border-start-0 ps-0"
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       placeholder="Enter your email address">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <button class="btn btn-primary w-100 py-2 mt-3 fw-semibold">
                            <i class="bi bi-check-circle me-2"></i>
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-warning text-white me-3">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Update Password</h5>
                            <p class="text-muted small mb-0">Use a strong password to stay secure</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="current_password" 
                                       class="form-control border-start-0 ps-0"
                                       placeholder="Enter current password">
                            </div>
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-shield-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" 
                                       class="form-control border-start-0 ps-0"
                                       placeholder="Enter new password">
                            </div>
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-shield-check text-muted"></i>
                                </span>
                                <input type="password" name="password_confirmation" 
                                       class="form-control border-start-0 ps-0"
                                       placeholder="Confirm new password">
                            </div>
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button class="btn btn-warning text-dark w-100 py-2 mt-3 fw-semibold">
                            <i class="bi bi-key me-2"></i>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        {{-- <div class="col-12">
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-danger text-white me-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                            <p class="text-muted small mb-0">Once you delete your account, it cannot be recovered</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Permanently remove your account and all associated data</p>
                        </div>
                        <button class="btn btn-outline-danger px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
</div>

<!-- Delete Account Modal -->
{{-- <div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="modal-header border-0">
                <div class="icon-circle bg-danger text-white me-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="modal-title text-danger fw-bold">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-3">
                    This action is permanent and cannot be undone. All your data will be permanently deleted. 
                    Please enter your password to confirm.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Enter your password to confirm">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</div> --}}

<style>
.profile-header {
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.profile-header::after {
    content: '';
    position: absolute;
    bottom: -50px;
    left: -50px;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255, 255, 255, 0.3);
    color: #4361ee;
    font-size: 2rem;
}

.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.form-control:focus {
    box-shadow: none;
    border-color: #4361ee;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #4361ee;
    border-color: #4361ee;
}

.btn-primary:hover {
    background-color: #3a56d4;
    border-color: #3a56d4;
    transform: translateY(-1px);
}
</style>

@endsection