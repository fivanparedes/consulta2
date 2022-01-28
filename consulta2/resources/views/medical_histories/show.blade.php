@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => 'Consulta2 | Editar institución', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                    <h2 class="card-title">Historia médica</h2>
                    <p>Prestador: {{ $medical_history->professionalProfile->profile->user->lastname . ' ' . $medical_history->professionalProfile->profile->user->name}}</p>
                    <p>Paciente: {{ $medical_history->patientProfile->profile->user->lastname . ' ' . $medical_history->patientProfile->profile->user->name }}</p>
                        </div>
                        <div class="col">
                            @if ($medical_history->professionalProfile->institution_id != 1)
                                <button id="instbutton" type="button" onclick="toggleInstitutionPrivilege()" class="btn @if ($medical_history->institution_id == null)
                                bg-primary
                            @else
                                bg-danger
                            @endif text-light"><i class="spinner fa fa-spinner"></i>Habilitar acceso a {{ $medical_history->professionalProfile->institution->name }}</button>
                            @endif
                            <a class="btn bg-secondary" href="/medical_history/{{ encrypt($medical_history->id) }}/pdf">PDF</a>
                        </div>
                    </div>
                    </div>
                <hr>
                        <div class="form-group pb-1 ml-4">
                            <p><strong>Fecha de ingreso al consultorio:</strong> {{ $medical_history->indate }}</p>
                            <p><strong>Historia psicológica:</strong> {{ $psicological_history }}</p>
                            <p><strong>Razón de visita:</strong> {{ $visitreason }}</p>
                            <p><strong>Diagnóstico:</strong> {{ Crypt::decryptString($medical_history->diagnosis) }}</p>
                            <p><strong>Antecedentes médicos:</strong>{{ Crypt::decryptString($medical_history->clinical_history) }}</p>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <h4>Historial</h4>
                            @if ($medical_history->cites->count() > 0)
                                @foreach ($medical_history->cites as $cite)
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>Fecha:</strong>{{ date_create($cite->calendarEvent->start)->format('d/m/Y h:i') }}</p>
                                        <p><strong>Tipo de atención:</strong> {{ $cite->calendarEvent->consultType->name }}</p>
                                        <a class="btn bg-primary text-light" href="/cite/{{ $cite->id }}">Ver detalles</a>
                                    </div>
                                </div>
                            @endforeach
                            @else
                                <p>Aún no hay un historial de consultas, evaluaciones u otras atenciones.</p>
                            @endif
                            
                        </div>
                    </div>


                </div>
        </div>
       
        
    </div>
    </div>
<script>
    function toggleInstitutionPrivilege() {
        var id = '<?php echo $medical_history->id ?>';
        $('.spinner').show();
        $.ajax({
            dataType: "json",
            method: 'GET',
            url: '/medical_history/toggleInstitutionPrivilege',
            data: {
                id: id
            },
            success: function(response) {
                $('.spinner').hide();
                if (response.status == 0) {
                    $('#instbutton').addClass('bg-primary');
                    $('#instbutton').removeClass('bg-danger');
                } else {
                    $('#instbutton').addClass('bg-danger');
                    $('#instbutton').removeClass('bg-primary');
                }
            }
        });
    }
</script>
@endsection
