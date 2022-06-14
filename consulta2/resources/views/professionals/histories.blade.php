@extends('layouts.app', ['activePage' => 'attendees', 'title' => $companyName.' | Historia médica', 'navName' => 'Mi cuenta', 'activeButton' => 'laravel'])

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
                                    <h3 class="mb-0">{{ __('Historia médica') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ url('/profile/attendees/history/update/'.$history->id)}}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <h6 class="heading-small text-muted mb-4">{{ __('Información de contacto') }}</h6>
                                
                                @include('alerts.success')
                                @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
        
                                <div class="pl-lg-4" id="form-content">
                                    <div class="form-group{{ $errors->has('indate') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="indate">
                                            <i class="w3-xxlarge fa fa-user"></i>{{ __('Fecha de ingreso') }}
                                        </label>
                                        <input type="date" name="indate" id="indate" class="form-control{{ $errors->has('indate') ? ' is-invalid' : '' }}" placeholder="{{ __('Fecha de ingreso') }}" value="{{ old('indate', $history->indate) }}" required autofocus>
        
                                        @include('alerts.feedback', ['field' => 'indate'])
                                    </div>
                                    <div class="form-group{{ $errors->has('visitreason') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-visitreason"><i class="w3-xxlarge fa fa-envelope-o"></i>{{ __('Razón de visita') }}</label>
                                        <input type="text" name="visitreason" id="input-visitreason" class="form-control{{ $errors->has('visitreason') ? ' is-invalid' : '' }}" placeholder="{{ __('Razón de visita') }}" value="{{ old('visitreason', decrypt($history->visitreason)) }}" required>
        
                                        @include('alerts.feedback', ['field' => 'visitreason'])

                                        <a class="btn" id="a-visitreason" ><i class="nc nc-eye">Mostrar</i></a>
                                    </div>
                                    <input type="hidden" name="hid" value="{{ $history->id }}">
                                    <input type="hidden" name="touched" value="0">
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
    <script>
        $('#a-visitreason').click(function() {
            $('input[type=hidden][name=touched]').val(1);
            var touched = $('input[type=hidden][name=touched]').val();
            $.ajax({
                method: 'GET',
                url: '/profile/attendees/history/decryptContent',
                dataType: 'json',
                data: { 
                    hid: <?php echo $history->id ?>,
                    id: <?php echo $history->patientProfile->id ?>,
                    touched: 1
                },
                success: function (response) {
                    if (response.length != 0) {
                        $('#input-visitreason').val(response.text);
                    }
                    
                }
            });
        });
    </script>
@endsection