@if (auth()->check() &&
    request()->route()->getName() != null || in_array($activePage, $wtfpages))
    @include('layouts.navbars.navs.auth')
@else
    @include('layouts.navbars.navs.guest')
@endif
