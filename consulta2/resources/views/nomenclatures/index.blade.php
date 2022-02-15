@extends('layouts.app', ['activePage' => 'nomenclatures', 'title' => 'Consulta2 | Configuración de nomencladores',
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
                                    <h4 class="card-title">Nomencladores</h4>
                                    <p class="card-category">Listado de nomenclaturas para prácticas profesionales</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('nomenclatures.create') }}">+
                                        Agregar nomenclatura</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/nomenclatures') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter1" name="filter1" style="width: 97%;"
                                                placeholder="Código" value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="filter2" name="filter2" style="width: 97%;"
                                                placeholder="Descripción" value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter3" name="filter3" style="width: 97%;"
                                                placeholder="Especialidad" value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/nomenclatures" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/nomenclatures" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($nomenclatures->count() == 0)
                                <p class="ml-5 card-category">No hay nomenclaturas. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id')</th>
                                        <th>@sortablelink('code', 'Código')</th>
                                        <th>@sortablelink('description', 'Descripción')</th>
                                        <th>@sortablelink('specialty.displayname', trans('Especialidad'))</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($nomenclatures as $nomenclature)
                                            <tr>
                                                <td>{{ $nomenclature->id }}</td>
                                                <td>{{ $nomenclature->code }}</td>
                                                <td>{{ $nomenclature->description }}</td>
                                                <td>{{ $nomenclature->specialty->displayname }}</td>
                                                <td><a class="nav-link"
                                                        href="/nomenclatures/{{ base64_encode(base64_encode($nomenclature->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $nomenclatures->appends('nomenclatures')->links('vendor.pagination.bootstrap-4') !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
