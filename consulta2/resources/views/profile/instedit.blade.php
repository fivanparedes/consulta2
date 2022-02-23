@extends('layouts.app', ['activePage' => 'infoedit', 'title' => 'Consulta2 | Datos institucionales', 'navName' => 'Mi cuenta', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ __('Información institucional') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('generalinfo.update') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Información de contacto') }}</h6>
                                
                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
        
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Nombre del establecimiento') }}
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre del centro de salud') }}" value="{{ old('name', $institution->name) }}" required autofocus>
        
                                        @include('alerts.feedback', ['field' => 'name'])
                                    </div>
                                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-description"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Descripción del establecimiento') }}</label>
                                        <input type="text" name="description" id="input-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Incluir breve descripción con rubros, prácticas, etc.') }}" value="{{ old('description', $institution->description) }}" required>
        
                                        @include('alerts.feedback', ['field' => 'gender'])
                                    </div>
                                    <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="phone">
                                        <i class="w3-xxlarge fa fa-user"></i>{{ __('Teléfono') }}
                                        </label>
                                        <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('Número de teléfono') }}" value="{{ old('phone', $institution->phone) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'phone'])
                                    </div>
                                    <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-address"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Domicilio') }}</label>
                                        <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Domicilio') }}" value="{{ old('address', $institution->address) }}" required>
        
                                        @include('alerts.feedback', ['field' => 'gender'])
                                    </div>
                                    <hr class="my-4" />
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-default mt-4">{{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection