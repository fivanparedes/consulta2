@extends('layouts.app', ['activePage' => 'consult_types', 'title' => 'Consulta2 | Agregar tipo de consulta', 'navName'
=> 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar tipo de consulta</h1>
                    <h2>Configuración N° {{ $consult->id }}</h2>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/consult_types/' . $consult->id) }}"
                method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre del tipo de consulta</label>
                            <input class="form-control" type="text" name="name" id="input-name"
                                value="{{ $consult->name }}" placeholder="Ej: Videoconsultas">
                        </div>
                        <div class="form-group">
                            <h4>Elegir prácticas</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="input-practice">Practica profesional</label>
                                    <select id="input-practice" class="form-control" name="practice">
                                        <option value="0">Seleccione una práctica de la lista</option>
                                        @foreach ($professional->coverages as $coverage)
                                            <optgroup label="{{ $coverage->name }}">
                                                @foreach ($coverage->practices as $practice)
                                                    <option @if ($consult->practices->contains($practice))
                                                        style="display: none;"
                                                @endif
                                                value="{{ $practice->id }}">{{ $practice->name }}</option>
                                        @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                    <button id="add-btn" onclick="addItem()" type="button"
                                        class="btn btn-primary text-light" disabled>Agregar a la lista</button>
                                </div>
                                <div class="col-md-12">
                                    <select class="form-control" multiple="multiple" name="selected_practices[]"
                                        id="selected-practices">
                                        @foreach ($consult->practices as $practice)
                                            <option value="{{ $practice->id }}">{{ $practice->name }}</option>
                                        @endforeach
                                    </select>
                                    <button id="rem-btn" onclick="removeItem()" type="button"
                                        class="btn btn-primary text-light">Sacar de la lista</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card" id="card-two">
                        <div class="card-body">
                            <div class="form-group">
                                <h4>Disponibilidad horaria</h4>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-monday" id="av-monday"
                                        @if (in_array('1', $av)) checked @endif>
                                    <label class="form-check-label" for="av-monday"><strong>Lunes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-tuesday" id="av-tuesday"
                                        @if (in_array('2', $av)) checked @endif>
                                    <label class="form-check-label" for="av-tuesday"><strong>Martes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-wednesday" id="av-wednesday"
                                        @if (in_array('3', $av)) checked @endif>
                                    <label class="form-check-label" for="av-wednesday"><strong>Miércoles</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-thursday" id="av-thursday"
                                        @if (in_array('4', $av)) checked @endif>
                                    <label class="form-check-label" for="av-thursday"><strong>Jueves</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-friday" id="av-friday"
                                        @if (in_array('5', $av)) checked @endif>
                                    <label class="form-check-label" for="av-friday"><strong>Viernes</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-saturday" id="av-saturday"
                                        @if (in_array('6', $av)) checked @endif>
                                    <label class="form-check-label" for="av-saturday"><strong>Sábado</strong></label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="av-sunday" id="av-sunday"
                                        @if (in_array('7', $av)) checked @endif>
                                    <label class="form-check-label" for="av-sunday"><strong>Domingo</strong></label>
                                </div>
                                <hr>
                                @foreach (\App\Models\BusinessHour::all() as $hour)
                                    <div class="form-check-inline">
                                        <input @if ($consult->businessHours->contains($hour)) checked @endif type="checkbox" class="form-check-input"
                                            name="business_hours[]" id="bhour-{{ $hour->id }}"
                                            value="{{ $hour->id }}">
                                        <label class="form-check-label"
                                            for="bhour-{{ $hour->id }}"><strong>{{ $hour->time }}</strong></label>
                                    </div>
                                @endforeach
                                <hr>
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <input type="checkbox" class="form-check-input" name="visible" id="input-visible"
                                            @if ($consult->visible) checked @endif>
                                        <label class="form-check-label" for="input-visible">¿Será visible al
                                            usuario?</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" class="form-check-input" name="requires_auth"
                                            id="input-requires_auth" @if ($consult->requires_auth) checked @endif>
                                        <label class="form-check-label" for="input-requires_auth">¿Requiere autorizar los
                                            turnos manualmente?</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button id="btn-next" class="btn btn-danger text-light"> Cancelar</button>
                                    <button onclick="sendForm()" class="btn btn-light text-dark">Guardar</button>
                                </div>
                            </div>


                        </div>
                    </div>
            </form>
            <form action="/consult_types/{{ $consult->id }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger text-light">Eliminar configuración</button>
            </form>
        </div>
    </div>
    <script>
        $('#input-practice').change(function() {
            if ($('#input-practice option:selected').val() == 0) {
                $('#add-btn').attr('disabled', true);
            } else {
                $('#add-btn').attr('disabled', false);
            }
        });

        function addItem() {
            var practiceId = $('#input-practice option:selected').val();
            var practiceName = $('#input-practice option:selected').html();
            if (practiceId != undefined && practiceName != undefined) {
                $('#input-practice option[value="' + practiceId + '"]').hide();
                $('#input-practice option[value=0]').attr('selected', true);
                $('#selected-practices').append('<option value="' + practiceId + '">' + practiceName + '</option>');
                $('#add-btn').attr('disabled', true);
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
            }
        }

        function sendForm() {
            $('#selected-practices option').prop('selected', true);
            $('create-form').submit();
        }
    </script>
@endsection
