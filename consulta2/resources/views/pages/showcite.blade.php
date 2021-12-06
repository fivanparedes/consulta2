@extends('layouts.app', ['activePage' => 'cite', 'title' => 'Consulta2 | Ver sesión', 'navName' => 'Detalles de sesión',
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
                                <div class="col-md-8">
                                    <h3 class="mb-0">{{ 'Sesión N° ' . $cite->id }}</h3>
                                    <p>Paciente: {{ $cite->calendarEvent->title }}</p>
                                    <p>Fecha de turno: {{ $cite->calendarEvent->start }}</p>
                                    @if (date_create($calendarEvent->start) > date_create('now'))
                                        <div class="alert alert-warning">
                                            <button type="button" aria-hidden="true" class="close"
                                                data-dismiss="alert">
                                                <i class="nc-icon nc-simple-remove"></i>
                                            </button>
                                            <span>
                                                <b> Información: </b> Faltan
                                                {{ date_diff(date_create($calendarEvent->start), date_create(now()))->format('%d') }}
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

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>

                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                                <div class="pl-lg-4">

                                    <div class="form-group{{ $errors->has('assisted') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-assisted">¿Asistió a la
                                            reunión?</label>
                                        @if (date_diff(date_create($calendarEvent->start), date_create(now()))->format('%d')
                                        > 0)
                                        <select disabled="true" class="form-control" name="assisted" id="input-assisted">
                                        @else
                                            <select class="form-control" name="assisted" id="input-assisted">
                                                @endif
                                                <option value="1" @if (old('assisted', $cite->assisted) == 1) selected @endif>Asistió.</option>
                                                <option value="0" @if (old('assisted', $cite->assisted) == 0) selected @endif>No asistió.</option>
                                            </select>
                                    </div>

                                    @include('alerts.feedback', ['field' => 'assisted'])

                                </div>
                                <div class="form-group{{ $errors->has('isVirtual') ? ' has-error' : '' }}">
                                    <label for="input-isVirtual">Modalidad</label>
                                    <select class="form-control" name="isVirtual" id="input-isVirtual">
                                        <option value="1" @if (old('isVirtual', $cite->isVirtual) == 1) selected @endif>Virtual</option>
                                        <option value="0" @if (old('isVirtual', $cite->isVirtual) == 0) selected @endif>Presencial</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'isVirtual'])
                                </div>
                                <div class="form-group">
                                    <label for="input-approved">Estado</label>
                                    <select name="approved" id="input-approved" class="form-control">
                                        <option value="0" @if (old('approved', $calendarEvent->approved == 0))
                                            selected
                                            @endif>Pendiente</option>
                                        <option value="1" @if (old('approved', $calendarEvent->approved != 0))
                                            selected
                                            @endif>Aprobado</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="input-practice">¿Qué actividad se realizará?</label>
                                    <select name="practice" id="input-practice" class="form-control">
                                        @foreach ($calendarEvent->consultType->practices as $practice)
                                            <option value="{{ $practice->id }}" @if ($practice == $cite->practice)
                                                selected
                                        @endif>{{ $practice->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" value="{{ $cite->id }}">
                                <div class="text-center">
                                    <button type="button" class="btn bg-danger text-light mt-4" data-toggle="modal"
                                        data-target="#exampleModal">Cancelar turno</button>
                                    <button type="submit"
                                        class="btn bg-primary text-light mt-4">{{ __('Guardar') }}</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            </div>
        </div>
    </div>
@endsection
