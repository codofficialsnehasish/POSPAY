{{-- Update Password --}}
<div class="card border-0 shadow radius-12 mb-4">
    <div class="card-body p-4">

        <h4 class="fw-semibold mb-3">Update Password</h4>
        <p class="text-muted mb-4">
            Ensure your account is protected with a secure password.
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            {{-- Current Password --}}
            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" name="current_password"
                        class="form-control h-56-px bg-neutral-50 radius-12"
                        placeholder="Current Password" autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            {{-- New Password --}}
            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" name="password"
                        class="form-control h-56-px bg-neutral-50 radius-12"
                        placeholder="New Password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" name="password_confirmation"
                        class="form-control h-56-px bg-neutral-50 radius-12"
                        placeholder="Confirm Password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="btn btn-primary w-100 radius-12 py-2 mt-3">
                Save
            </button>

        </form>
    </div>
</div>