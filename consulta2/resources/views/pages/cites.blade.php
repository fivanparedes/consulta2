@extends('layouts.app', ['activePage' => 'cites', 'title' => 'Consulta2 | Lista de sesiones y consultas', 'navName' =>
'Sesiones programadas', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Sesiones y consultas</h4>
                            <p class="card-category">Lista de sesiones pasadas, presentes y futuras.</p>
                        </div>
                        @if (Auth::user()->isAbleTo('receive-consults'))
                            <hr>
                            <div style="margin-top: 10px; margin-bottom: 10px;">
                                <button type="button" class="btn bg-primary text-light" data-toggle="modal"
                                    data-target="#addModal">+ Agendar manualmente</button>
                            </div>
                            <hr>
                        @endif

                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/cite') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter1">Concurrencia</label>
                                            <select name="filter1" id="filter1" class="form-control" style="width: 97%;">
                                                <option value="" @if (!isset($filter1) || $filter1 == '')
                                                    selected
                                                    @endif>Todas</option>
                                                <option value=">=" @if (isset($filter1) && $filter1 == '>=')
                                                    selected
                                                    @endif>Futuras</option>
                                                <option value="<=" @if (isset($filter1) && $filter1 == '<=')
                                                    selected
                                                    @endif>Pasadas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter2">Nombre/apellido</label>
                                            <input type="text" class="form-control" id="filter2" name="filter2"
                                                style="width: 97%;" placeholder="Nombre o apellido"
                                                value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>

                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <label for="filter3">Desde</label>
                                            <input type="date" class="form-control" id="filter3" name="filter3"
                                                style="width: 97%;" placeholder="Desde"
                                                value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter4">Hasta</label>
                                            <input type="date" class="form-control" id="filter4" name="filter4"
                                                style="width: 97%;" placeholder="Hasta"
                                                value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter5">Modalidad</label>
                                            <select name="filter5" id="filter5" class="form-control" style="width: 97%;">
                                                <option value="" @if (!isset($filter5) || $filter5 == '')
                                                    selected
                                                    @endif>Todas</option>
                                                <option value="0" @if (isset($filter5) && $filter5 == '0')
                                                    selected
                                                    @endif>Presencial
                                                </option>
                                                <option value="1" @if (isset($filter5) && $filter5 == '1')
                                                    selected
                                                    @endif>Virtual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%; float: left;">
                                        <div class="">
                                            <label for="filter6">Asistencia</label>
                                            <select name="filter6" id="filter6" class="form-control" style="width: 97%;">
                                                <option value="" @if (!isset($filter6) || $filter6 == '')
                                                    selected
                                                    @endif>Todas</option>
                                                <option value="0" @if (isset($filter6) && $filter6 == '0')
                                                    selected
                                                    @endif>No
                                                </option>
                                                <option value="1" @if (isset($filter6) && $filter6 == '1')
                                                    selected
                                                    @endif>Si</option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/cite" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/cite" class="btn bg-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($professional->status == 0)
                                <div class="alert alert-info">
                                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                                        <i class="nc-icon nc-simple-remove"></i>
                                    </button>
                                    <span>
                                        <b> Información: </b> Para poder recibir turnos, primero debe ser aprobado por el
                                        sistema.</span>
                                </div>
                            @endif
                            @if ($cites->count() == 0)
                                <p class="ml-5 card-category">No hay sesiones agendadas.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>@sortablelink('title', 'Nombre y apellido')</th>
                                        <th>@sortablelink('start', 'Fecha y hora')</th>
                                        <th>@sortablelink('assisted', '¿Asistió?')</th>
                                        <th>@sortablelink('isVirtual', 'Modalidad')</th>
                                        <th>@sortablelink('confirmed', '¿Confirmó asistencia?')</th>
                                        <th>Más</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($cites as $cite)
                                            <tr>
                                                <td>{{ $cite->id }}</td>
                                                <td>{{ $cite->title }}</td>
                                                <td>{{ date('d-m-Y h:i', strtotime($cite->start)) }}</td>
                                                <td>
                                                    @if ($cite->assisted)
                                                        Sí.
                                                    @else
                                                        No.
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($cite->isVirtual)
                                                        Virtual
                                                    @else
                                                        Presencial.
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($cite->confirmed)
                                                        Confirmado.
                                                    @else
                                                        Sin confirmar.
                                                    @endif
                                                </td>
                                                <td><a class="nav-link" href="/cite/{{ $cite->cite->id }}">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $citea->append('cites')->links() !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar turno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="/event/store">
                    @csrf
                    @method('post')
                    <div class="modal-body">
                        Escriba el DNI del paciente que desea agregar.
                        <div class="form-group">
                            <label for="dni">N° Documento:</label>
                            <input type="number" class="form-control" name="dni" onchange="searchByDni(this.value)">
                        </div>
                        <div class="form-group" id="patientdata"></div>
                        <div class="form-group">
                            <label for="time">Fecha y hora</label>
                            <input type="datetime-local" min="{{ date('Y-m-d', strtotime(now())) }}"
                                class="form-control" name="date">
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="1" name="consult-type">
                            <label for="practice">Tipo de atención:</label>
                            <select class="form-control" name="practice" id="input-practice" onchange="setConsultId()">
                                @foreach ($professional->consultTypes as $consult)
                                    <optgroup label="{{ $consult->name }}">
                                        @foreach ($consult->practices as $practice)
                                            <option value="{{ $practice->id }}">{{ $practice->name }}
                                                [{{ $practice->coverage->name }}]</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <input name="profid" type="hidden" value="{{ Auth::user()->id }}">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver
                            atrás.</button>

                        <button type="submit" class="btn bg-primary text-light">Agendar turno</button>


                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function searchByDni(value) {
            $.ajax({
                method: 'GET',
                dataType: 'json',
                url: '/users/searchByDni',
                data: {
                    dni: value
                },
                success: function(response) {
                    $('#patientdata').empty();
                    if (response.status == 'success') {
                        $('#patientdata').append('<p>' + response.fullname + '-</p><p>' + response.dni +
                            '-</p><p>' + response.address + '-</p><p>' + response.coverage + '</p>');
                    }
                }
            });
        }

        function setConsultId() {
            $('input[type=hidden][name=consult-type]').val(document.querySelector('#input-practice option:checked')
                .parentElement.label);
        }
    </script>
@endsection
