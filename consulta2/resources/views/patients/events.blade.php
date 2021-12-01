@extends('layouts.app', ['activePage' => 'patient_events', 'title' => 'Consulta2 | Mis turnos', 'navName' => 'Mi
perfil', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Mis turnos.</h4>
                            <p class="card-category">Lista de turnos agendados</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body table-full-width table-responsive">
                        @if ($events->count() == 0)
                            <p class="ml-5 card-category">No hay turnos agendados.</p>
                        @else
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Prestador</th>
                                    <th>Fecha y hora</th>
                                    <th>Rubro</th>
                                    <th>Modalidad</th>
                                    <th>Tipo de consulta</th>
                                    <th>Estado</th>
                                    <th>Más</th>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ $event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname }}
                                            </td>
                                            <td>{{ date('d-m-Y h:m', strtotime($event->start)) }}</td>
                                            <td>{{ $event->professionalProfile->specialty->displayname }}</td>
                                            <td>
                                                @if ($event->isVirtual)
                                                    Virtual
                                                @else
                                                    Presencial.
                                                @endif
                                            </td>
                                            <td>{{ $event->consultType->name }}</td>
                                            <td>
                                                @if ($event->approved == 0)
                                                    <span class="badge badge-secondary">En revisión</span>
                                                @else
                                                    <span class="badge badge-primary text-light">Aprobado</span>
                                                @endif
                                            </td>
                                            <td><a class="nav-link" href="/profile/events/{{ $event->id }}"
                                                    title="Más">
                                                    <i class="nc-icon nc-badge"></i>
                                                </a></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.detail-row').hide();
        });

        function showDetails() {
            $(this).parent().children('.detail-row').show();
        }
    </script>
@endsection
