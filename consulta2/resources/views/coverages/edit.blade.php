@extends('layouts.app', ['activePage' => 'nomenclatures', 'title' => $companyName.' | Agregar nomenclatura', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar Obra Social N° {{ $coverage->id }}</h1>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/coverages/'.$coverage->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="input-name" 
                                value="{{ $coverage->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-city">Ciudad:</label>
                            <select name="city" id="input-city" class="form-control">
                                @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}" @if ($city == $coverage->city)
                                        selected
                                @endif>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-address">Dirección</label>
                            <input class="form-control" type="text" name="address" id="input-address"
                                 value="{{ $coverage->address }}">
                        </div>
                        <div class="form-group">
                            <label for="input-phone">Teléfono</label>
                            <input class="form-control" type="text" name="phone" id="input-phone"
                                placeholder="+543764466666" value="{{ $coverage->phone }}">
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/nomenclatures') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <form action="/coverages/{{ $coverage->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar Obra Social</button>
        </form>
    </div>
    </div>
@endsection
