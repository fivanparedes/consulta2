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
                                    <div class="col">
                                        <div class="">
                                            <input type="date" class="form-control" id="filter1" name="filter1"
                                                style="width: 97%;" placeholder="Entre..."
                                                value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select name="filter2" id="input-filter2" class="form-control">
                                            <option value="all" @if (!isset($filter2))
                                                selected
                                            @endif>Todos los eventos</option>
                                            <option value="updated" @if (isset($filter2) && $filter2 == "updated")
                                                selected
                                            @endif>Update</option>
                                            <option value="created" @if (isset($filter2) && $filter2 == "created")
                                                selected
                                            @endif>Insert</option>
                                            <option value="deleted" @if (isset($filter2) && $filter2 == "deleted")
                                                selected
                                            @endif>Delete</option>
                                        </select>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            
                                        </div>
                                    </div>
                                    <div class="col ml-5" style="width: 10%;">
                                        <div class="">
                                            <a href="/non_workable_days" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                    <a class="btn bg-secondary text-light" href="/audits?filter1={{ isset($filter1) ? $filter1 : '' }}&filter2=" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a></div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($audits->count() == 0)
                                <p class="ml-5 card-category">No hay registros.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('user_id', 'ID de Usuario')</th>
                                        <th>@sortablelink('event', 'Evento')</th>
                                        <th style="max-width: 200px;">@sortablelink('old_values', 'Valores anteriores')</th>
                                        <th style="max-width: 200px;">@sortablelink('new_values', 'Valores posteriores')</th>
                                        <th>@sortablelink('url', 'URL')</th>
                                        <th>Ver</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($audits as $audit)
                                            <tr>
                                                <td>{{ $audit->id }}</td>
                                                <td>{{ $audit->user_id }}</td>
                                                <td>{{ $audit->event }}</td>
                                                <td style="max-width: 200px;">{{ ($audit->old_values != []) ? substr(implode('-', $audit->old_values), 0, 50) : "Vacío"}}</td>
                                                <td style="max-width: 200px;">{{ ($audit->new_values != []) ? substr(implode('-', $audit->new_values), 0, 50) : "Vacío" }}</td>
                                                <td>{{ $audit->url }}</td>
                                                <td><a href="/audits/{{ $audit->id }}"
                                                        class="btn bg-primary text-light">Detalles</a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $audits->links('vendor.pagination.bootstrap-4') !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    
@endsection
