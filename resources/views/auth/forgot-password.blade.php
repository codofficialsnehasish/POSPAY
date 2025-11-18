@section('title','Forgot Password')
<x-guest-layout>

    <div class="mb-16 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just enter your email and we will send you a password reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="icon-field mb-16">
            <span class="icon top-50 translate-middle-y">
                <iconify-icon icon="mage:email"></iconify-icon>
            </span>
            <input 
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="form-control h-56-px bg-neutral-50 radius-12"
                placeholder="Email"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"
        >
            Send Password Reset Link
        </button>

    </form>

</x-guest-layout>
