<h1>{{$companyName}} |  Turno aprobado</h1>
<p>El profesional le autorizó el turno agendado para el día {{ date_create($event->start)->format('d/m/Y i') }}.<br>
</p>
<h2>Datos del turno:</h2>
<ul>
    <li>Fecha y hora: {{ date_create($event->start)->format('d/m/Y h:i') }}</li>
    <li>Prestador/a:
        {{ $event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname }}.
    </li>
    <li>Tipo de consulta: {{ $event->consultType->name }}</li>
</ul>

<p>Si va a <strong>cancelar</strong> el turno, toque el link de abajo: </p>
<ul>
    <li><a
            href="{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}">{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}</a>
    </li>
</ul>
