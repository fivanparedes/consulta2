@extends('layouts.app', ['activePage' => 'non_workable_days', 'title' => $companyName.' | Días no laborables',
'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-12 left">
                                    <h4 class="card-title">Días no laborables</h4>
                                    <p class="card-category">Días donde los pacientes no podrán agendar turnos. Esta
                                        restricción se puede saltar con el agendamiento manual.</p>
                                </div>
                                @if (Auth::user()->isAbleTo('receive-consults'))
                                    <div class="row mt-2">
                                        <div class="col ml-5">
                                            <button type="button" class="btn bg-primary text-light" data-toggle="modal"
                                                id="any-cancel" data-target="#programCancelModal">+ Agregar
                                                registro</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn bg-primary text-light" data-toggle="modal"
                                                id="today-cancel" data-target="#programCancelModal">Cancelar los turnos de
                                                hoy</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn bg-primary text-light" data-toggle="modal"
                                                id="tomorrow-cancel" data-target="#programCancelModal">Cancelar los turnos
                                                de mañana...</button>
                                        </div>
                                    </div>

                                @endif
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/non_workable_days') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="date" class="form-control" id="filter1" name="filter1"
                                                style="width: 97%;" placeholder="Entre..."
                                                value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/non_workable_days" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/non_workable_days" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($nonworkabledays->count() == 0)
                                <p class="ml-5 card-category">No hay registros.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('concept', 'Concepto')</th>
                                        <th>@sortablelink('from', 'Desde')</th>
                                        <th>@sortablelink('to', 'Hasta')</th>
                                        <th>Borrar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($nonworkabledays as $nonworkableday)
                                            <tr>
                                                <td>{{ $nonworkableday->id }}</td>
                                                <td>{{ $nonworkableday->concept }}</td>
                                                <td>{{ $nonworkableday->from }}</td>
                                                <td>{{ $nonworkableday->to }}</td>
                                                <td>
                                                    <form
                                                        action="/non_workable_days/{{ $nonworkableday->id }}"
                                                        method="post">@csrf @method('delete')<button type="submit"
                                                            class="nav-link btn bg-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button></form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $nonworkabledays->appends('nonworkabledays')->links('vendor.pagination.bootstrap-4') !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="programCancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar turnos de hoy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="/event/massCancel">
                    @csrf
                    @method('post')
                    <div class="modal-body">
                        <p>Se le enviará a cada paciente un aviso de que su turno queda cancelado, y podrán elegir cuando
                            reubicar dichas consultas pendientes.</p>
                        <div class="form-group">
                            <label for="concept">Motivo</label>
                            <input class="form-control" type="text" name="concept" id="concept"
                                placeholder="Ej: 'Vacaciones', 'Emergencia', etc. ">
                        </div>
                        <div class="form-group">
                            <label for="from">Suspender atención desde</label>
                            <input type="date" name="from" id="from" class="form-control"
                                min="{{ date('Y-m-d', strtotime(now())) }}"
                                value="{{ date('Y-m-d', strtotime(now())) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="to">Suspender atención hasta</label>
                            <input type="date" name="to" id="to" class="form-control"
                                min="{{ date('Y-m-d', strtotime(now())) }}"
                                value="{{ date('Y-m-d', strtotime(now())) }}" required>
                        </div>
                        <p>¿Realmente desea dar por cancelados todos los turnos de los días señalados?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver
                            atrás.</button>

                        <button type="submit" class="btn bg-danger text-light">Sí, cancelar los turnos.</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        $('#any-cancel').click(function() {
            $('#exampleModalLabel').html('Elegir días no laborables');
            $('#from').val("<?php echo date('Y-m-d', strtotime(now())); ?>");
            $('#from').prop("disabled", false);
            $('#to').val("<?php echo date('Y-m-d', strtotime(now())); ?>");
            $('#to').prop("disabled", false);
        });

        $('#today-cancel').click(function() {
            $('#exampleModalLabel').html('Cancelar los turnos de hoy');
            $('#from').val("<?php echo date('Y-m-d', strtotime(now())); ?>");
            $('#from').prop("disabled", true);
            $('#to').val("<?php echo date('Y-m-d', strtotime(now())); ?>");
            $('#to').prop("disabled", true);
        });

        $('#tomorrow-cancel').click(function() {
            $('#exampleModalLabel').html('Cancelar los turnos de mañana');
            $('#from').val("<?php echo date('Y-m-d', strtotime(now() . '+1 day')); ?>");
            $('#from').prop("disabled", true);
            $('#to').val("<?php echo date('Y-m-d', strtotime(now() . '+1 day')); ?>");
            $('#to').prop("disabled", true);
        });
    </script>
@endsection
