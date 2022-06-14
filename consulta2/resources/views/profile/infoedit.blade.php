@extends('layouts.app', ['activePage' => 'infoedit', 'title' => $companyName.' | Datos personales', 'navName' => 'Mi cuenta', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ __('Información personal') }}</h3>
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
                                    <div class="form-group{{ $errors->has('bornDate') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="bornDate">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Fecha de nacimiento') }}
                                        </label>
                                        <input type="date" name="bornDate" id="bornDate" class="form-control{{ $errors->has('bornDate') ? ' is-invalid' : '' }}" placeholder="{{ __('Fecha de nacimiento') }}" value="{{ old('bornDate', $user_profile->bornDate) }}" required autofocus>
        
                                        @include('alerts.feedback', ['field' => 'bornDate'])
                                    </div>
                                    <div class="form-group{{ $errors->has('gender') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-gender"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Género') }}</label>
                                        <select name="gender" id="input-gender" class="form-control">
                                            <option value="Femenino" @if (old('gender', $user_profile->gender) == "Femenino")
                                                selected
                                            @endif>Femenino</option>
                                            <option value="Masculino" @if (old('gender', $user_profile->gender) == "Masculino")
                                                selected
                                            @endif>Masculino</option>
                                            <option value="Otro" @if (old('gender', $user_profile->gender) == "Otro")
                                                selected
                                            @endif>Otro</option>
                                        </select>
                                        @include('alerts.feedback', ['field' => 'gender'])
                                    </div>
                                    <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="phone">
                                        <i class="w3-xxlarge fa fa-user"></i>{{ __('Teléfono') }}
                                        </label>
                                        <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('Número de teléfono') }}" value="{{ old('phone', $user_profile->phone) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'phone'])
                                    </div>
                                    <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-address"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Domicilio') }}</label>
                                        <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Domicilio') }}" value="{{ old('address', $user_profile->address) }}" required>
        
                                        @include('alerts.feedback', ['field' => 'address'])
                                    </div>
                                    <hr class="my-4" />
                                    {{-- Este formulario aparece si el usuario esta logueado como paciente --}}
                                    @if (isset($patient_profile))
                                        <h6 class="heading-small text-muted mb-4">{{ __('Datos del paciente') }}</h6>
                                        <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('bornPlace') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="bornPlace">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Lugar de nacimiento') }}
                                            </label>
                                            <input type="text" name="bornPlace" id="bornPlace" class="form-control{{ $errors->has('bornPlace') ? ' is-invalid' : '' }}" placeholder="{{ __('Ciudad, Provincia, Pais') }}" value="{{ old('bornPlace', $patient_profile->bornPlace) }}" required autofocus>
    
                                            @include('alerts.feedback', ['field' => 'bornPlace'])
                                        </div>
                                        <div class="form-group{{ $errors->has('familyGroup') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-familyGroup"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Grupo familiar conviviente') }}</label>
                                            <input type="text" name="familyGroup" id="input-familyGroup" class="form-control{{ $errors->has('familyGroup') ? ' is-invalid' : '' }}" placeholder="{{ __('Madre, Padre, hermanos / Solo/a / En pareja') }}" value="{{ old('familyGroup', $patient_profile->familyGroup) }}" required>
    
                                            @include('alerts.feedback', ['field' => 'familyGroup'])
                                        </div>
                                        <div class="form-group{{ $errors->has('familyPhone') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-familyPhone"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Teléfono de un familiar cercano') }}</label>
                                            <input type="text" name="familyPhone" id="input-familyPhone" class="form-control{{ $errors->has('familyPhone') ? ' is-invalid' : '' }}" placeholder="{{ __('N° Teléfono') }}" value="{{ old('familyPhone', $patient_profile->familyPhone) }}">
    
                                            @include('alerts.feedback', ['field' => 'familyPhone'])
                                        </div>
                                        <div class="form-group{{ $errors->has('civilState') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-civilState"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Estado civil') }}</label>
                                            <input type="text" name="civilState" id="input-civilState" class="form-control{{ $errors->has('civilState') ? ' is-invalid' : '' }}" placeholder="{{ __('Solter@/Casad@/Divorciad@/Viud@') }}" value="{{ old('civilState', $patient_profile->civilState) }}">
    
                                            @include('alerts.feedback', ['field' => 'civilState'])
                                        </div>
                                        <div class="form-group{{ $errors->has('scholarity') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-scholarity"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Escolaridad') }}</label>
                                            <input type="text" name="scholarity" id="input-scholarity" class="form-control{{ $errors->has('scholarity') ? ' is-invalid' : '' }}" placeholder="{{ __('Ejemplo: Secundario completo') }}" value="{{ old('scholarity', $patient_profile->scholarity) }}">
    
                                            @include('alerts.feedback', ['field' => 'scholarity'])
                                        </div>
                                        <div class="form-group{{ $errors->has('occupation') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-occupation"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Ocupación') }}</label>
                                            <input type="text" name="occupation" id="input-occupation" class="form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" placeholder="{{ __('Empleado de atención al público en La Verde S.A.') }}" value="{{ old('occupation', $patient_profile->occupation) }}">
    
                                            @include('alerts.feedback', ['field' => 'occupation'])
                                        </div>

                                    {{-- formulario correspondiente al profesional. --}}
                                    {{-- TODO: comprobar por ROLE_ID en vez de PROFILE_ID--}}
                                    @elseif (isset($professional_profile))

                                        <h6 class="heading-small text-muted mb-4">{{ __('Datos del profesional') }}</h6>
                                        <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('licensePlate') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="licensePlate">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Matrícula profesional') }}
                                            </label>
                                            <input type="text" name="licensePlate" id="licensePlate" class="form-control{{ $errors->has('licensePlate') ? ' is-invalid' : '' }}" placeholder="{{ __('Matrícula N°') }}" value="{{ old('licensePlate', $professional_profile->licensePlate) }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'licensePlate'])
                                        </div>
                                        <div class="form-group{{ $errors->has('field') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-field"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Campo de estudio') }}</label>
                                            <input type="text" name="field" id="input-field" class="form-control{{ $errors->has('field') ? ' is-invalid' : '' }}" placeholder="{{ __('Ej: Psicología/Odontología/etc.') }}" value="{{ old('field', $professional_profile->field) }}" required>

                                            @include('alerts.feedback', ['field' => 'field'])
                                        </div>
                                        <div class="form-group{{ $errors->has('specialty') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-specialty"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Especialización') }}</label>
                                            <select name="specialty" id="input-specialty" class="form-control{{ $errors->has('specialty') ? ' is-invalid' : ''}}">
                                                @foreach (\App\Models\Specialty::all() as $specialty)
                                                    <option value="{{ $specialty->id }}" @if($specialty->id == $professional_profile->specialty_id) selected @endif >{{ $specialty->displayname }}</option>
                                                @endforeach
                                            </select>
                                            @include('alerts.feedback', ['field' => 'specialty'])
                                        </div>
                                        <div class="form-group{{ $errors->has('coverages') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-coverages"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Obras sociales aceptadas') }}</label>
                                            <select multiple name="coverages[]" id="input-coverages" class="form-control{{ $errors->has('coverages') ? ' is-invalid' : ''}}">
                                                @foreach (\App\Models\Coverage::all() as $coverage)
                                                    <option value="{{ $coverage->id }}" @if($professional_profile->coverages->contains($coverage)) selected @endif >{{ $coverage->name }}</option>
                                                @endforeach
                                            </select>
                                            @include('alerts.feedback', ['field' => 'coverages[]'])
                                        </div>
                                    </div>
                                    @endif 
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