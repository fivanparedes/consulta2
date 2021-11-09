@extends('layouts.app', ['activePage' => 'professional_show', 'title' => 'Consulta2 | Ver perfil ', 'navName' => 'Ver perfil', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h2>{{ $professional->name }}  {{ $professional->lastname }}</h2>
                <h3><span class="badge badge-secondary">{{ $professional->field }} </span> <span class="badge badge-secondary">{{ $professional->specialty }}</span></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Reservar fecha</h4>
                <form  id="reserve-form" action="/event/confirm">
                    <input type="date" name="date" id="input-day"/>
                    <select class="form-control" name="consult-type" id="input-consult">
                        @foreach ($consulttypes as $consulttype)
                            <option value="{{ $consulttype->id }}">{{ $consulttype->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="profid" value="{{ $professional->id }}"/>
                    <div class="form-group" id="hour-group">
                        
                    </div>
                    
                    <button onclick="eventclick()" id="submit-button" type="submit">Reservar turno</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#submit-button').prop("disabled",true);
            
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
            $.ajax( {
                url: '/show-available-hours',
                type: 'GET',
                dataType: 'json',
                headers: headers,
                data: {
                    profid: <?php echo $professional->id ?>,
                    consultid: $('#input-consult option:selected').val(),
                    currentdate: $('#input-day').val()
                },
                success: function (response) {
                    $("#hour-group").empty();
                    
                    if (response.length != 0) {
                        let data = response.content;
                        console.log($('#input-day').val());
                        console.log(JSON.stringify(response));
                        if (data != "empty") {
                            $('#submit-button').prop("disabled",true);
                            for (var i = 0; i < data.length; i++) {
                                $("#hour-group").append(
                                '<span class="badge badge-secondary"><input type="radio" name="time" class="input-time" value="'+ data[i] +'" onchange="allowButton()"><p>' + data[i] +'</p></span>'
                                );
                            }
                        } else {
                            $('#hour-group').append('<p>No hay turnos disponibles para este día.</p>');
                        }
                    } 
                }
            });
        }
        function allowButton() {
            $('#submit-button').prop("disabled",false);
        }
        $('#input-day').change(function() {
            toggleBusinessHours();
        });

        $('#input-consult').change(function() {
            toggleBusinessHours();
        });
    </script>
    @endsection
    


   
    