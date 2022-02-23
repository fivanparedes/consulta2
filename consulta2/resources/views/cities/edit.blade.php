@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Editar ciudad', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar ciudad N° {{ $city->id }}</h1>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/cities/' . $city->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="input-name"
                                value="{{ $city->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-zipcode">Código postal</label>
                            <input class="form-control" type="number" name="zipcode" id="input-zipcode" value="{{ $city->zipcode }}"> 
                        </div>
                        <div class="form-group">
                            <label for="input-contry">País</label>
                            <select name="country" id="input-country" class="form-control"
                                onchange="getProvinces(this.value)">
                                @foreach (\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}" @if ($country == $city->province->country) selected @endif>
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-province">Provincia</label>
                            <select name="province_id" id="input-province" class="form-control"
                                onchange="getCities(this.value)">
                                <option value="0">Seleccione primero el país</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/cities') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <form action="/cities/{{ $city->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar ciudad</button>
        </form>
    </div>
    </div>
    <script>
        $(document).ready(function() {
                getProvinces($('#input-country option:selected').val());
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
                        }
                    }
                });
            }
        }
    </script>
@endsection
