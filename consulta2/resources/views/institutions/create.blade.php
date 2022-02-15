@extends('layouts.app', ['activePage' => 'institutions', 'title' => 'Consulta2 | Agregar institución', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Crear institución</h2>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/institutions') }}" method="post">
                @csrf
                @method('post')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Estos son los datos del usuario que
                                administrará la cuenta del establecimiento.</p>

                            <div class="row">
                                <div class="col">
                                    <label for="user_name">Nombre del usuario a cargo</label>
                                    <input type="text" class="form-control" name="user_name" id="user_name" required>
                                </div>
                                <div class="col">
                                    <label for="user_lastname">Apellido</label>
                                    <input type="text" class="form-control" name="user_lastname" id="user_lastname" required>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col"><label for="user_dni">Número de documento</label>
                                        <input type="number" class="form-control" name="user_dni" id="user_dni" required>
                                    </div>
                                    <div class="col">
                                        <label for="user_email">Correo electrónico</label>
                                        <input type="email" class="form-control" name="user_email" id="user_email" required>
                                    </div>
                                </div>
                                <p style="font-size: 85%"><i class="fa fa-info-circle"></i>El número de documento será la contraseña inicial del usuario, que deberá cambiar inmediatamente al iniciar sesión.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="input-name">Nombre del establecimiento</label>
                            <input class="form-control" type="text" name="name" id="input-name" required>
                        </div>
                        <div class="form-group">
                            <label for="input-name">Descripción</label>
                            <input class="form-control" type="text" name="description" id="input-description"
                                placeholder="Una descripción amigable sobre lo que se realiza acá" required>
                        </div>
                        <div class="form-group">
                            <label for="input-city">Ciudad:</label>
                            <select name="city" id="input-city" class="form-control" required>
                                @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-address">Dirección</label>
                            <input class="form-control" type="text" name="address" id="input-address" required>
                        </div>
                        <div class="form-group">
                            <label for="input-phone">Teléfono</label>
                            <input class="form-control" type="text" name="phone" id="input-phone"
                                placeholder="+543764466666" required>
                        </div>
                        <hr>
                        <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Para personalizar el perfil y comenzar a asociar perfiles profesionales, debe iniciar sesión en su cuenta.</p>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/institutions') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
    </div>
    </div>
@endsection
