<div class="sidebar" data-image="{{ asset('light-bootstrap/img/sidebar-5.jpg') }}">
    <!--
Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

Tip 2: you can also add an image using data-image tag
-->
    <div class="sidebar-wrapper" style="background-color: black">
        <div class="logo">
            <a href="/home" class="simple-text">
                {{ __("Consulta2") }} 
            </a>
        </div>
        <ul class="nav">
            <li class="nav-item @if($activePage == 'professional_index') active @endif">
                <a class="nav-link" href="{{route('professional.index')}}">
                    <i class="nc-icon nc-paper-2"></i>
                    <p>{{ __("Agendar turno") }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laravelExamples" @if($activeButton =='laravel') aria-expanded="true" @endif>
                    <i>
                        <img src="{{ asset('light-bootstrap/img/laravel.svg') }}" style="width:25px">
                    </i>
                    <p>
                        {{ __('Mi cuenta') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse @if($activeButton =='laravel') show @endif" id="laravelExamples">
                    <ul class="nav">
                        <li class="nav-item @if($activePage == 'user') active @endif">
                            <a class="nav-link" href="{{route('profile.edit')}}">
                                <i class="nc-icon nc-single-02"></i>
                                <p>{{ __("Cuenta") }}</p>
                            </a>
                        </li>
                        <li class="nav-item @if($activePage == 'infoedit') active @endif">
                            <a class="nav-link" href="{{route('profile.infoedit')}}">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>{{ __("Perfil") }}</p>
                            </a>
                        </li>
                        @if (Auth::user()->isAbleTo('_consulta2_patient_profile_perm'))
                        <li class="nav-item @if($activePage == 'user-management') active @endif">
                            <a class="nav-link" href="{{route('profile.lifesheet')}}">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>{{ __("Hoja de vida") }}</p>
                            </a>
                        </li>
                        @endif
                        
                        
                    </ul>
                </div>
            </li>
            @if (!Auth::user()->isAbleTo('_consulta2_patient_profile_perm'))
            <li class="nav-item @if($activePage == 'cites') active @endif">
                <a class="nav-link" href="{{route('cite.index')}}">
                    <i class="nc-icon nc-notes"></i>
                    <p>{{ __("Sesiones") }}</p>
                </a>
            </li>
            @endif
            @if (auth()->user()->role_id < 3)
            <li class="nav-item @if($activePage == 'dashboard') active @endif">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="nc-icon nc-chart-pie-35"></i>
                    <p>{{ __("Estadísticas") }}</p>
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
