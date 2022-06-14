@extends('layouts.app', ['activePage' => 'professionals', 'title' => $companyName.' | Agregar profesional', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Crear perfil profesional</h2>
                    @if (Auth::user()->hasPermission('institution-profile'))
                        <p>Agrega un prestador a la institución.</p>
                    @else
                        <p>Agrega a un nuevo prestador al sistema.</p>
                    @endif
                </div>

                @include('alerts.errors')

            </div>
            @php
                $basecity = 0;
            @endphp
            <form id="create-form" class="form-horizontal" action="{{ route('admin.professionals.store') }}" method="post">

                @csrf
                @method('post')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Estos son los datos del usuario que
                                prestará el servicio profesional.</p>

                            <div class="row">
                                <div class="col">
                                    <label for="user_name">Nombre</label>
                                    <input type="text" class="form-control" name="user_name" id="user_name" required>
                                </div>
                                <div class="col">
                                    <label for="user_lastname">Apellido</label>
                                    <input type="text" class="form-control" name="user_lastname" id="user_lastname"
                                        required>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col"><label for="user_dni">Número de documento</label>
                                        <input type="number" class="form-control" name="user_dni" id="user_dni" required>
                                    </div>
                                    <div class="col">
                                        <label for="user_email">Correo electrónico</label>
                                        <input type="email" class="form-control" name="user_email" id="user_email"
                                            required>
                                    </div>
                                </div>
                                <p style="font-size: 85%"><i class="fa fa-info-circle"></i>El número de documento será la
                                    contraseña inicial del usuario, que deberá cambiar inmediatamente al iniciar sesión.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="input-bornDate">Fecha de nacimiento</label>
                            <input class="form-control" type="date"
                                max="{{ date('Y-m-d', strtotime(now() . '- 18 year')) }}"
                                value="{{ date('Y-m-d', strtotime(now() . '- 18 year')) }}" name="bornDate"
                                id="input-bornDate" required>
                        </div>
                        <div class="form-group">
                            <label for="input-gender">Género</label>
                            <select name="gender" id="input-gender" class="form-control" required>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-contry">País</label>
                            <select name="country" id="input-country" class="form-control"
                                onchange="getProvinces(this.value)">
                                @foreach (\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
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
                        <div class="form-group">
                            <label for="input-licensePlate">Matrícula</label>
                            <input class="form-control" type="text" name="licensePlate" id="input-licensePlate" required>
                        </div>
                        <div class="form-group">
                            <label for="input-specialty">Especialidad:</label>
                            <select name="specialty" id="input-specialty" class="form-control" required>
                                @foreach (\App\Models\Specialty::all() as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->displayname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-field">Especialización/Rubro específico</label>
                            <input class="form-control" type="text" name="field" id="input-field"
                                placeholder="Por ejemplo: Psicología laboral." required>
                        </div>
                        <div class="form-group">
                            <label for="input-institution_id">Establecimiento donde trabaja:</label>
                            <select name="institution_id" id="input-institution_id" class="form-control">
                                <option value="0">Seleccione primero la ciudad</option>
                            </select>
                        </div>
                        <hr>
                        <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Para personalizar el perfil y comenzar a
                            recibir turnos, debe iniciar sesión en su cuenta y configurar sus horarios de atención.</p>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/manage/professionals') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
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
                            getInstitutions($('#input-city option:selected').val());

                        }
                    }
                });
            }
        }

        function getInstitutions(value) {
            if (value != 0) {
                $.ajax({
                    method: 'GET',
                    url: '/cities/getInstitutions',
                    dataType: 'json',
                    data: {
                        id: value
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.length > 0) {
                            $('#input-institution_id').empty();
                            $("#input-institution_id").append($('<option/>', {
                                value: 1,
                                text: "Independiente"
                            }));
                            for (var i = 0; i < response.length; i++) {
                                if (response[i].id != 1) {
                                    $("#input-institution_id").append($('<option/>', {
                                        value: response[i].id,
                                        text: response[i].name
                                    }));
                                }

                            }

                        }
                    }
                });
            }
        }
    </script>
@endsection
