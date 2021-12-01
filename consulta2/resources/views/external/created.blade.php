<div class="card">
    <div class="card-header">
        <h1>Consulta2 | Turno agendado</h1>
    </div>
    <div class="card-body">
        <h2>Datos del turno</h2>
        <ul>
            <li>Fecha y hora: {{ date_create($event->start)->format('d-m-Y h:m') }}</li>
            <li>Tipo de consulta: {{ $event->consultType->name }}</li>
            <li>Prestador/a: {{ $event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname}}</li>
            <li>Rubro: {{ $event->professionalProfile->specialty->displayname }}</li>
        </ul>
        <h2>Datos del paciente</h2>
        <ul>
            <li>Nombre y apellido: {{ $user->name . ' ' . $user->lastname }}</li>
            <li>Obra social: {{ $user->profile->patientProfile->lifesheet->coverage->name }}</li>
            <li>DNI: {{ $user->dni }}</li>
        </ul>
        Si usted desea <strong>cancelar</strong> el presente turno, toque en el siguiente link: <br>
        <a href="{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}">{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}</a>
    </div>
</div>
