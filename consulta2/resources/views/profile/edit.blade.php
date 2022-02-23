@extends('layouts.app', ['activePage' => 'user', 'title' => 'Consulta2 | Configuración de cuenta', 'navName' => 'Mi
cuenta', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-8">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ __('Configuración') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de cuenta') }}</h6>

                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Nombre(s)') }}
                                        </label>
                                        <input type="text" name="name" id="input-name"
                                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Nombre') }}"
                                            value="{{ old('name', auth()->user()->name) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'name'])
                                    </div>
                                    <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-lastname">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Apellido(s)') }}
                                        </label>
                                        <input type="text" name="lastname" id="input-lastname"
                                            class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Apellido') }}"
                                            value="{{ old('lastname', auth()->user()->lastname) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'lastname'])
                                    </div>
                                    <div class="form-group{{ $errors->has('dni') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-dni">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Número de documento') }}
                                        </label>
                                        <input type="text" name="dni" id="input-dni"
                                            class="form-control{{ $errors->has('dni') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('N° Doc') }}"
                                            value="{{ old('dni', auth()->user()->dni) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'name'])
                                    </div>
                                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-email"><i
                                                class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Email') }}</label>
                                        <input type="email" name="email" id="input-email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Email') }}"
                                            value="{{ old('email', auth()->user()->email) }}" required>

                                        @include('alerts.feedback', ['field' => 'email'])
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-default mt-4">{{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </form>
                            <hr class="my-4" />
                            <form method="post" action="{{ route('profile.password') }}">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Contraseña') }}</h6>

                                @include('alerts.success', ['key' => 'password_status'])
                                @include('alerts.error_self_update', ['key' => 'not_allow_password'])

                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-current-password">
                                            <i class="w3-xxlarge fa fa-eye-slash"></i>{{ __('Contraseña actual') }}
                                        </label>
                                        <input type="password" name="old_password" id="input-current-password"
                                            class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Contraseña actual') }}" value="" required>

                                        @include('alerts.feedback', ['field' => 'old_password'])
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-password">
                                            <i class="w3-xxlarge fa fa-eye-slash"></i>{{ __('Nueva contraseña') }}
                                        </label>
                                        <input type="password" name="password" id="input-password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Contraseña nueva') }}" value="" required>

                                        @include('alerts.feedback', ['field' => 'password'])
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-password-confirmation">
                                            <i
                                                class="w3-xxlarge fa fa-eye-slash"></i>{{ __('Confirmar la nueva contraseña') }}
                                        </label>
                                        <input type="password" name="password_confirmation" id="input-password-confirmation"
                                            class="form-control" placeholder="{{ __('Confirmar contraseña') }}"
                                            value="" required>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-default mt-4">{{ __('Cambiar contraseña') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-user">
                            <div class="card-image">

                            </div>
                            <div class="card-body">
                                <div class="author">
                                    <a href="#">
                                        <img class="avatar border-gray"
                                            src="{{ auth()->user()->pfp != '' ? asset('/storage/images/'.explode('/',auth()->user()->pfp)[2]) : asset('light-bootstrap/img/default-avatar.png') }}"
                                            alt="...">
                                        <h5 class="title">
                                            {{ auth()->user()->lastname . ' ' . auth()->user()->name }}</h5>
                                    </a>
                                </div>
                                <p class="description text-center">
                                <div class="col-md-12">
                                    <form action="{{ url('/profile/pfp') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <div class="form-group">
                                        <input type="file" name="image" placeholder="Elegir imagen" id="image" class="form-control" onchange="allowButton()">
                                        @error('image')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group text-center"><button id="pfp-submit" type="submit" class="btn bg-primary text-light" disabled>Actualizar</button></div>
                                    </form>
                                </div>
                                </p>
                            </div>
                            <hr>
                            <div class="button-container mr-auto ml-auto">
                               <label for="">Subir una foto de perfil formal de usted</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function allowButton() {
            $('#pfp-submit').prop('disabled', false);
        }
    </script>
@endsection
