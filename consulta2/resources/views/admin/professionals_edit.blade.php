@extends('layouts.app', ['activePage' => 'professionals', 'title' => 'Consulta2 | Editar profesional', 'navName' =>
'Detalles de sesión', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-0">
                                        {{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname }}
                                    </h3>
                                    <p>Matrícula: {{ $professional->licensePlate }}</p>
                                    <p>DNI: {{ $professional->profile->user->dni }}</p>
                                    <p>Especialización: {{ $professional->specialty->displayname }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/manage/professionals/update/{{ $professional->id }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>

                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                                <div class="pl-lg-4">
                                    <div class="form-group">
                                        <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Estos son los datos del
                                            usuario que prestará el servicio profesional.</p>

                                        <div class="row">
                                            <div class="col">
                                                <label for="user_name">Nombre</label>
                                                <input type="text" class="form-control" name="user_name" id="user_name"
                                                    value="{{ $professional->profile->user->name }}" required>
                                            </div>
                                            <div class="col">
                                                <label for="user_lastname">Apellido</label>
                                                <input type="text" class="form-control" name="user_lastname"
                                                    value="{{ $professional->profile->user->lastname }}"
                                                    id="user_lastname" required>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col"><label for="user_dni">Número de
                                                        documento</label>
                                                    <input type="number" class="form-control" name="user_dni"
                                                        value="{{ $professional->profile->user->dni }}" id="user_dni"
                                                        required>
                                                </div>
                                                <div class="col">
                                                    <label for="user_email">Correo electrónico</label>
                                                    <input type="email" class="form-control" name="user_email"
                                                        value="{{ $professional->profile->user->email }}" id="user_email"
                                                        required>
                                                </div>
                                            </div>
                                            <p style="font-size: 85%"><i class="fa fa-info-circle"></i>El número de
                                                documento será la contraseña inicial del usuario, que deberá cambiar
                                                inmediatamente al iniciar sesión.</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="input-bornDate">Fecha de nacimiento</label>
                                        <input class="form-control" type="date"
                                            max="{{ date('Y-m-d', strtotime(now() . '- 18 year')) }}"
                                            value="{{ date_create($professional->profile->bornDate)->format('Y-m-d') }}"
                                            name="bornDate" id="input-bornDate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-name">Género</label>
                                        <input class="form-control" type="text" name="gender" id="input-gender"
                                            placeholder="Masculino/Femenino/Otro" required
                                            value="{{ $professional->profile->gender }}">
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="input-contry">País</label>
                                                <select name="country" id="input-country" class="form-control"
                                                    onchange="getProvinces(this.value)">
                                                    @foreach (\App\Models\Country::all() as $country)
                                                        <option value="{{ $country->id }}" @if ($country == $professional->profile->city->province->country)
                                                            selected
                                                    @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="input-province">Provincia</label>
                                                <select name="province" id="input-province" class="form-control"
                                                    onchange="getCities(this.value)">
                                                    <option value="0">Seleccione primero el país</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="input-city">Ciudad:</label>
                                                <select name="city" id="input-city" class="form-control" required
                                                    onchange="getInstitutions(this.value)">
                                                    <option value="0">Seleccione primero la provincia</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="input-address">Dirección</label>
                                        <input class="form-control" type="text" name="address" id="input-address"
                                            value="{{ $professional->profile->address }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-phone">Teléfono</label>
                                        <input class="form-control" type="text" name="phone" id="input-phone"
                                            value="{{ $professional->profile->phone }}" placeholder="+543764466666"
                                            required>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="input-licensePlate">Matrícula</label>
                                        <input class="form-control" type="text" name="licensePlate"
                                            value="{{ $professional->licensePlate }}" id="input-licensePlate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-specialty">Especialidad:</label>
                                        <select name="specialty" id="input-specialty" class="form-control" required>
                                            @foreach (\App\Models\Specialty::all() as $specialty)
                                                <option value="{{ $specialty->id }}" @if ($specialty == $professional->specialty)
                                                    selected
                                            @endif>{{ $specialty->displayname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-field">Especialización/Rubro específico</label>
                                        <input class="form-control" type="text" name="field" id="input-field"
                                            value="{{ $professional->field }}"
                                            placeholder="Por ejemplo: Psicología laboral." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-institution_id">Establecimiento donde trabaja:</label>
                                        <select name="institution_id" id="input-institution_id" class="form-control">
                                            <option value="0">Seleccione primero la ciudad</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Para personalizar el perfil y
                                        comenzar a recibir turnos, debe iniciar sesión en su cuenta y configurar sus
                                        horarios de atención.</p>
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="input-isVirtual">Estado</label>
                                        <select class="form-control" name="status" id="input-status">
                                            <option value="1" @if (old('status', $professional->status) == 1) selected @endif>Habilitado</option>
                                            <option value="0" @if (old('status', $professional->status) == 0) selected @endif>Inhabilitado</option>
                                        </select>

                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>
                                    <input type="hidden" value="{{ $professional->id }}">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-default mt-4">{{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                            $('#input-city option[value={{ $professional->profile->city->province_id }}]')
                                .prop("selected", true);
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

                            $('#input-city option[value={{ $professional->profile->city_id }}]').prop(
                                "selected", true);
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
                            $('#input-institution_id option[value={{ $professional->institution_id }}]')
                                .prop("selected", true);

                        }
                    }
                });
            }
        }
    </script>
@endsection
