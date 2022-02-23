<nav class="navbar navbar-expand-lg " color-on-scroll="500">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"> {{ $navName }} </a>
        <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        <i class="nc-icon nc-palette"></i>
                        <span class="d-lg-none">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if (Auth::user()->hasRole('Patient'))
                    <li class="dropdown nav-item">

                        {{-- <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <i class="nc-icon nc-planet"></i>
                            <span
                                class="notification">{{ Auth::user()->reminders->where('sent', '<>', null)->count() }}</span>
                            <span class="d-lg-none">{{ __('Notificaciones') }}</span>
                        </a> --}}
                        <ul class="dropdown-menu">
                            @if (Auth::user()->reminders->where('sent', '<>', null)->count() == 0)
                                <p class="nav-item">No hay recordatorios</p>
                            @else
                                @foreach (Auth::user()->reminders as $reminder)
                                    @if ($reminder->answered != null)
                                        <a class="dropdown-item"
                                            href="/profile/events/{{ $reminder->calendarEvent->id }}">{{ $reminder->calendarEvent->consultType->name . ' ' . date_create($reminder->calendarEvent->start)->format('d/m/Y h:i') }}</a>
                                    @else
                                        <a class="dropdown-item font-weight-bold"
                                            href="/profile/events/{{ $reminder->calendarEvent->id }}">{{ $reminder->calendarEvent->consultType->name . ' ' . date_create($reminder->calendarEvent->start)->format('d/m/Y h:i') }}</a>
                                    @endif

                                @endforeach

                            @endif

                        </ul>
                    </li>
                @elseif (Auth::user()->hasRole('Professional'))
                    <li class="dropdown nav-item">

                       {{--  <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <i class="nc-icon nc-planet"></i>
                            <span
                                class="notification">{{ Auth::user()->profile->professionalProfile->calendarEvents->where('approved', 0)->count() }}</span>
                            <span class="d-lg-none">{{ __('Notificaciones') }}</span>
                        </a> --}}
                        {{-- <ul class="dropdown-menu">
                            @if (Auth::user()->profile->professionalProfile->calendarEvents->where('approved', 0)->count() == 0)
                                <p class="nav-item">No hay recordatorios</p>
                            @else
                                @foreach (Auth::user()->profile->professionalProfile->calendarEvents as $calendarEvent)
                                    @if ($calendarEvent->approved != 0)
                                        <a class="dropdown-item"
                                            href="/cite/{{ $calendarEvent->cite->id }}">{{ $calendarEvent->consultType->name . ' ' . date_create($calendarEvent->start)->format('d/m/Y h:i') }}</a>
                                    @else
                                        <a class="dropdown-item font-weight-bold"
                                            href="/cite/{{ $calendarEvent->cite->id }}">{{ $calendarEvent->consultType->name . ' ' . date_create($calendarEvent->start)->format('d/m/Y h:i') }}</a>
                                    @endif

                                @endforeach

                            @endif

                        </ul> --}}
                    </li>
                @endif

                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nc-icon nc-zoom-split"></i>
                        <span class="d-lg-block">&nbsp;{{ __('Search') }}</span>
                    </a>
                </li> --}}
            </ul>
            <ul class="navbar-nav   d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href=" {{ route('profile.edit') }} ">
                        <span class="no-icon">{{ __('Cuenta') }}</span>
                    </a>
                </li>
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="no-icon">{{ __('Dropdown') }}</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">{{ __('Action') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Another action') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Something') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Something else here') }}</a>
                        <div class="divider"></div>
                        <a class="dropdown-item" href="#">{{ __('Separated link') }}</a>
                    </div>
                </li> --}}
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a class="text-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Cerrar sesión') }} </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
