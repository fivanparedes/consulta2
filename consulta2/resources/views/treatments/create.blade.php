@extends('layouts.app', ['activePage' => 'treatments', 'title' => 'Consulta2 | Iniciar tratamiento', 'navName' =>
'Tratamientos', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Iniciar tratamiento</h2>
                    <p class="card-secondary">Puede asignarle un tratamiento nuevo solamente a pacientes ya atendidos
                        previamente.</p>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ route('treatments.store') }}" method="post">
                @csrf
                @method('post')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-medical_history_id">Seleccione el paciente</label>
                            <select name="medical_history_id" id="input-medical_history_id" class="form-control">
                                @foreach ($medical_histories as $medical_history)
                                    <option value="{{ $medical_history->id }}">
                                        {{ $medical_history->patientProfile->getFullName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-name">Nombre del tratamiento</label>
                            <input type="text" name="name" id="input-name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="input-description">Práctica que se realizará en el tratamiento</label>
                            <select name="description" id="input-description" class="form-control">
                                <option value="0">Seleccione una práctica de la lista</option>
                                @foreach ($medical_history->professionalProfile->coverages as $coverage)
                                    <optgroup label="{{ $coverage->name }}">
                                        @foreach ($coverage->practices as $practice)
                                            <option value="{{ $practice->name }}">{{ $practice->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-times_per_month">Veces a las que asistirá al consultorio (por mes)</label>
                            <input type="number" name="times_per_month" id="input-times_per_month" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="input-start">Fecha de inicio del tratamiento</label>
                            <input class="form-control" type="date" name="start" id="input-start" required>
                        </div>
                        <div class="form-group">
                            <label for="input-end">Fecha de finalización del tratamiento</label>
                            <input class="form-control" type="date" name="end" id="input-end" required>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/treatments') }}">
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
