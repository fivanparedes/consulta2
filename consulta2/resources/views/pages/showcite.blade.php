@extends('layouts.app', ['activePage' => 'cite', 'title' => $companyName.' | Ver sesión', 'navName' => 'Detalles de sesión',
'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="section-image">
                <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
                <div class="row">

                    <div class="card col-md-10">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col col-md-8">
                                    <h3 class="mb-0">{{ 'Sesión N° ' . $cite->id }}</h3>
                                    <p><strong>Paciente:</strong> {{ $cite->calendarEvent->title }}</p>
                                    <p><strong>Fecha de turno:</strong>
                                        {{ date_create($cite->calendarEvent->start)->format('d/m/Y h:i') }}</p>
                                    <p><strong>Cobertura médica:</strong>
                                        {{ $cite->medicalHistory->patientProfile->lifesheet->coverage->name }}</p>
                                    <p><strong>Fue cubierto por obra social:</strong> {{ $cite->covered ? 'Sí' : 'No' }}
                                    </p>
                                    <p><strong>Tratamiento:</strong>
                                        {{ isset($cite->treatment) ? $cite->treatment->name : 'Ninguno' }}</p>
                                    <p><strong>Precio a pagar:</strong>
                                        ${{ isset($cite->total) ? $cite->total : $cite->practice->price->price }} <span
                                            class="badge {{ $cite->paid ? 'badge-success' : 'badge-secondary' }}">{{ $cite->paid ? 'Pagado' : 'No pagado' }}</span>
                                    </p>
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
                                <div class="col flex">
                                    <div class="row">
                                        <div class="col text-center">
                                            <img class="avatar border-gray mb-2" style="min-width:150px; min-height: 150px;"
                                                src="{{ $cite->medicalHistory->patientProfile->profile->user->pfp != ''? asset('/storage/images/' . explode('/', $cite->medicalHistory->patientProfile->profile->user->pfp)[2]): asset('light-bootstrap/img/default-avatar.png') }}"
                                                alt="...">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
                                            <button class="btn bg-primary text-light" data-toggle="modal"
                                                data-target="#patientModal">Ver información de contacto</button>
                                        </div>

                                    </div>
                                    {{-- <div class="row">
                                        @if (date_create($cite->calendarEvent->start) < now())
                                            <div class="col">
                                                <button class="btn bg-primary text-light" data-toggle="modal"
                                                    data-target="#quickCloseModal">Cierre rápido</button>
                                            </div>
                                        @endif
                                    </div> --}}
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="/cite/update/{{ $cite->id }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="row">
                                    <div class="col">
                                        <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn bg-primary text-light" data-toggle="modal"
                                            data-target="#helpModal"><i class="fa fa-question"></i></button>
                                    </div>
                                </div>


                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('assisted') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-assisted">¿Asistió a la
                                            reunión?</label>
                                        @if (date_create($cite->calendarEvent->start) > now())
                                            <select disabled="true" class="form-control" name="assisted"
                                                id="input-assisted">
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

                                </div>
                                <div class="form-group{{ $errors->has('resume') ? ' has-error' : '' }}">

                                    @if ($cite->resume != null)
                                        <p><strong>Resumen de la sesión/consulta:</strong> {{ decrypt($cite->resume) }}
                                        </p>
                                    @else
                                        <label for="input-resume">Resumen de la consulta/sesión</label>
                                        <textarea name="resume" id="input-resume" class="form-control" @if (date_create($cite->calendarEvent->start) > now()) disabled @endif></textarea>
                                    @endif

                                    @include('alerts.feedback', ['field' => 'resume'])
                                </div>
                                <div class="form-group{{ $errors->has('isVirtual') ? ' has-error' : '' }}">
                                    <label for="input-isVirtual">Modalidad</label>
                                    <select class="form-control" name="isVirtual" id="input-isVirtual">
                                        <option value="1" @if (old('isVirtual', $cite->isVirtual) == 1) selected @endif>Virtual</option>
                                        <option value="0" @if (old('isVirtual', $cite->isVirtual) == 0) selected @endif>Presencial
                                        </option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'isVirtual'])
                                </div>
                                <div class="form-group">
                                    <label for="input-approved">Estado</label>
                                    <select name="approved" id="input-approved" class="form-control"
                                        @if (isset($cite->resume)) disabled @endif>
                                        <option value="0" @if (old('approved', $calendarEvent->approved == 0)) selected @endif>Pendiente
                                        </option>
                                        <option value="1" @if (old('approved', $calendarEvent->approved != 0)) selected @endif>Aprobado
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="input-practice">¿Qué actividad se realizará?</label>
                                    <select name="practice" id="input-practice" class="form-control"
                                        @if (isset($cite->resume)) disabled @endif>
                                        @foreach ($calendarEvent->consultType->practices as $practice)
                                            <option value="{{ $practice->id }}"
                                                @if ($practice == $cite->practice) selected @endif>{{ $practice->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('paid') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-paid">¿Pagó la consulta?</label>
                                    @if (date_create($cite->calendarEvent->start) > now())
                                        <select disabled="true" class="form-control" name="paid" id="input-paid"
                                            @if (isset($cite->resume)) disabled @endif>
                                        @else
                                            <select class="form-control" name="paid" id="input-paid">
                                    @endif
                                    <option value="1" @if (old('paid', $cite->paid) == 1) selected @endif>Pagó.</option>
                                    <option value="0" @if (old('paid', $cite->paid) == 0) selected @endif>No pagó.</option>
                                    </select>
                                </div>
                                <input type="hidden" value="{{ $cite->id }}">
                                <div class="text-center">
                                    @if ($cite->resume != null)
                                        <a href="" class="btn bg-primary text-light">Imprimir comprobante</a>
                                    @else
                                        <button type="button" class="btn bg-danger text-light mt-4" data-toggle="modal"
                                            data-target="#exampleModal">Cancelar turno</button>
                                        <button type="submit"
                                            class="btn bg-primary text-light mt-4">{{ __('Guardar') }}</button>
                                    @endif
                                </div>
                            </form>
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
            <!-- Modal Cierre rapido -->
            <div class="modal fade" id="quickCloseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            <p><strong>ADVERTENCIA</strong> cerrar el registro rápidamente hará que la consulta se considere
                                confirmada, asistida y pagada para evitar generar cargas adicionales al paciente.</p>
                            <p>¿Desea continuar?</p>
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
