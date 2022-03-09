@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => $companyName.' | Mi historia clínica',
'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-body">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col col-md-12 left">
                                        <h4 class="card-title">Mi historia clínica</h4>
                                        <p class="card-category">Evaluaciones, atenciones, estudios...</p>
                                    </div>
                                </div>
                                @if ($medicalHistories->count() > 0)
                                    <div class="col col-pl-4 mt-5">
                                        @foreach ($patientProfile->calendarEvents->toQuery()->orderByDesc('start')->get() as $calendarEvent)
                                            @if (isset($calendarEvent->cite))
                                                <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <p><strong><i class="nc-icon nc-time-alarm"></i></strong> {{ date_create($calendarEvent->start)->format('d/m/Y h:i') }}</p>
                                                            <p><strong><i class="nc-icon nc-circle-09"></i></strong> {{ $calendarEvent->professionalProfile->profile->user->lastname . ' ' . $calendarEvent->professionalProfile->profile->user->name }}</p>
                                                            <p><strong><i class="nc-icon nc-square-pin"></i></strong> {{ $calendarEvent->professionalProfile->institution_id == 1 ? "Consultorio personal" : $calendarEvent->professionalProfile->institution->name }}</p>
                                                            <p><strong><i class="nc-icon nc-grid-45"></i></strong> {{ $calendarEvent->consultType->name }}</p>
                                                        </div>
                                                        <div class="col">
                                                            <a href="/profile/events/{{ $calendarEvent->id }}" class="btn bg-primary text-light">Ver registro</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-pl-4 mt-5">
                                        <h4>No hay registros médicos suyos en el sistema actualmente.</h4>
                                    </div>
                                @endif
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
