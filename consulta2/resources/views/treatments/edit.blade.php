@extends('layouts.app', ['activePage' => 'treatments', 'title' => 'Consulta2 | Actualizar tratamiento N°'.$treatment->id, 'navName' =>
'Tratamientos', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Actualizar tratamiento</h2>
                    <p class="card-secondary">Actualizar tratamiento para la persona seleccionada</p>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/treatments/'.$treatment->id) }}" method="post">
                @csrf
                @method('put')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <p><strong>Paciente:</strong> {{ $treatment->medicalHistory->patientProfile->getFullName() }}</p>
                            <p><strong>Práctica realizada:</strong> {{ $treatment->description }}</p>
                        </div>
                        <div class="form-group">
                            <label for="input-name">Nombre del tratamiento</label>
                            <input type="text" name="name" id="input-name" class="form-control" required value="{{ $treatment->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-times_per_month">Veces a las que asistirá al consultorio (por mes)</label>
                            <input type="number" name="times_per_month" id="input-times_per_month" class="form-control" value="{{ $treatment->times_per_month }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="input-start">Fecha de inicio del tratamiento</label>
                            <input class="form-control" type="date" name="start" id="input-start" required value="{{ date('Y-m-d', strtotime($treatment->start)) }}">
                        </div>
                        <div class="form-group">
                            <label for="input-end">Fecha de finalización del tratamiento</label>
                            <input class="form-control" type="date" name="end" id="input-end" required value="{{ date('Y-m-d', strtotime($treatment->end)) }}">
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-secondary text-light" href="{{ url('/treatments') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <div class="text-center form-group">
            <form action="{{ url('/treatments/'.$treatment->id) }}" method="post">
            @csrf
            @method('delete')
                <button type="submit" class="btn bg-danger text-light">Eliminar tratamiento</button>
            </form>
        </div>
        
    </div>
    </div>
@endsection
