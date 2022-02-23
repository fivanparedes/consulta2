@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => 'Consulta2 | Editar historial', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h2 class="card-title">Editar historia clínica</h2>
                                <p>Se podrán editar los datos que hayan sido ingresados erróneamente o necesiten actualizarse.</p>
                                <p><strong>Nombre del paciente:</strong> {{ $medical_history->patientProfile->profile->user->lastname .' '. $medical_history->patientProfile->profile->user->name }}</p>
                                <p><strong>Documento:</strong> {{ $medical_history->patientProfile->profile->user->dni }}</p>
                                <p><strong>Cobertura médica:</strong> {{ $medical_history->patientProfile->lifesheet->coverage->name }}</p>
                                    </div>
                                    <div class="col">
                                        <div class="col-md-12">
                                    <form action="{{ url('/medical_history/document') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <div class="form-group">
                                        <input type="hidden" name="medical_history_id" value="{{ encrypt($medical_history->id) }}">
                                        <input type="file" name="document" placeholder="Elegir document" id="document" class="form-control" onchange="allowButton()">
                                        @error('document')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group text-center"><button id="doc-submit" type="submit" class="btn bg-primary text-light" disabled>Subir documento</button></div>
                                    </form>
                                </div>
                                    </div>
                                </div>
                                @include('alerts.errors')
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class=" pb-1 ml-4">
                        
                        <form action="{{ url('/medical_history/'.$medical_history->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="indate">Fecha de ingreso al establecimiento</label>
                                <input type="date" id="indate" value="{{ date('Y-m-d', strtotime($medical_history->indate)) }}" name="indate"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="psicological_history">Antecedentes psicológicos/psiquiátricos</label>
                                <input type="text" class="form-control" name="psicological_history" id="psicological_history" value="{{ $psicological_history }}"
                                    placeholder="Un resumen de diagnósticos previos del paciente...">
                            </div>
                            <div class="form-group">
                                <label for="visitreason">Razón de visita</label> 
                                <input type="text" name="visitreason" id="visitreason" class="form-control" value="{{ $visitreason }}"
                                    placeholder="Un resumen del motivo por el cual se iniciará un tratamiento o se realizó la consulta/evaluación...">
                            </div>
                            <div class="form-group">
                                <label for="clinical_history">Antecedentes médicos</label>
                                <input type="text" name="clinical_history" id="clinical_history" class="form-control" value="{{ $clinical_history }}"
                                    placeholder="Un resumen de diagnósticos y atenciones previas...">
                            </div>
                            <div class="form-group">
                                <label for="diagnosis">Diagnóstico actual</label>
                                <input type="text" name="diagnosis" id="diagnosis" class="form-control" value="{{ $diagnosis }}"
                                    placeholder="Descripción del estado del paciente en el tratamiento actual con usted o su establecimiento">
                            </div>
                    </div>
                    <hr>
                    <div class="form-group text-center">
                        <a class="btn bg-secondary text-light" href="{{ url('/manage/patients') }}">Cancelar</a>
                        <button type="submit" class="btn bg-primary text-light">Actualizar historial</button>

                    </div>
                    </form>

                </div>


            </div>
        </div>
    </div>
    <script>
        function allowButton() {
            $('#doc-submit').prop('disabled', false);
        }

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
