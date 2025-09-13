@section('title','Login')

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="icon-field mb-16">
            <span class="icon top-50 translate-middle-y">
                <iconify-icon icon="mage:email"></iconify-icon>
            </span>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="position-relative mb-20">
            <div class="icon-field">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password"
                name="password"
                required autocomplete="current-password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
        </div>
        <div class="">
            <div class="d-flex justify-content-between gap-2">
                <div class="form-check style-check d-flex align-items-center">
                    <input class="form-check-input border border-neutral-300" type="checkbox" value="" id="remeber">
                    <label class="form-check-label" for="remeber">Remember me </label>
                </div>
                {{-- <a href="javascript:void(0)" class="text-primary-600 fw-medium">Forgot Password?</a> --}}
            </div>
        </div>
    
        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"> Sign In</button>
    
        {{-- <div class="mt-32 text-center text-sm">
            <p class="mb-0">Don’t have an account? <a href="{{ route('register') }}" class="text-primary-600 fw-semibold">Sign Up</a></p>
        </div> --}}
    
    </form>
</x-guest-layout>
       