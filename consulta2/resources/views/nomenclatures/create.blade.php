@extends('layouts.app', ['activePage' => 'nomenclatures', 'title' => 'Consulta2 | Agregar nomenclatura', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Agregar nomenclatura</h1>
                    <p>Agregar una nomenclatura de acuerdo al Nomenclador Nacional de Prácticas Médicas.</p>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/nomenclatures') }}" method="post">
                @csrf
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-code">Código</label>
                            <input class="form-control" type="text" name="code" id="input-code" placeholder="Ej: C0004">
                        </div>
                        <div class="form-group">
                            <label for="input-specialty">Especialidad:</label>
                            <select name="specialty" id="input-specialty" class="form-control">
                                @foreach (\App\Models\Specialty::all() as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->displayname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-description">Descripción</label>
                            <input class="form-control" type="text" name="description" id="input-description"
                                placeholder="Ej: Exámenes sensoriales">
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
    </div>
    </div>
@endsection
