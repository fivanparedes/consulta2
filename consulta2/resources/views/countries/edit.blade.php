@extends('layouts.app', ['activePage' => 'config', 'title' => $companyName.' | Editar país', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar país N° {{ $country->id }}</h1>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/countries/'.$country->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="input-name" 
                                value="{{ $country->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-code">Código de país</label>
                            <input class="form-control" type="number" name="code" id="input-code"
                                placeholder="+54" value="{{ $country->phone }}">
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/countries') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <form action="/countries/{{ $country->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar país</button>
        </form>
    </div>
    </div>
@endsection
