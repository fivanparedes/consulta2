@extends('layouts.app', ['activePage' => 'lifesheet', 'title' => $companyName.' | Hoja de Vida', 'navName' => 'Mi cuenta', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card data-tables">
    
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h1 class="mb-0">Hoja de vida</h1>
                                    <p class="text-sm mb-0">
                                        Rellene esta página con información sobre su salud y estilo de vida actuales.
                                    </p>
                                </div>
                            </div>
                        </div>
    
                        <div class="col-12 mt-2">
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('lifesheet.update') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <h6 class="heading-small text-muted mb-4">{{ __('Enfermedades') }}</h6>
                            
                            @include('alerts.success')
                            @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
    
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('coverage') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="coverage">
                                        <i class="w3-xxlarge fa fa-user"></i>{{ __('Obra Social') }}
                                    </label>
                                    <select class="form-control" name="coverage" id="coverage">
                                        @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->id }}" @if ($coverage == $lifesheet->coverage)
                                            selected
                                        @endif>{{ $coverage->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'diseases'])
                                </div>
                                <div class="form-group{{ $errors->has('diseases') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="diseases">
                                        <i class="w3-xxlarge fa fa-user"></i>{{ __('Enfermedades base') }}
                                    </label>
                                    <textarea name="diseases" id="diseases" class="form-control{{ $errors->has('diseases') ? ' is-invalid' : '' }}" placeholder="{{ __('Ejemplo: diabetes, hipertensión, etc.') }}" value="{{ old('diseases', $lifesheet->diseases) }}" autofocus></textarea>
                                    
                                    @include('alerts.feedback', ['field' => 'diseases'])
                                </div>
                                <div class="form-group{{ $errors->has('surgeries') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="surgeries"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Cirugías/Operaciones') }}</label>
                                    <textarea name="surgeries" id="surgeries" class="form-control{{ $errors->has('surgeries') ? ' is-invalid' : '' }}" placeholder="{{ __('Lista de cirugías.') }}" value="{{ old('surgeries', $lifesheet->surgeries) }}" autofocus></textarea>
                                    
                                    @include('alerts.feedback', ['field' => 'surgeries'])
                                </div>
                                <div class="form-group{{ $errors->has('medication') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="medication">
                                    <i class="w3-xxlarge fa fa-user"></i>{{ __('Medicamentos') }}
                                    </label>
                                    <textarea name="medication" id="medication" class="form-control{{ $errors->has('medication') ? ' is-invalid' : '' }}" placeholder="{{ __('Lista de medicamentos que consuma actualmente.') }}" value="{{ old('medication', $lifesheet->medication) }}" autofocus></textarea>
                                    
                                    @include('alerts.feedback', ['field' => 'medication'])
                                </div>
        
                                <hr class="my-4" />
                                    <h6 class="heading-small text-muted mb-4">{{ __('Estilo de vida') }}</h6>
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('smokes') ? ' has-danger' : '' }}">
                                            <p>¿Fuma?</p>
                                            <label class="form-control-label" for="smokes-no">{{ __('No.') }}</label>
                                            <input type="radio" name="smokes" id="smokes-no" class="form-control{{ $errors->has('smokes') ? ' is-invalid' : '' }}"  value="0" required @if($lifesheet->smokes == 0) checked @endif>
                                            <label class="form-control-label" for="smokes-mid">{{ __('Ocasionalmente.') }}</label>
                                            <input type="radio" name="smokes" id="smokes-mid" class="form-control{{ $errors->has('smokes') ? ' is-invalid' : '' }}"  value="1" required @if($lifesheet->smokes == 1) checked @endif>
                                            <label class="form-control-label" for="smokes-yes">{{ __('Siempre.') }}</label>
                                            <input type="radio" name="smokes" id="smokes-yes" class="form-control{{ $errors->has('smokes') ? ' is-invalid' : '' }}"  value="2" required @if($lifesheet->smokes == 2) checked @endif>
                                            @include('alerts.feedback', ['field' => 'smokes'])
                                        </div>
                                        <div class="form-group{{ $errors->has('drinks') ? ' has-danger' : '' }}">
                                            <p>¿Consume bebidas alcohólicas?</p>
                                            <label class="form-control-label" for="drinks-no">{{ __('No.') }}</label>
                                            <input type="radio" name="drinks" id="drinks-no" class="form-control{{ $errors->has('drinks') ? ' is-invalid' : '' }}"  value="0" required @if($lifesheet->drinks == 0) checked @endif>
                                            <label class="form-control-label" for="drinks-mid">{{ __('Ocasionalmente.') }}</label>
                                            <input type="radio" name="drinks" id="drinks-mid" class="form-control{{ $errors->has('drinks') ? ' is-invalid' : '' }}"  value="1" required @if($lifesheet->drinks == 1) checked @endif>
                                            <label class="form-control-label" for="drinks-yes">{{ __('Siempre.') }}</label>
                                            <input type="radio" name="drinks" id="drinks-yes" class="form-control{{ $errors->has('drinks') ? ' is-invalid' : '' }}"  value="2" required @if($lifesheet->drinks == 2) checked @endif>
                                            @include('alerts.feedback', ['field' => 'drinks'])
                                        </div>
                                        <div class="form-group{{ $errors->has('exercises') ? ' has-danger' : '' }}">
                                            <p>¿Realiza ejercicio?</p>
                                            <label class="form-control-label" for="exercises-no">{{ __('No.') }}</label>
                                            <input type="radio" name="exercises" id="exercises-no" class="form-control{{ $errors->has('exercises') ? ' is-invalid' : '' }}"  value="0" required @if($lifesheet->exercises == 0) checked @endif>
                                            <label class="form-control-label" for="exercises-no">{{ __('Ocasionalmente.') }}</label>
                                            <input type="radio" name="exercises" id="exercises-no" class="form-control{{ $errors->has('exercises') ? ' is-invalid' : '' }}"  value="1" required @if($lifesheet->exercises == 1) checked @endif>
                                            <label class="form-control-label" for="exercises-no">{{ __('Siempre.') }}</label>
                                            <input type="radio" name="exercises" id="exercises-no" class="form-control{{ $errors->has('exercises') ? ' is-invalid' : '' }}"  value="2" required @if($lifesheet->exercises == 2) checked @endif>
                                            @include('alerts.feedback', ['field' => 'exercises'])
                                        </div>
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