@extends('layouts.app', ['activePage' => 'cite', 'title' => $companyName.' | Ver consulta', 'navName' => 'Detalles de
sesión',
'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-8">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col col-md-8">
                                    <h3 class="mb-0">{{ 'Consulta N° ' . $cite->id }} <button type="button"
                                            class="btn bg-primary text-light" data-toggle="modal" data-target="#helpModal"><i
                                                class="fa fa-question"></i></button></h3>
                                    @if (date_create($calendarEvent->start) > date_create('now'))
                                        <div class="alert alert-warning">
                                            <button type="button" aria-hidden="true" class="close"
                                                data-dismiss="alert">
                                                <i class="nc-icon nc-simple-remove"></i>
                                            </button>
                                            <span>
                                                <b> Información: </b> Faltan aprox.
                                                {{ date_diff(date_create($calendarEvent->start), now())->format('%d') }}
                                                días para llevarse a cabo esta reunión.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="/cite/update/{{ $cite->id }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                @include('alerts.success')
                                @include('alerts.error_self_update', [
                                    'key' => 'not_allow_profile',
                                ])
                                <input type="hidden" name="practice" value="{{ $cite->practice_id }}">
                                <div class="form-group{{ $errors->has('resume') ? ' has-error' : '' }}">
                                    <p><strong>Fecha de turno:</strong>
                                        {{ date_create($cite->calendarEvent->start)->format('d/m/Y h:i') }}</p>
                                    <p><strong>Modalidad:</strong>
                                        {{ $cite->calendarEvent->isVirtual ? 'Virtual' : 'Presencial' }}</p>
                                    @if ($cite->resume != null)
                                        <p><strong>Resumen de la consulta:</strong> {{ decrypt($cite->resume) }}
                                        </p>
                                    @else
                                        <label for="input-resume">Resumen de la consulta/sesión</label>
                                        <textarea name="resume" id="input-resume" class="form-control"
                                            @if (date_create($cite->calendarEvent->start) > now()) disabled @endif></textarea>
                                    @endif

                                    @include('alerts.feedback', ['field' => 'resume'])
                                </div>
                                <div class="form-group">
                                    @if (!isset($cite->document) && !isset($cite->resume))
                                            <label for="input-document">Adjuntar archivo</label>
                                    <input @if (isset($cite->resume) || date_create($cite->calendarEvent->start) > now())
                                        disabled
                                    @endif type="file" name="document" placeholder="Elegir document" id="document" class="form-control" >
                                    @error('document')
                                        {{ $message }}
                                    @enderror
                                    @else
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong>Nombre:</strong> {{ $cite->document->name }}</p>
                                                <a class="btn bg-primary text-light" href="/medical_history/document/{{ $cite->document->id }}">Descargar</a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                </div>
                                   
                                @if ($calendarEvent->approved == 0)
                                    <div class="form-group">
                                        <label for="input-approved">Estado</label>
                                        <select name="approved" id="input-approved" class="form-control">
                                            <option value="0" @if (old('approved', $calendarEvent->approved == 0)) selected @endif>Pendiente
                                            </option>
                                            <option value="1" @if (old('approved', $calendarEvent->approved != 0)) selected @endif>Aprobado
                                            </option>
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group{{ $errors->has('paid') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-paid">¿Pagó la consulta?</label>
                                    @if (date_create($cite->calendarEvent->start) > now() || isset($cite->resume))
                                        <select disabled="true" class="form-control" name="paid" id="input-paid"
                                            @if (isset($cite->resume)) disabled @endif>
                                        @else
                                            <select class="form-control" name="paid" id="input-paid">
                                    @endif
                                    <option value="1" @if (old('paid', $cite->paid) == 1) selected @endif>Pagó.</option>
                                    <option value="0" @if (old('paid', $cite->paid) == 0) selected @endif>No pagó.</option>
                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('assisted') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-assisted">¿Asistió a la
                                        reunión?</label>
                                    @if (date_create($cite->calendarEvent->start) > now() || isset($cite->resume))
                                        <select class="form-control" name="assisted" id="input-assisted" disabled>
                                        @else
                                            <select class="form-control" name="assisted" id="input-assisted">
                                    @endif
                                    <option value="1" @if (old('assisted', $cite->assisted) == 1) selected @endif>Asistió.
                                    </option>
                                    <option value="0" @if (old('assisted', $cite->assisted) == 0) selected @endif>No asistió.
                                    </option>
                                    </select>
                                </div>

                                @include('alerts.feedback', ['field' => 'assisted'])
                                <input type="hidden" value="{{ $cite->id }}">
                                <div class="text-center">
                                    @if ($cite->resume != null)
                                        <a href="" class="btn bg-primary text-light">Imprimir comprobante</a>
                                    @else
                                        @if (date_create($cite->calendarEvent->start) > now())
                                            <button type="button" class="btn bg-danger text-light mt-4" data-toggle="modal"
                                                data-target="#exampleModal">Cancelar turno</button>
                                        @endif
                                        <button type="submit"
                                            class="btn bg-primary text-light mt-4">{{ __('Guardar') }}</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-user">
                            <div class="card-body">
                                <div class="col text-center">
                                    <div class="row">
                                        <div class="card card-body">
                                            <div class="text-center">
                                                <img class="avatar border-gray mb-2"
                                                    style="max-width:100px; max-height: 100px;"
                                                    src="{{ $cite->medicalHistory->patientProfile->profile->user->pfp != ''? asset('/storage/images/' . explode('/', $cite->medicalHistory->patientProfile->profile->user->pfp)[2]): asset('light-bootstrap/img/default-avatar.png') }}"
                                                    alt="...">
                                            </div>

                                            <p><strong>Paciente:</strong> {{ $cite->calendarEvent->title }}</p>
                                            <div class="col text-center">
                                                <button class="btn bg-primary text-light" data-toggle="modal"
                                                    data-target="#patientModal">Ver info</button>
                                            </div>


                                        </div>




                                    </div>
                                    <p><strong>Tratamiento:</strong>
                                        {{ isset($cite->treatment) ? $cite->treatment->name : 'Ninguno' }}</p>
                                    @if (isset($cite->treatment))
                                        <div class="text-center">
                                            <a class="btn bg-primary text-light"
                                                href="/treatments/{{ $cite->treatment_id }}">Ver tratamiento</a>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="text-center">
                                            <p><strong>Cobertura médica:</strong>
                                                {{ $cite->medicalHistory->patientProfile->lifesheet->coverage->name }}
                                            </p>
                                            <p><strong>Fue cubierto por obra social:</strong>
                                                {{ $cite->covered ? 'Sí' : 'No' }}
                                            </p>
                                            <p><strong>Precio a pagar:</strong>
                                                ${{ isset($cite->total) ? $cite->total : $cite->practice->price->price }}
                                                <span
                                                    class="badge {{ $cite->paid ? 'badge-success' : 'badge-secondary' }}">{{ $cite->paid ? 'Pagado' : 'No pagado' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                            ¿Segura/o que desea cancelar el turno? Se enviará un aviso al paciente.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver
                                atrás.</button>
                            <form method="post" action="/profile/events/delete/{{ $cite->calendarEvent->id }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn bg-danger text-light">Sí, cancelar turno.</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Paciente -->
            <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $cite->calendarEvent->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @php
                                $patient = $cite->medicalHistory->patientProfile;
                            @endphp
                            <p><strong>DNI:</strong> {{ $patient->profile->user->dni }}</p>
                            <p><strong>E-Mail:</strong> {{ $patient->profile->user->email }}</p>
                            <p><strong>Teléfono:</strong> {{ $patient->profile->phone }}</p>
                            <p><strong>Cobertura médica:</strong> {{ $patient->lifesheet->coverage->name }}</p>
                            <p><strong>Fecha de nacimiento:</strong>
                                {{ date_create($patient->profile->bornDate)->format('d/m/Y') }}</p>
                            <p><strong>Domicilio:</strong> {{ $patient->profile->address }} </p>
                            <p><strong>Ciudad:</strong> {{ $patient->profile->city->name }}</p>
                            <p><strong>Ocupación:</strong> {{ $patient->occupation }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver
                                atrás.</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Ayuda -->
            <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ayuda</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Varios de los contenidos relacionados a la realización de esta consulta se disponibilizan a
                                partir del día estipulado.</p>
                            <p><strong>Resumen de la consulta:</strong> al rellenar este campo, la consulta se da por
                                cerrada y no se puede volver a editar el contenido. Esto incluye esta cita en algunos
                                procesos del sistema, como por ejemplo la acumulación de deudas.</p>
                            <p><strong>Práctica a realizar:</strong> esta será la operación o actividad que se realizará en
                                el consultorio a esta fecha y hora. Está basado en el agrupamiento horario definido elegido
                                por el paciente o por usted.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver
                                atrás.</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
