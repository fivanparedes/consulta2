<div class="sidebar" data-image="{{ asset('light-bootstrap/img/sidebar-5.jpg') }}">
    <!--
Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

Tip 2: you can also add an image using data-image tag
-->
    <div class="sidebar-wrapper" style="background-color: black">
        <div class="logo">
            <a href="/home" class="simple-text">
                {{ __('Consulta2') }}
            </a>
        </div>

        <ul class="nav">
            @if (Auth::user()->isAbleTo('patient-profile'))
                <li class="nav-item @if ($activePage == 'professional_index') active @endif">
                    <a class="nav-link" href="{{ route('professional.index') }}">
                        <i class="nc-icon nc-paper-2"></i>
                        <p>{{ __('Agendar turno') }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->isAbleTo('patient-profile'))
                <li class="nav-item @if ($activePage == 'patient_events') active @endif">
                    <a class="nav-link" href="{{ route('profile.events') }}">
                        <i class="nc-icon nc-notes"></i>
                        <p>{{ __('Mis turnos') }}</p>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laravelExamples" @if ($activeButton == 'laravel') aria-expanded="true" @endif>
                    <i>
                        <img src="{{ asset('light-bootstrap/img/laravel.svg') }}" style="width:25px">
                    </i>
                    <p>
                        {{ __('Mi cuenta') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse @if ($activeButton == 'laravel') show @endif" id="laravelExamples">
                    <ul class="nav">
                        <li class="nav-item @if ($activePage == 'user') active @endif">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="nc-icon nc-single-02"></i>
                                <p>{{ __('Cuenta') }}</p>
                            </a>
                        </li>
                        <li class="nav-item @if ($activePage == 'infoedit') active @endif">
                            <a class="nav-link" href="{{ route('profile.infoedit') }}">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>{{ __('Perfil') }}</p>
                            </a>
                        </li>
                        @if (Auth::user()->isAbleTo('patient-profile'))
                            <li class="nav-item @if ($activePage == 'user-management') active @endif">
                                <a class="nav-link" href="{{ route('profile.lifesheet') }}">
                                    <i class="nc-icon nc-circle-09"></i>
                                    <p>{{ __('Hoja de vida') }}</p>
                                </a>
                            </li>
                        @endif


                    </ul>
                </div>
            </li>
            @if (Auth::user()->isAbleTo('patient-profile'))
                <li class="nav-item @if ($activePage == 'medical_histories') active @endif">
                                <a class="nav-link" href="{{ url('/medical_history') }}">
                                    <i class="nc-icon nc-circle-09"></i>
                                    <p>{{ __('Mi historia clínica') }}</p>
                                </a>
                            </li>
            @endif
            @if ((Auth::user()->isAbleTo('professional-profile') || Auth::user()->isAbleTo('institution-profile') || Auth::user()->isAbleTo('admin-profile'))  && Auth::user()->isAbleTo('CiteController@index'))
                <li class="nav-item @if ($activePage == 'cites') active @endif">
                    <a class="nav-link" href="{{ route('cite.index') }}">
                        <i class="nc-icon nc-notes"></i>
                        <p>{{ __('Sesiones') }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->isAbleTo('professional-profile') || Auth::user()->isAbleTo('institution-profile'))
                <li class="nav-item @if ($activePage == 'treatments') active @endif">
                    <a class="nav-link" href="{{ route('treatments.index') }}">
                        <i class="nc-icon nc-notes"></i>
                        <p>{{ __('Tratamientos') }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->isAbleTo('professional-profile'))
                <li class="nav-item @if ($activePage == 'consult_types') active @endif">
                    <a class="nav-link" href="{{ route('consult_types.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Horarios') }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->isAbleTo('professional-profile') || Auth::user()->isAbleTo('institution-profile') )
                <li class="nav-item @if ($activePage == 'non_workable_days') active @endif">
                    <a class="nav-link" href="{{ route('non_workable_days.index') }}">
                        <i class="nc-icon nc-single-02"></i>
                        <p>{{ __('Días no laborables') }}</p>
                    </a>
                </li>
                
            @endif
            @if (Auth::user()->isAbleTo('professional-profile') || Auth::user()->isAbleTo('institution-profile'))
                <li class="nav-item @if ($activePage == 'dashboard') active @endif">
                    <a class="nav-link" href="{{ url('/statistics') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Estadísticas') }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->isAbleTo('admin-profile') || Auth::user()->isAbleTo('institution-profile'))
                <li class="nav-item @if ($activePage == 'roles_permissions') active @endif">
                    <a class="nav-link" href="{{ route('laratrust.roles-assignment.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Roles y Permisos') }}</p>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'audits') active @endif">
                    <a class="nav-link" href="{{ route('admin.audits') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Auditoría') }}</p>
                    </a>
                </li>
            @endif

             @if (Auth::user()->isAbleTo('admin-profile') || Auth::user()->isAbleTo('institution-profile') || Auth::user()->isAbleTo('professional-profile'))
             <li class="nav-item @if ($activePage == 'patients') active @endif">
                    <a class="nav-link" href="{{ route('patients.index') }}">
                        <i class="nc-icon nc-single-02"></i>
                        <p>{{ __('Pacientes') }}</p>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'institutions') active @endif">
                    <a class="nav-link" href="{{ route('institutions.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Instituciones') }}</p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAbleTo('admin-profile') || Auth::user()->isAbleTo('institution-profile'))
                <li class="nav-item @if ($activePage == 'professionals') active @endif">
                    <a class="nav-link" href="{{ route('admin.professionals') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Profesionales') }}</p>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'practices') active @endif">
                    <a class="nav-link" href="{{ route('practices.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Gestionar prácticas') }}</p>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'nomenclatures') active @endif">
                    <a class="nav-link" href="{{ route('nomenclatures.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Nomenclador') }}</p>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'coverages') active @endif">
                    <a class="nav-link" href="{{ route('coverages.index') }}">
                        <i class="nc-icon nc-chart-pie-35"></i>
                        <p>{{ __('Obras sociales') }}</p>
                    </a>
                </li>

            @endif

            {{-- <li class="nav-item">
                <a class="nav-link active bg-danger" href="{{route('page.index', 'upgrade')}}">
                    <i class="nc-icon nc-alien-33"></i>
                    <p>{{ __("Upgrade to PRO") }}</p>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
