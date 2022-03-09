@extends('layouts.app', ['activePage' => 'register', 'title' => $companyName.' | Crear cuenta'])

@section('content')
    <div class="full-page register-page section-image" data-color="black" data-image="{{ asset('light-bootstrap/img/bg5.jpg') }}">
        <div class="content">
            <div class="container">
                <div class="card card-register card-plain text-center" style="background-color: #444">
                    <div class="card-body ">
                        <h3 style="color:#eee">Registrarse</h3>
                        <div class="row">
                            <div    class="col-md-4 ml-1">

                            </div>
                            <div class="col-md-4 m-auto align-center" >
                                <form method="POST" action="{{ route('register') }}" >
                                    @csrf
                                    <div class="card card-plain" style="background-color: #444">
                                        <div class="content">
                                            <div class="form-group">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Nombre(s)') }}" value="{{ old('name') }}" required autofocus>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="{{ __('Apellido(s)') }}" value="{{ old('lastname') }}" required autofocus>
                                            </div>
                                            <div class="form-group">
                                                <input type="number" name="dni" id="dni" class="form-control" placeholder="{{ __('N° Documento') }}" value="{{ old('dni') }}" required autofocus>
                                            </div>

                                            <div class="form-group">   {{-- is-invalid make border red --}}
                                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control" required placeholder="Contraseña">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" class="form-control" required autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label for="type">Yo soy...</label>
                                                <select class="form-control" name="type">
                                                    <option value="0">Paciente</option>
                                                    <option value="1">Profesional</option>
                                                </select>
                                            </div>
                                            <div class="form-group d-flex justify-content-center">
                                                <div class="form-check rounded col-md-10 text-left">
                                                    <label class="form-check-label text-white d-flex align-items-center">
                                                        <input class="form-check-input" name="agree" type="checkbox" required >
                                                        <span class="form-check-sign"></span>
                                                        <b>{{ __('Aceptar términos y condiciones') }}</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="footer text-center">
                                                <button type="submit" class="btn btn-fill btn-neutral btn-wd">{{ __('Crear la cuenta') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col">
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-warning alert-dismissible fade show" >
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close"> &times;</a>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();

            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700)
        });
    </script>
@endpush