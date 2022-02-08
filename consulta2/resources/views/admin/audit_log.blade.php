@extends('layouts.app', ['activePage' => 'audits', 'title' => 'Consulta2 | Log de Auditoría',
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
                                    <h4 class="card-title">Auditoría</h4>
                                    <p class="card-category">Registro de actividad en el sistema</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/audits') }}" method="GET">
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
                            @if ($audits->count() == 0)
                                <p class="ml-5 card-category">No hay registros.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>ID de Usuario</th>
                                        <th>Evento</th>
                                        <th>Valor anterior</th>
                                        <th>Valor posterior</th>
                                        <th>URL</th>
                                        <th>Ver</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($audits as $audit)
                                            <tr>
                                                <td>{{ $audit->id }}</td>
                                                <td>{{ $audit->user_id }}</td>
                                                <td>{{ $audit->event }}</td>
                                                <td>{{ implode('-', $audit->old_values) }}</td>
                                                <td>{{ implode('-', $audit->new_values) }}</td>
                                                <td>{{ $audit->url }}</td>
                                                <td><a href="/audits/{{ $audit->id }}"
                                                        class="btn bg-primary text-light">Detalles</a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    
@endsection
