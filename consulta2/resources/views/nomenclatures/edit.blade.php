@extends('layouts.app', ['activePage' => 'nomenclatures', 'title' => $companyName.' | Agregar nomenclatura', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar nomenclatura N° {{ $nomenclature->id }}</h1>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/nomenclatures/'.$nomenclature->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-code">Código</label>
                            <input class="form-control" type="text" name="code" id="input-code" placeholder="Ej: C0004"
                                value="{{ $nomenclature->code }}">
                        </div>
                        <div class="form-group">
                            <label for="input-specialty">Especialidad:</label>
                            <select name="specialty" id="input-specialty" class="form-control">
                                @foreach (\App\Models\Specialty::all() as $specialty)
                                    <option value="{{ $specialty->id }}" @if ($specialty == $nomenclature->specialty)
                                        selected
                                @endif>{{ $specialty->displayname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-description">Descripción</label>
                            <input class="form-control" type="text" name="description" id="input-description"
                                placeholder="Ej: Exámenes sensoriales" value="{{ $nomenclature->description }}">
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
        <form action="/nomenclatures/{{ $nomenclature->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar nomenclatura</button>
        </form>
    </div>
    </div>
@endsection
