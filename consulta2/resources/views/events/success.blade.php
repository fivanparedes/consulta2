@extends('layouts.app', ['activePage' => 'professional_show', 'title' => 'Consulta2 | Confirmar turno', 'navName' =>
'Agendar un turno', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2>Turno agendado exitosamente!</h2>
                            <strong>Nombre del prestador: </strong>
                            <p>{{ $event->professionalProfile->getFullName() }}
                            </p>
                            <strong>Área:</strong>
                            <p> {{ $event->professionalProfile->specialty->displayname }}</p>
                            <strong>Fecha y hora: </strong>
                            <p> {{ date_create($event->start)->format('D M Y H:i') }}</p>
                            <strong>Tipo de consulta:</strong>
                            <p> {{ $event->consultType->name }}</p>
                            <strong>Modalidad:</strong>
                            <p>
                                @if ($event->isVirtual == 0)
                                    Presencial
                                @else
                                    Virtual
                                @endif
                            </p>
                            <strong>Precio:</strong>
                            <p>

                                @if ($event->cite->total == 0)
                                    No es necesario abonar nada.
                                @else
                                    El afiliado debe abonar ${{ $event->cite->total }}.
                                @endif

                            </p>
                            <hr>
                            <div class="text-center">
                                <div class="row">
                                    <div class="col">
                                        <a class="btn bg-primary text-light" href="{{ url('/home') }}">Volver al inicio</a>
                                    </div>
                                    <div class="col">
                                        <a class="btn bg-secondary text-light" href="{{ route('events.pdf', ['calendarEvent' => $event]) }}">Imprimir comprobante</a>
                                    </div>
                                    @if (isset($gevent))
                                        <div class="col">
                                        <a class="btn bg-success text-light" href="{{ $gevent->htmlLink }}"><img src="https://fonts.gstatic.com/s/i/productlogos/calendar_2020q4/v13/web-64dp/logo_calendar_2020q4_color_1x_web_64dp.png" style="max-width: 30px; max-height:30px;"> Ir a Calendar</a>
                                        </div>
                                    @endif
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
