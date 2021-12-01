<h1>Consulta2 | Recordatorio de turno</h1>
<p>Se le recuerda que mañana {{ date_create($event->start)->format('d/m/Y h:m') }} tiene agendado un turno.<br>
    En caso de no confirmar la asistencia, se cobrará un recargo
</p>
<h2>Datos del turno:</h2>
<ul>
    <li>Fecha y hora: {{ date_create($event->start)->format('d/m/Y h:m') }}</li>
    <li>Prestador/a:
        {{ $event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname }}.
    </li>
    <li>Tipo de consulta: {{ $event->consultType->name }}</li>
</ul>

<p>Si <strong>confirma</strong> su asistencia a la consulta, toque el link de abajo: </p>
<ul>
    <li><a
            href="{{ env('APP_URL') . ':8000/reminder/confirm/' . encrypt($reminder->id) }}">{{ env('APP_URL') . ':8000/reminder/confirm/' . encrypt($reminder->id) }}</a>
    </li>
</ul>

<p>Si va a <strong>cancelar</strong> el turno, toque el link de abajo: </p>
<ul>
    <li><a
            href="{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}">{{ env('APP_URL') }}:8000/external/event/cancel/{{ encrypt($event->id) }}</a>
    </li>
</ul>
