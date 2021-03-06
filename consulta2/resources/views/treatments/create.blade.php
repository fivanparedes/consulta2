@extends('layouts.app', ['activePage' => 'treatments', 'title' => $companyName.' | Iniciar tratamiento', 'navName' =>
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
                @include('alerts.errors')
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
                            <label for="input-description">Actividad que se realizará en el tratamiento</label>
                            <select name="description" id="input-description" class="form-control">
                                @foreach ($medical_history->professionalProfile->consultTypes as $consultType)
                                    <option value="{{ $consultType->name }}">{{ $consultType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="input-times_per_month">Veces a las que asistirá al consultorio (por mes)</label>
                            <input type="number" name="times_per_month" id="input-times_per_month" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-monday" id="av-monday" value="1">
                                            <label class="form-check-label" for="av-monday"><strong>Lunes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-tuesday" value="2"
                                                id="av-tuesday">
                                            <label class="form-check-label" for="av-tuesday"><strong>Martes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-wednesday" value="3"
                                                id="av-wednesday">
                                            <label class="form-check-label"
                                                for="av-wednesday"><strong>Miércoles</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-thursday" value="4"
                                                id="av-thursday">
                                            <label class="form-check-label"
                                                for="av-thursday"><strong>Jueves</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-friday" id="av-friday" value="5">
                                            <label class="form-check-label" for="av-friday"><strong>Viernes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-saturday" value="6"
                                                id="av-saturday">
                                            <label class="form-check-label"
                                                for="av-saturday"><strong>Sábado</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-sunday" id="av-sunday" value="7">
                                            <label class="form-check-label" for="av-sunday"><strong>Domingo</strong></label>
                                        </div>
                                    </div>

                                </div>
                                <div class="col">
                                    <label for="input-preferred_hour">Hora preferida</label>
                                    <input type="time" class="form-control" name="preferred_hour"
                                        id="input-preferred_hour">
                                </div>
                            </div>
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
