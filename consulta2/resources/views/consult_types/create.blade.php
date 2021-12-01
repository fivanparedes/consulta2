@extends('layouts.app', ['activePage' => 'consult_types', 'title' => 'Consulta2 | Agregar tipo de consulta', 'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Crear tipo de consulta</h1>
                    
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ route('consult_types.store') }}" method="post">
                @csrf
            <div class="card" id="card-one">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input-name">Nombre del tipo de consulta</label>
                        <input class="form-control" type="text" name="name" id="input-name" placeholder="Ej: Videoconsultas">
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
                                        <option value="{{ $practice->id }}">{{ $practice->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                                </select> 
                                <button id="add-btn" onclick="addItem()" type="button" class="btn btn-primary text-light" disabled>Agregar a la lista</button>
                            </div>
                            <div class="col-md-12">
                                <select class="form-control" multiple="multiple" name="selected_practices[]" id="selected-practices">

                                </select>
                                 <button id="rem-btn" onclick="removeItem()" type="button" class="btn btn-primary text-light">Sacar de la lista</button>
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
                        <input type="checkbox" class="form-check-input" name="av-sunday" id="av-sunday" >
                        <label class="form-check-label" for="av-sunday"><strong>Domingo</strong></label>
                    </div>
                    <hr>
                    @foreach (\App\Models\BusinessHour::all() as $hour)
                        <div class="form-check-inline">
                        <input type="checkbox" class="form-check-input" name="business_hours[]" id="bhour-{{ $hour->id }}" value="{{ $hour->id }}" >
                        <label class="form-check-label" for="bhour-{{ $hour->id }}"><strong>{{ $hour->time }}</strong></label>
                        </div>
                    @endforeach
                        <hr>
                    <div class="form-group">
                    <div class="form-check-inline">
                        <input type="checkbox" class="form-check-input" name="visible" id="input-visible">
                        <label class="form-check-label" for="input-visible">¿Será visible al usuario?</label>
                    </div> 
                    <div class="form-check-inline">
                        <input type="checkbox" class="form-check-input" name="requires_auth" id="input-requires_auth">
                        <label class="form-check-label" for="input-requires_auth">¿Requiere autorizar los turnos manualmente?</label>
                    </div>
                </div>
                <div class="form-group">
                    <button id="btn-next" class="btn btn-danger text-light"> Cancelar</button>
                    <button onclick="sendForm()" class="btn btn-light text-dark">Crear tipo de consulta</button>
                </div>
                </div>
                
                
                </div>
            </div>
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
                $('#input-practice option[value="'+practiceId+'"]').hide();
                $('#input-practice option[value=0]').attr('selected', true);
                $('#selected-practices').append('<option value="'+practiceId+'">'+practiceName+'</option>');
                $('#add-btn').attr('disabled', true);
            }
        }

        function removeItem() {
            var practiceId = $('#selected-practices option:selected').val();
            var practiceName = $('#selected-practices option:selected').html();
            if (practiceId != undefined && practiceName != undefined) {
                $('#selected-practices option[value="'+practiceId+'"]').remove();
                $('#input-practice option[value="'+practiceId+'"]').show();
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