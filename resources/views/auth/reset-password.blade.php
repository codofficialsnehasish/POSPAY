@section('title','Reset Password')
<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Hidden Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="icon-field mb-16">
            <span class="icon top-50 translate-middle-y">
                <iconify-icon icon="mage:email"></iconify-icon>
            </span>
            <input 
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="form-control h-56-px bg-neutral-50 radius-12"
                placeholder="Email"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="position-relative mb-20">
            <div class="icon-field">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>

                <input 
                    type="password"
                    name="password"
                    id="new-password"
                    required
                    autocomplete="new-password"
                    class="form-control h-56-px bg-neutral-50 radius-12"
                    placeholder="New Password"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#new-password"></span>
        </div>

        <!-- Confirm Password -->
        <div class="position-relative mb-20">
            <div class="icon-field">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>

                <input 
                    type="password"
                    name="password_confirmation"
                    id="confirm-password"
                    required
                    autocomplete="new-password"
                    class="form-control h-56-px bg-neutral-50 radius-12"
                    placeholder="Confirm Password"
                >
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#confirm-password"></span>
        </div>

        <!-- Button -->
        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
            Reset Password
        </button>

    </form>

</x-guest-layout>
