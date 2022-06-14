@extends('layouts.app', ['activePage' => 'patient_events', 'title' => $companyName.' | Ver turno', 'navName' => 'Detalles de
la consulta', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">{{ 'Turno N° ' . $event->id }}</h3>
                                    <p><strong>Paciente:</strong> {{ $event->title }}</p>
                                    <p><strong>Fecha de turno:</strong>
                                        {{ date_create($event->start)->format('d/m/Y h:i') }}</p>
                                    <p><strong>Cobertura médica:</strong>
                                        {{ $event->cite->medicalHistory->patientProfile->lifesheet->coverage->name }}</p>
                                    <p><strong>Fue cubierto por obra social:</strong>
                                        {{ $event->cite->covered ? 'Sí' : 'No' }}</p>
                                    <p><strong>Tratamiento:</strong>
                                        {{ isset($event->cite->treatment) ? $event->cite->treatment->name : 'Ninguno' }}
                                    </p>
                                    <p><strong>Precio a pagar:</strong>
                                        ${{ isset($event->cite->total) ? $event->cite->total : $event->cite->practice->price->price }}
                                        <span
                                            class="badge {{ $event->cite->paid ? 'badge-success' : 'badge-secondary' }}">{{ $event->cite->paid ? 'Pagado' : 'No pagado' }}</span>
                                    </p>
                                    <p><strong>Modalidad:</strong> {{ $event->isVirtual == 1 ? "Virtual" : "Presencial" }}</p>  
                                    @if (date_create($event->start) > date_create('now'))
                                        <div class="alert alert-warning">
                                            <button type="button" aria-hidden="true" class="close"
                                                data-dismiss="alert">
                                                <i class="nc-icon nc-simple-remove"></i>
                                            </button>
                                            <span>
                                                <b> Información: </b> Faltan
                                                {{ date_diff(date_create($event->start), date_create(now()))->format('%d') }}
                                                días para llevarse a cabo esta reunión.</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col">
                                    @if (isset($gevent))
                                        <div class="row">
                                            <div class="col">
                                                <a href="{{ $gevent->htmlLink }}" class="btn bg-success text-light"><img
                                                    src="https://fonts.gstatic.com/s/i/productlogos/calendar_2020q4/v13/web-64dp/logo_calendar_2020q4_color_1x_web_64dp.png"
                                                    style="max-width: 30px; max-height:30px;"> Ver en Google Calendar</a>
                                            </div>
                                            
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" autocomplete="off" enctype="multipart/form-data">

                                <div class="pl-lg-4">

                                     
                                    @if (date_create('now')->format('Y-m-d') == date_sub(date_create($event->start), date_interval_create_from_date_string('2 days'))->format('Y-m-d'))
                                        <div class="form-group">
                                            <h5>Confirmar asistencia</h5>
                                            <p>La consulta está a punto de tomar lugar. Confirme su asistencia para evitar
                                                recargos adicionales.</p>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#confirmModal">Confirmar</button>
                                        </div>
                                    @endif
                                    @if (date_create('now') < date_create($event->start))
                                            <div class="form-group">
                                        <div class="col">
                                            <button id="btn-cancelar" class="btn bg-danger text-light mt-4"
                                                data-toggle="modal"
                                                data-target="#exampleModal">{{ __('Cancelar turno') }}</button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                </div>
                            </form>

                        </div>
                    </div>
                    <!-- Button trigger modal -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar turno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Segura/o que desea cancelar el turno? En caso de querer un nuevo turno, deberá volver a agendar uno.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver atrás.</button>
                    <form method="post" action="/profile/events/delete/{{ $event->id }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn bg-danger text-light">Sí, cancelar turno.</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Segura/o que desea confirmar la asistencia al consultorio? Se aplicarán cargos adicionales en caso de
                    no asistir.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver atrás.</button>
                    <form method="post" action="/profile/events/delete/{{ $event->id }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn bg-primary text-light">Sí, confirmar turno.</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
