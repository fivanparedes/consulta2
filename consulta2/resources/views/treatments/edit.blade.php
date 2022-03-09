@extends('layouts.app', ['activePage' => 'treatments', 'title' => $companyName.' | Actualizar tratamiento N°'.$treatment->id, 'navName' =>
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
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-monday" id="av-monday" value="1" @if (in_array("1", $preferred_days))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="av-monday"><strong>Lunes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-tuesday" value="2" @if (in_array("2", $preferred_days))
                                                checked
                                            @endif
                                                id="av-tuesday">
                                            <label class="form-check-label" for="av-tuesday"><strong>Martes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-wednesday" value="3" @if (in_array("3", $preferred_days))
                                                checked
                                            @endif
                                                id="av-wednesday">
                                            <label class="form-check-label"
                                                for="av-wednesday"><strong>Miércoles</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-thursday" value="4" @if (in_array("4", $preferred_days))
                                                checked
                                            @endif
                                                id="av-thursday">
                                            <label class="form-check-label"
                                                for="av-thursday"><strong>Jueves</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-friday" id="av-friday" value="5" @if (in_array("5", $preferred_days))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="av-friday"><strong>Viernes</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-saturday" value="6" @if (in_array("6", $preferred_days))
                                                checked
                                            @endif
                                                id="av-saturday">
                                            <label class="form-check-label"
                                                for="av-saturday"><strong>Sábado</strong></label>
                                        </div>
                                        <div class="col form-check-inline">
                                            <input type="checkbox" class="form-check-input" name="av-sunday" id="av-sunday" value="7" @if (in_array("7", $preferred_days))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="av-sunday"><strong>Domingo</strong></label>
                                        </div>
                                    </div>

                                </div>
                                <div class="col">
                                    <label for="input-preferred_hour">Hora preferida</label>
                                    <input type="time" class="form-control" name="preferred_hour" value="{{  $treatment->preferred_hour }}"
                                        id="input-preferred_hour">
                                </div>
                            </div>
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
