@extends('layouts.app', ['activePage' => 'consult_types', 'title' => $companyName.' | Agregar tipo de consulta', 'navName'
=> 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Configurar horario nuevo <button type="button" class="btn bg-primary text-light"
                                data-toggle="modal" data-target="#addModal"><i class="fa fa-question-circle"></i></button></h2>
                                <p>Asigne horas de trabajo a un conjunto de prácticas o consultas, que los pacientes podrán
                                    visualizar al agendar un turno.</p>
                                <p>En lo posible, elegir una práctica de cada obra social, incluyendo atención particular.
                                </p>

                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ route('consult_types.store') }}" method="post">
                @csrf
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre del tipo de consulta</label>
                            <input class="form-control" type="text" name="name" id="input-name"
                                placeholder="Ej: Videoconsultas">
                        </div>
                        <div class="form-group">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p style="font-size: 85%"><i class="fa fa-info"></i>Si no se encuentra la
                                            práctica u obra
                                            social en el listado, contacte con el administrador o su institución.</p>
                                        <label for="input-practice">Practica profesional</label>
                                        <select id="input-practice" class="form-control" name="practice">
                                            <option value="0">Seleccione una práctica de la lista</option>
                                            @foreach ($professional->coverages as $coverage)
                                                <optgroup label="{{ $coverage->name }}">
                                                    @foreach ($coverage->practices as $practice)
                                                        <option value="{{ $practice->id }}">{{ $practice->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="add-btn" onclick="addItem()" type="button"
                                            class="btn btn-primary text-light" disabled>Agregar a la lista</button>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="form-control" multiple="multiple" name="selected_practices[]"
                                            id="selected-practices">

                                        </select>


                                    </div>
                                    <div class="form-group">
                                        <button id="rem-btn" onclick="removeItem()" type="button"
                                            class="btn btn-primary text-light" disabled>Sacar de la lista</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card" id="card-two">
                        <div class="card-body">
                            <div class="form-group">
                                <h4>Disponibilidad horaria <button type="button" class="btn bg-primary text-light"
                                        style="size: 70%" data-toggle="modal" data-target="#secondModal"><i
                                            class="fa fa-question-circle"></i></button></h4>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-monday" id="av-monday">
                                    <label class="form-check-label" for="av-monday"><strong>Lunes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-tuesday" id="av-tuesday">
                                    <label class="form-check-label" for="av-tuesday"><strong>Martes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-wednesday" id="av-wednesday">
                                    <label class="form-check-label" for="av-wednesday"><strong>Miércoles</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-thursday" id="av-thursday">
                                    <label class="form-check-label" for="av-thursday"><strong>Jueves</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-friday" id="av-friday">
                                    <label class="form-check-label" for="av-friday"><strong>Viernes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-saturday" id="av-saturday">
                                    <label class="form-check-label" for="av-saturday"><strong>Sábado</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-sunday" id="av-sunday">
                                    <label class="form-check-label" for="av-sunday"><strong>Domingo</strong></label>
                                </div>
                                <hr>
                                <div class="form-group" id="hour-checks">
                                    <select name="hour-criteria" id="hour-criteria"
                                        onchange="retrieveAvailableHours(this.value)" class="form-control">
                                        <option value="0">Seleccione horas disponibles</option>
                                        <option value="1">Cada 1 hora</option>
                                        <option value="2">Cada 30 minutos</option>
                                        <option value="3">Cada 20 minutos</option>
                                        <option value="4">Cada 10 minutos</option>
                                        <option value="5">Cada 5 minutos</option>
                                        <option value="6">Todas las horas disponibles</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div id="hour-choices" class="row">

                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <h4>Opciones de autogestión</h4>

                                    <p style="font-size: 85%"><i class="fa fa-info"></i>Estas opciones influyen en la
                                        pantalla de agendamiento para el paciente.</p>
                                    <div class="form-check-inline">

                                        <label class="form-check-label" for="input-visible"><input type="checkbox"
                                                class="form-check-input" name="visible" id="input-visible" checked>¿Será
                                            visible al
                                            usuario?</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" class="form-check-input" name="requires_auth"
                                            id="input-requires_auth">
                                        <label class="form-check-label" for="input-requires_auth">¿Debo autorizar los
                                            turnos manualmente?</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button id="btn-next" class="btn btn-danger text-light"> Cancelar</button>
                                    <button onclick="sendForm()" class="btn btn-light text-dark">Guardar horarios de
                                        consulta</button>
                                </div>
                            </div>


                        </div>
                    </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ayuda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p><strong>Nombre: </strong>Nombre que se le mostrará al paciente al elegir este grupo horario.</p>
                    <strong>Prácticas:</strong>
                    <p>Cada obra social o cobertura maneja su propio listado de prácticas clínicas para la especialidad a la
                        que usted se dedica. Procure agregar una por cada obra social que usted maneje y, en lo posible,
                        agregue una para los pacientes que se atiendan particularmente.</p>
                    <p>Si crea el grupo horario sin incluir una práctica de atención particular, el precio que se le cobrará
                        al paciente será de la primer práctica encontrada en el listado.</p>
                    <p>Si el paciente está cubierto con una de las obras sociales que usted maneja y no incluye la práctica
                        correspondiente, se le cobrará como particular.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok.</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="secondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ayuda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <strong>Disponibilidad horaria:</strong>
                    <p>Tiene a su disposición la opción de fijar cualquier día de la semana y rango horario. Cabe destacar
                        que el rango horario elegido es el mismo para todos los días seleccionados. Si usted necesita fijar
                        excepciones como feriados, <a class="text-primary">toque aquí.</a></p>
                        <strong>¿Será visible al usuario?</strong>
                    <p>Si está marcada, el paciente podrá ver este grupo horario para elegir un turno, usualmente para los
                        casos en donde se pueda agendar libremente, como por ejemplo la primera consulta. En caso de no
                        marcar esta casilla, usted podrá asignar turnos a este grupo horario por medio del <a href=""
                            class="text-primary">agendamiento manual</a> .</p>
                    <strong>¿Debo autorizar manualmente?</strong>
                    <p>Si el paciente solicita un turno, este quedará en estado pendiente y usted tendrá que autorizarlo.
                        Útil para los casos donde existe una excesiva demanda o se necesiten priorizar ciertos pacientes. Si
                        no está marcada la casilla, el paciente agendará el turno al instante y se ocupará el lugar en el
                        calendario.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok.</button>
                </div>
            </div>

        </div>
    </div>
    </div>
    <script>
        function retrieveAvailableHours(value) {
            if (value != 0) {
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                $('#hour-choices').empty();
                $.ajax({
                    type: 'GET',
                    url: '/getAvailableHours',
                    dataType: 'json',
                    headers: headers,
                    data: {
                        choice: value
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (response.status == 'success') {
                            let data = response.data;
                            for (let i = 0; i < data.length; i++) {
                                $('#hour-choices').append(
                                    '<div class="col"><div class="form-check-inline"><input type="checkbox" class="form-check-input" name="business_hours[]" id="bhour-' +
                                    data[i].time + '" value="' + data[i].time +
                                    '" ><label class="form-check-label" for="bhour-' + data[i].time +
                                    '"><strong>' + data[i].time.substring(0, 5) +
                                    '</strong></label></div></div>');
                            }
                        }
                    }
                });
            }
        }
        $('#input-practice').change(function() {
            if ($('#input-practice option:selected').val() == 0) {
                $('#add-btn').attr('disabled', true);
            } else {
                $('#add-btn').attr('disabled', false);
            }
        });

        $('#selected-practices').change(function() {
            $('#rem-btn').prop('disabled', false);
        });

        function addItem() {
            var practiceId = $('#input-practice option:selected').val();
            var practiceName = $('#input-practice option:selected').html();
            if (practiceId != undefined && practiceName != undefined) {
                $('#input-practice option[value="' + practiceId + '"]').hide();
                $('#input-practice option[value=0]').attr('selected', true);
                $('#selected-practices').append('<option value="' + practiceId + '">' + practiceName + '</option>');
                $('#add-btn').attr('disabled', true);
                $('#rem-btn').attr('disabled', true);
            }
        }

        function removeItem() {
            var practiceId = $('#selected-practices option:selected').val();
            var practiceName = $('#selected-practices option:selected').html();
            if (practiceId != undefined && practiceName != undefined) {
                $('#selected-practices option[value="' + practiceId + '"]').remove();
                $('#input-practice option[value="' + practiceId + '"]').show();
                $('#input-practice option[value=0]').attr('selected', true);
                $('#add-btn').attr('disabled', true);
                $('#rem-btn').attr('disabled', true);
            }
        }

        function sendForm() {
            $('#selected-practices option').prop('selected', true);
            $('create-form').submit();
        }
    </script>
@endsection
