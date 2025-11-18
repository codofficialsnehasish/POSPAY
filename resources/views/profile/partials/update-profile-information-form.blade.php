{{-- Profile Information --}}
<div class="card border-0 shadow radius-12 mb-4">
    <div class="card-body p-4">

        <h4 class="fw-semibold mb-3">Profile Information</h4>
        <p class="text-muted mb-4">
            Update your account’s profile information and email address.
        </p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            {{-- Name --}}
            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="mdi:user"></iconify-icon>
                </span>
                <input type="text" name="name"
                    class="form-control h-56-px bg-neutral-50 radius-12"
                    value="{{ old('name', $user->name) }}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Email --}}
            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="mage:email"></iconify-icon>
                </span>
                <input type="email" name="email"
                    class="form-control h-56-px bg-neutral-50 radius-12"
                    value="{{ old('email', $user->email) }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="text-warning small mt-2">Your email address is unverified.</p>

                <button form="send-verification" class="btn btn-link p-0 text-primary">
                    Click here to resend verification email
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="small text-success mt-2">
                        A new verification link has been sent to your email.
                    </p>
                @endif
            @endif

            <button type="submit" class="btn btn-primary w-100 radius-12 py-2 mt-3">
                Save
            </button>

        </form>
    </div>
</div>