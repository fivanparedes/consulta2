@extends('layouts.app', ['activePage' => 'patient_events', 'title' => 'Consulta2 | Ver turno', 'navName' => 'Detalles de sesión', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ 'Turno N° '.$event->id }}</h3>
                                    <p>Prestador/a: {{$event->professionalProfile->profile->user->name . ' ' . $event->professionalProfile->profile->user->lastname}}</p>
                                    <p>Fecha de turno: {{$event->start}}</p>
                                    @if (date_create($event->start) > date_create('now'))
                                    <div class="alert alert-warning">
                                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                                            <i class="nc-icon nc-simple-remove"></i>
                                        </button>
                                        <span>
                                            <b> Información: </b> Faltan {{date_diff(date_create($event->start), date_create(now()))->format('%d')}} días para llevarse a cabo esta reunión.</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>
                                
                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
        
                                <div class="pl-lg-4">
                                    
                                    <div class="form-group{{ $errors->has('isVirtual') ? ' has-error' : '' }}">
                                        <label for="input-isVirtual">Modalidad</label>
                                        <select class="form-control" name="isVirtual" id="input-isVirtual">
                                            <option value="1" @if (old('isVirtual', $event->cite->isVirtual) == 1) selected @endif>Virtual</option>
                                            <option value="0" @if (old('isVirtual', $event->cite->isVirtual) == 0) selected @endif>Presencial</option>
                                        </select>

                                        @include('alerts.feedback', ['field' => 'isVirtual'])
                                    </div>
                                    <div class="text-center">
                                        
                                    </div>
                                </div>
                            </form>
                            <button id="btn-cancelar" class="btn bg-danger text-light mt-4" data-toggle="modal" data-target="#exampleModal">{{ __('Cancelar turno') }}</button>
                        </div>
                    </div>
                    <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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