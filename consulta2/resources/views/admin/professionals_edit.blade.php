@extends('layouts.app', ['activePage' => 'professionals', 'title' => 'Consulta2 | Editar profesional', 'navName' => 'Detalles de sesión', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname }}</h3>
                                    <p>Matrícula: {{$professional->licensePlate}}</p>
                                    <p>DNI: {{$professional->profile->user->dni}}</p>
                                    <p>Especialización: {{ $professional->specialty->displayname }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/admin/professionals/store/{{$professional->id}}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <h6 class="heading-small text-muted mb-4">{{ __('Datos de agenda') }}</h6>
                                
                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
        
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="input-isVirtual">Estado</label>
                                        <select class="form-control" name="status" id="input-status">
                                            <option value="1" @if (old('status', $professional->status) == 1) selected @endif>Habilitado</option>
                                            <option value="0" @if (old('status', $professional->status) == 0) selected @endif>Inhabilitado</option>
                                        </select>

                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>
                                    <input type="hidden" value="{{$professional->id}}">
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