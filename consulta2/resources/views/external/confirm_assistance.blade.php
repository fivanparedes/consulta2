<div class="content">
    <h1>Confirmado.</h1>
    <p>Gracias por confirmar su asistencia. El turno se dará mañana
        {{ date_create($reminder->calendarEvent->start)->format('d/m/Y h:i') }} en el consultorio de
        {{ $reminder->calendarEvent->professionalProfile->profile->user->name . ' ' . $reminder->calendarEvent->professionalProfile->profile->user->lastname }}
    </p>
</div>
