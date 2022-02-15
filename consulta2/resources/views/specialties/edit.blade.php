@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Editar especialidad', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar especialidad N° {{ $specialty->id }}</h1>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/specialties/'.$specialty->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre de código</label>
                            <input class="form-control" type="text" name="name" id="input-name" 
                                value="{{ $specialty->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-displayname">Nombre visual</label>
                            <input class="form-control" type="text" name="displayname" id="input-displayname"
                                 value="{{ $specialty->displayname }}">
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/specialties') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <form action="/specialties/{{ $specialty->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar especialidad</button>
        </form>
    </div>
    </div>
@endsection
