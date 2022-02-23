@extends('layouts.app', ['activePage' => 'institutions', 'title' => 'Consulta2 | Agregar institución', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Crear institución</h2>
                    <p>Una institución es un establecimiento que alberga prestadores de distintas áreas de la salud y atiende cada uno de sus pacientes.</p>
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
                            <label for="input-contry">País</label>
                            <select name="country" id="input-country" class="form-control"
                                onchange="getProvinces(this.value)">
                                @foreach (\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-province">Provincia</label>
                            <select name="province" id="input-province" class="form-control"
                                onchange="getCities(this.value)">
                                <option value="0">Seleccione primero el país</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-city">Ciudad:</label>
                            <select name="city" id="input-city" class="form-control" required
                                onchange="getInstitutions(this.value)">
                                <option value="0">Seleccione primero la provincia</option>
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
    <script>
        $(document).ready(function() {
                getProvinces($('#input-country option:selected').val());
                getCities($('#input-province option:selected').val());
            }

        );

        function getProvinces(value) {
            if (value != 0) {
                $.ajax({
                    method: 'GET',
                    url: '/countries/getProvinces',
                    dataType: 'json',
                    data: {
                        id: value
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.length > 0) {
                            $('#input-province').empty();
                            for (var i = 0; i < response.length; i++) {
                                $("#input-province").append($('<option/>', {
                                    value: response[i].id,
                                    text: response[i].name
                                }));
                            }
                            getCities($('#input-province option:selected').val());
                        }
                    }
                });
            }
        }

        function getCities(value) {
            if (value != 0) {
                $.ajax({
                    method: 'GET',
                    url: '/provinces/getCities',
                    dataType: 'json',
                    data: {
                        id: value
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.length > 0) {
                            $('#input-city').empty();
                            for (var i = 0; i < response.length; i++) {
                                $("#input-city").append($('<option/>', {
                                    value: response[i].id,
                                    text: response[i].name
                                }));
                            }

                        }
                    }
                });
            }
        }
    </script>
@endsection
