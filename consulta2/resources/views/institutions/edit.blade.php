@extends('layouts.app', ['activePage' => 'institutions', 'title' => 'Consulta2 | Editar institución', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Editar institución N° {{ $institution->id }}</h2>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/institutions/'.$institution->id) }}" method="post">
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
                            <input class="form-control" type="text" name="description" id="input-description" placeholder="Una descripción amigable sobre lo que se realiza acá" 
                                value="{{ $institution->description }}">
                        </div>
                        <div class="form-group">
                            <label for="input-city">Ciudad:</label>
                            <select name="city" id="input-city" class="form-control">
                                @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}" @if ($city == $institution->city)
                                        selected
                                @endif>{{ $city->name }}</option>
                                @endforeach
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
                            <select name="active" id="input-active">
                                <option value="0">Inactivo</option>
                                <option value="1">Activo</option>
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
        <form action="/institutions/{{ $institution->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar institución</button>
        </form>
    </div>
    </div>
@endsection
