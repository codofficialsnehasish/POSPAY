<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('partials.head')

<body>
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/dashboard-assets/images/auth/auth-img.png') }}" alt="">
                {{-- <img src="{{ asset('assets/dashboard-assets/images/auth/1940.jpg') }}" alt=""> --}}
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;text-align: center;">
                    <a href="{{ route('dashboard') }}" class="mb-40 max-w-290-px">
                        <img src="{{ asset('assets/dashboard-assets/images/web-logo.png') }}" alt="">
                        
                        {{-- <h4>YOUR LOGO</h4> --}}
                    </a>
                    <h4 class="mb-12">Sign In to your Account</h4>
                    <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </section>
    
                    
    @include('partials.scripts')
    <script>
        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================
    </script>
</body>

</html>