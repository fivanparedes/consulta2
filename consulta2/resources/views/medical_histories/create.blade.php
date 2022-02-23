@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => 'Consulta2 | Crear historial', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                <h2 class="card-title">Crear historial clínico</h2>
                                <p>Al asignar este nuevo historial a un paciente por su DNI, automáticamente aparecerá en su
                                    listado de pacientes.</p>
                            </div>
                        </div>
                    </div>
                    @include('alerts.errors')
                    <hr>
                    <div class=" pb-1 ml-4">
                        
                        <form action="{{ url('/medical_history') }}" method="post">
                            @csrf
                            <div class="form-group">
                                    <label for="dni">Número de documento:</label>
                                    <input class="form-control" type="number" name="dni"
                                        onkeyup="locatePatient(this.value)">
                                </div>
                                <div id="patientlocated"></div>
                            <input type="hidden" name="patient_profile_id" value="0">
                            @if (Auth::user()->isAbleTo('institution-profile'))
                                <label for="professional_profile_id">Escoger profesional propietario del historial</label>
                                <select name="professional_profile_id" id="professional_profile_id" class="form-control">
                                    @foreach (Auth::user()->institutionProfile->professionalProfiles as $professionalProfile)
                                        <option value="{{ $professionalProfile->id }}">
                                            {{ $professionalProfile->profile->user->lastname . ' ' . $professionalProfile->profile->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <div class="form-group">
                                <label for="indate">Fecha de ingreso al establecimiento</label>
                                <input type="date" id="indate" value="{{ date('Y-m-d', strtotime('now')) }}" name="indate"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="psicological_history">Antecedentes psicológicos/psiquiátricos</label>
                                <textarea class="form-control" name="psicological_history" id="psicological_history"
                                    placeholder="Un resumen de diagnósticos previos del paciente..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="visitreason">Razón de visita</label>
                                <textarea name="visitreason" id="visitreason" class="form-control"
                                    placeholder="Un resumen del motivo por el cual se iniciará un tratamiento o se realizó la consulta/evaluación..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="clinical_history">Antecedentes médicos</label>
                                <textarea name="clinical_history" id="clinical_history" class="form-control"
                                    placeholder="Un resumen de diagnósticos y atenciones previas..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="diagnosis">Diagnóstico actual</label>
                                <textarea name="diagnosis" id="diagnosis" class="form-control"
                                    placeholder="Descripción del estado del paciente en el tratamiento actual con usted o su establecimiento"></textarea>
                            </div>
                    </div>
                    <hr>
                    <div class="form-group text-center">
                        <a class="btn bg-secondary text-light" href="{{ url('/manage/patients') }}">Cancelar</a>
                        <button type="submit" class="btn bg-primary text-light">Adherir historial al paciente</button>

                    </div>
                    </form>

                </div>


            </div>
        </div>
    </div>
    <script>
        function locatePatient(value) {
            if (value.length > 7) {
                $.ajax({
                    method: 'GET',
                    url: '/medical_history/locatePatient',
                    dataType: 'json',
                    data: {
                        dni: $('input[type=number][name=dni]').val()
                    },
                    success: function(response) {
                        $('input[type=hidden][name=patient_profile_id]').val(response.id);
                    }
                });
            }

        }
    </script>
@endsection
