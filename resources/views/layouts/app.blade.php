
    @include('partials/layouts/layoutTop')
    
    @yield('contents')
    @isset($slot)
        {{ $slot }}
    @endisset
    
    @include('partials.layouts.layoutBottom')