<h1>Consulta2 | Recordatorio de deuda</h1>
<p>Se le recuerda que el turno del día {{ date_create($event->start)->format('d/m/Y h:i') }} no fue pagado.<br>
    Se le cobrará un recargo por el retraso.
</p>
<h2>Datos del turno:</h2>
<ul>
    <li>Fecha y hora: {{ date_create($event->start)->format('d/m/Y h:i') }}</li>
    <li>Prestador/a:
        {{ $event->professionalProfile->getFullName() }}.
    </li>
    <li>Tipo de consulta: {{ $event->consultType->name }}</li>
    <li>Adeuda: ${{ isset($debt) ? $debt : $event->cite->practice->price->price }}</li>
</ul>

@if (isset($reminder))
    <p>Si <strong>confirma</strong> su voluntad de pago, toque el link de abajo: </p>
    <ul>
        <li><a
                href="{{ env('APP_URL') . ':8000/reminder/willpay/' . encrypt($reminder->id) }}">{{ env('APP_URL') . ':8000/reminder/willpay/' . encrypt($reminder->id) }}</a>
        </li>
    </ul>
@endif

@if ($disabling)
    El agendamiento automático de turnos para su tratamiento no será renovado hasta que regularice su situación.
@else
    <p>Si usted desea <strong>cancelar su tratamiento</strong> toque el link de abajo: </p>
    <ul>
        <li><a
                href="{{ env('APP_URL') }}:8000/reminder/treatment/cancel/{{ encrypt($event->cite->treament->id) }}">{{ env('APP_URL') }}:8000/reminder/treatment/cancel/{{ encrypt($event->cite->treatment->id) }}</a>
        </li>
    </ul>
@endif

<p>Si usted cree que este aviso es un  <strong>error</strong> toque el link de abajo: </p>
    <ul>
        <li><a
                href="{{ env('APP_URL') }}:8000/reminder/treatment/mistake/{{ encrypt($event->cite->treament->id) }}">{{ env('APP_URL') }}:8000/reminder/treatment/mistake/{{ encrypt($event->cite->treatment->id) }}</a>
        </li>
    </ul>
