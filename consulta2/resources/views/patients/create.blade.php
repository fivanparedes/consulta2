@extends('layouts.app', ['activePage' => 'patients', 'title' => $companyName.' | Añadir paciente', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Añadir paciente</h2>
                </div>
            </div>
            @include('alerts.errors')
            <form id="create-form" class="form-horizontal" action="{{ route('patients.store') }}" method="post">
                @csrf
                @method('post')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Estos son los datos del usuario que
                                administrará su cuenta.</p>

                            <div class="row">
                                <div class="col">
                                    <label for="user_name">Nombre del usuario a cargo</label>
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
                            <input class="form-control" type="date" max="{{ date('Y-m-d', strtotime(now())) }}"
                                name="bornDate" id="input-bornDate" required>
                        </div>
                        <div class="form-group">
                            <label for="input-gender">Género</label>
                            <input class="form-control" type="text" name="gender" id="input-gender"
                                placeholder="Masculino/Femenino/Otro" required>
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
                        <div class="form-group">
                            <label for="input-bornPlace">Lugar de nacimiento (puede no ser el lugar actual)</label>
                            <input type="text" name="bornPlace" id="input-bornPlace" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-familyGroup">Grupo familiar actual</label>
                            <input type="text" name="familyGroup" id="input-familyGroup" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-familyPhone">Teléfono de familiar cercano</label>
                            <input type="text" name="familyPhone" id="input-familyPhone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-civilState">Estado civil</label>
                            <input type="text" name="civilState" id="input-civilState" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-scholarity">Escolaridad</label>
                            <input type="text" name="scholarity" id="input-scholarity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-occupation">Ocupación</label>
                            <input type="text" name="occupation" id="input-occupation" class="form-control" required>
                        </div>
                        <p style="font-size: 85%"><i class="fa fa-info-circle"></i>Para cambiar la contraseña y comenzar a
                            pedir turnos, debe iniciar sesión en su cuenta.</p>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/patients') }}">
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
