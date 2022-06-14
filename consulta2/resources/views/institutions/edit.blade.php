@extends('layouts.app', ['activePage' => 'institutions', 'title' => $companyName.' | Editar institución', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Editar institución N° {{ $institution->id }}</h2>
                    <p><strong>Administrador:</strong> {{ $institution->user->name . ' ' . $institution->user->lastname }}</p>
                    <p><strong>Contacto:</strong> {{ $institution->user->email }}</p>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/institutions/' . $institution->id) }}"
                method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="input-name"
                                value="{{ $institution->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-name">Descripción</label>
                            <input class="form-control" type="text" name="description" id="input-description"
                                placeholder="Una descripción amigable sobre lo que se realiza acá"
                                value="{{ $institution->description }}">
                        </div>
                        <div class="form-group">
                            <label for="input-contry">País</label>
                            <select name="country" id="input-country" class="form-control"
                                onchange="getProvinces(this.value)">
                                @foreach (\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}" @if ($country == $institution->city->province->country) selected @endif>
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
                            <input class="form-control" type="text" name="address" id="input-address"
                                value="{{ $institution->address }}">
                        </div>
                        <div class="form-group">
                            <label for="input-phone">Teléfono</label>
                            <input class="form-control" type="text" name="phone" id="input-phone"
                                placeholder="+543764466666" value="{{ $institution->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="input-active">Estado</label>
                            <select name="active" id="input-active" class="form-control">
                                <option value="0" @if ($institution->active == 0) selected @endif>Inactivo</option>
                                <option value="1" @if ($institution->active == 1) selected @endif>Activo</option>
                            </select>
                        </div>
                        <hr>
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
                            $('#input-city option[value={{ $institution->city->province_id }}]').prop(
                                "selected", true);
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

                            $('#input-city option[value={{ $institution->city_id }}]').prop("selected",
                            true);
                        }
                    }
                });
            }
        }
    </script>
@endsection
