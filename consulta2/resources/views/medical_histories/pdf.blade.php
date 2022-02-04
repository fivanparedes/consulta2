<html>

<head>
    <title>Consulta2 | Informe - Profesionales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }

    </style>
    <div style="text-align: center">
        <div class="row">
            <div class="col">
                <img style="position: relative; float:left"
                    src='https://static.vecteezy.com/system/resources/previews/000/499/145/original/vector-health-sign-icon-design.jpg'
                    width="100" height="100" alt="Logo" />
            </div>
            <div class="col">
                <h1 style="position: relative; float: right;">Consulta2</h1>
            </div>
        </div>
    </div>
    <hr style="clear: both;">
    <div class="row">
        <h2 class="card-title">Historia médica</h2>
    </div>
    <div class="row">
        <p>Paciente:
            {{ $medical_history->patientProfile->profile->user->lastname .' ' .$medical_history->patientProfile->profile->user->name }}
        </p>
    </div>
    <div class="row">
        <p>Prestador:
            {{ $medical_history->professionalProfile->profile->user->lastname .' ' .$medical_history->professionalProfile->profile->user->name }}
        </p>
    </div>

    <div class="card-body" style="font-size: 80%">
        <div class="form-group pb-1 ml-4">
            <p><strong>Fecha de ingreso al consultorio:</strong> {{ $medical_history->indate }}</p>
            <p><strong>Historia psicológica:</strong> {{ $psicological_history }}</p>
            <p><strong>Razón de visita:</strong> {{ $visitreason }}</p>
            <p><strong>Diagnóstico:</strong> {{ Crypt::decryptString($medical_history->diagnosis) }}</p>
            <p><strong>Antecedentes médicos:</strong>{{ Crypt::decryptString($medical_history->clinical_history) }}
            </p>
        </div>
        <hr>
        @php
            $lifesheet = $medical_history->patientProfile->lifesheet;
        @endphp
        <div class="form-group pb-1 ml-4">
            <h5>Antecedentes</h5>
            <p><strong>Tuvo enfermedades:</strong> {{ $lifesheet->diseases }}</p>
            <p><strong>Tuvo cirugías:</strong> {{ $lifesheet->surgeries }}</p>
            <p><strong>Medicamentos que consume:</strong> {{ $lifesheet->medication }}</p>
            <p><strong>Tiene alguna alergia:</strong> {{ $lifesheet->allergies }}</p>
            <p><strong>Fuma:</strong>{{ $lifesheet->smokes == 0 ? 'No' : 'Sí' }}
            </p>
            <p><strong>Bebe alcohol:</strong>{{ $lifesheet->drinks == 0 ? 'No' : 'Sí' }}
            </p>
            <p><strong>Realiza actividad física:</strong>{{ $lifesheet->exercises == 0 ? 'No' : 'Sí' }}
            </p>
        </div>
        <div class="form-group text-center">
            <h4>Historial</h4>
            @if ($medical_history->cites->count() > 0)
                @foreach ($medical_history->cites as $cite)
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Fecha:</strong>{{ date_create($cite->calendarEvent->start)->format('d/m/Y h:i') }}
                            </p>
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
</body>

</html>
