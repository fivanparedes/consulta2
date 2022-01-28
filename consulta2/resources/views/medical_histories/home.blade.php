@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => 'Consulta2 | Mi historia clínica',
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
                                        @foreach ($medicalHistories as $medical_history)
                                            <a href="{{ url('/medical_history' . '/' . encrypt($medical_history->id)) }}">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="card-title">
                                                                    <h5>{{ $medical_history->professionalProfile->profile->user->name . ' ' . $medical_history->professionalProfile->profile->user->lastname }}
                                                                    </h5>
                                                                </div>
                                                                <div class="card-category">
                                                                    <p>{{ $medical_history->professionalProfile->specialty->displayname }}
                                                                    </p>
                                                                </div>
                                                                <p>Lugar: {{ $medical_history->professionalProfile->institution_id != 1 ? $medical_history->professionalProfile->institution->name : 'Consultorio propio' }}</p>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                            </a>

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
