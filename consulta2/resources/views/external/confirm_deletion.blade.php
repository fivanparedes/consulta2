@extends('layouts.app', ['activePage' => '', 'title' => 'Consulta2 | Cancelar turno', 'navName' => 'Agendar un turno', 'activeButton' => 'laravel'])

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">
            <h1>Cancelar turno</h1>
            <p></p>
        </div>
        <div class="card-body">
        <h2>Datos del turno</h2>
        <ul>
            <li>Fecha y hora: {{ date_create($event->start)->format('d-m-Y h:i') }}</li>
            <li>Tipo de consulta: {{ $event->consultType->name }}</li>
            <li>Prestador/a: {{ $event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname}}</li>
            <li>Rubro: {{ $event->professionalProfile->specialty->displayname }}</li>
        </ul>
        <form method="post" action="/external/event/delete/{{ $event->id }}">
            @csrf
            @method('delete')
            <button class="btn btn-primary bg-red">Confirmar cancelación</button>
        </form>
        
    </div>
    </div>
</div>
@endsection