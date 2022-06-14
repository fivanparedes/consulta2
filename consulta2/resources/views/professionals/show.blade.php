@extends('layouts.app', ['activePage' => 'professional_show', 'title' => $companyName.' | Ver perfil ', 'navName' => 'Ver
perfil', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h2>{{ $professional->profile->user->name }} {{ $professional->profile->user->lastname }}</h2>
                        <h3><span class="badge badge-secondary">{{ $professional->specialty->displayname }}
                                ({{ $professional->field }}) </span></h3>
                    </div>
                    <div class="col align-self-mid">
                        <div class="row">
                            <div class="col">
                                <p><strong>Teléfono:</strong> {{ $professional->profile->phone }}</p>
                                <p><strong>Correo:</strong> {{ $professional->profile->user->email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            @if ($professional->institution_id != 1)
                                <div class="col"><a class="btn bg-primary text-light"
                                        href="{{ url('/institution/show/' . $professional->institution->id) }}">Ver lugar
                                        de
                                        trabajo</a></div>
                            @endif

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @if ($covered)
                            <strong class="text-primary">¡Este profesional atiende con tu obra social!</strong>
                        @else
                            @if (Auth::user()->profile->patientProfile->lifesheet->coverage->id == 1)
                                <strong class="text-dark">Atención particular.</strong>
                            @else
                                <strong class="text-dark">Este profesional no atiende con tu obra social.</strong>
                            @endif

                        @endif
                        @include('alerts.errors')
                    </div>
                </div>


            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Reservar fecha</h4>
                <form id="reserve-form" action="/event/confirm">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label" for="input-day">Elegir fecha:</label>
                                <input class="datepicker form-control" type="date" name="date" id="input-day"
                                    value="{{ date('Y-m-d', strtotime(now() . '+1 days')) }}"
                                    min="{{ date('Y-m-d', strtotime(now())) }}"
                                    max="{{ date('Y-m-d', strtotime(now() . ' + 15 days')) }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="input-isVirtual">Modalidad disponible:</label>
                                <select class="form-control" name="isVirtual" id="input-isVirtual" disabled>
                                    <option value="0">Presencial</option>
                                    <option value="1">Virtual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="input-consult">Tipo de consulta:</label>
                                <select class="form-control" name="consult-type" id="input-consult">
                                    @if ($consulttypes->count() > 0)
                                        @foreach ($consulttypes as $consulttype)
                                        <option value="{{ $consulttype->id }}">{{ $consulttype->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="0">Este profesional requiere derivación previa.</option>
                                    @endif
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="profid" value="{{ $professional->id }}" />
                                <input type="hidden" name="practice" value="" id="practice-field">
                                <div class="form-group" id="hour-group">
                                    <div class="form-group">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <div class="card bg-secondary text-light">
                                <div class="card-body">
                                    <div class="form-group" id="coverage-line"></div>
                                    <div class="form-group" id="price-line"> </div>
                                    <input type="hidden" name="price">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a class="btn bg-secondary text-light" href="{{ url('/professionals/list') }}">Atrás</a>
                        <button class="btn btn-primary" onclick="eventclick()" id="submit-button" type="submit">Reservar
                            turno</button>
                    </div>





                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#submit-button').prop("disabled", true);
            toggleBusinessHours();

        });

        function eventclick() {
            if ($('input[type=radio][name=time]').val() != null) {
                $('#reserve-channel').submit();
            }
        }

        function toggleBusinessHours() {
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            $.ajax({
                url: '/show-available-hours',
                type: 'GET',
                dataType: 'json',
                headers: headers,
                data: {
                    profid: <?php echo $professional->id; ?>,
                    consultid: $('#input-consult option:selected').val(),
                    currentdate: $('#input-day').val()
                },
                success: function(response) {
                    $("#hour-group").empty();
                    $('#price-line').empty();
                    if (response.length != 0) {
                        let data = response.content;
                        let price = response.copayment;
                        console.log($('#input-day').val());
                        console.log(JSON.stringify(response));
                        if (data != "empty") {
                            $('#practice-field').val(response.practice);
                            $('#submit-button').prop("disabled", true);
                            let phpcovered = "<?php echo $covered; ?>";
                            if (response.covered == 0) {
                                $("#price-line").append('<strong>Precio:</strong><p>$' + response.price +
                                    '</p>');
                                if (phpcovered) {
                                    $("#coverage-line").append(
                                        '<strong>Su obra social no cubre esta operación.</strong>');
                                }
                                $('input[type=hidden][name=price]').val(response.price);
                            } else {
                                if (phpcovered) {
                                    $("#coverage-line").empty();
                                    $("#coverage-line").append('<strong>Como afiliado debe abonar:.</strong>');
                                }
                                $("#price-line").append('<strong>Precio:</strong><p>$' + response.price +
                                    '</p>');
                                $('input[type=hidden][name=price]').val(response.price);
                            }
                            for (var i = 0; i < data.length; i++) {
                                $("#hour-group").append(
                                    '<label class="btn bg-primary text-light" style="height: 50px;"><input type="radio" name="time" class="hour-button" value="' +
                                    data[i] + '" onchange="allowButton()"><p>' + data[i].substring(0, 5) +
                                    '</p></label>'
                                );
                            }
                            var allowed_modes = response.allowed_modes;
                            switch (allowed_modes) {
                                case 0:
                                    $('#input-isVirtual').val(0);
                                    $('#input-isVirtual').prop("disabled", true);
                                    break;
                                case 1:
                                    $('#input-isVirtual').val(1);
                                    $('#input-isVirtual').prop("disabled", true);
                                case 2:
                                    $('#input-isVirtual').prop("disabled", false);
                                    break;
                            }
                        } else {
                            $('#hour-group').append('<p>No hay turnos disponibles para este día.</p>');
                        }
                    }
                }
            });
        }

        function allowButton() {
            $('#submit-button').prop("disabled", false);
        }
        $('#input-day').change(function() {
            toggleBusinessHours();
        });

        $('#input-consult').change(function() {
            toggleBusinessHours();
        });
    </script>
@endsection
