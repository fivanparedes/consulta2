@extends('layouts.app', ['activePage' => 'cite', 'title' => 'Consulta2 | Ver sesión', 'navName' => 'Detalles de sesión', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ 'Sesión N° '.$cite->id }}</h3>
                                    <p>Paciente: {{$cite->calendarEvent->title}}</p>
                                    <p>Fecha de turno: {{$cite->calendarEvent->start}}</p>
                                    @if (date_diff(date_create($calendarEvent->start), date_create(now()))->format('%d') > 0)
                                    <div class="alert alert-warning">
                                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                                            <i class="nc-icon nc-simple-remove"></i>
                                        </button>
                                        <span>
                                            <b> Información: </b> Faltan {{date_diff(date_create($calendarEvent->start), date_create(now()))->format('%d')}} días para llevarse a cabo esta reunión.</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="/cite/update/{{$cite->id}}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>
                                
                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
        
                                <div class="pl-lg-4">
                                    
                                    <div class="form-group{{ $errors->has('assisted') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-assisted">¿Asistió a la reunión?</label>
                                            @if (date_diff(date_create($calendarEvent->start), date_create(now()))->format('%d') > 0)
                                            <select disabled="true" class="form-control" name="assisted" id="input-assisted">
                                            @else
                                            <select  class="form-control" name="assisted" id="input-assisted">
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
                                    <input type="hidden" value="{{$cite->id}}">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-default mt-4">{{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- <div class="col-md-4">
                        <div class="card card-user">
                            <div class="card-image">
                                <img src="https://ununsplash.imgix.net/photo-1431578500526-4d9613015464?fit=crop&fm=jpg&h=300&q=75&w=400" alt="...">
                            </div>
                            <div class="card-body">
                                <div class="author">
                                    <a href="#">
                                        <img class="avatar border-gray" src="{{ asset('light-bootstrap/img/faces/face-3.jpg') }}" alt="...">
                                        <h5 class="title">{{ __('Mike Andrew') }}</h5>
                                    </a>
                                    <p class="description">
                                        {{ __('michael24') }}
                                    </p>
                                </div>
                                <p class="description text-center">
                                {{ __(' "Lamborghini Mercy') }}
                                    <br> {{ __('Your chick she so thirsty') }}
                                    <br> {{ __('I am in that two seat Lambo') }}
                                </p>
                            </div>
                            <hr>
                            <div class="button-container mr-auto ml-auto">
                                <button href="#" class="btn btn-simple btn-link btn-icon">
                                    <i class="fa fa-facebook-square"></i>
                                </button>
                                <button href="#" class="btn btn-simple btn-link btn-icon">
                                    <i class="fa fa-twitter"></i>
                                </button>
                                <button href="#" class="btn btn-simple btn-link btn-icon">
                                    <i class="fa fa-google-plus-square"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection