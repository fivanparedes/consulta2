@extends('layouts.app', ['activePage' => 'practices', 'title' => 'Consulta2 | Configuración de prácticas profesionales', 'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-12 left">
                                    <h4 class="card-title">Prácticas profesionales</h4>
                                    <p class="card-category">Listado de prácticas por obra social</p>
                                    <p>Sugerencia: crear una práctica para atención particular por cada operación a realizarse.</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary" href="{{ route('practices.create') }}">+ Agregar práctica</a>
                                </div>
                            </div>
                            </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/practices') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter1" name="filter1" style="width: 97%;"
                                                placeholder="Nombre" value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 5%; margin-right: -35px;">
                                        <div class="">
                                            <select name="filter2" id="filter2" class="form-control" style="width: 47%;">
                                                <option value="=" @if (!isset($filter2) || $filter2 == '=')
                                                    selected
                                                @endif>=</option>
                                                <option value="<>" @if (isset($filter2) && $filter2 == '<>')
                                                    selected
                                                @endif><></option>
                                                <option value="<" @if (isset($filter2) && $filter2 == '<')
                                                    selected
                                                @endif><</option>
                                                <option value="<=" @if (isset($filter2) && $filter2 == '<=')
                                                    selected
                                                @endif><=</option>
                                                <option value=">" @if (isset($filter2) && $filter2 == '>')
                                                    selected
                                                @endif>></option>
                                                <option value=">="
                                                    @if (isset($filter2) && $filter2 == '>=')
                                                    selected
                                                @endif>>=</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="filter3" name="filter3" style="width: 97%;"
                                                placeholder="Duración" value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter4" name="filter4" style="width: 97%;"
                                                placeholder="Obra social" value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 5%; margin-right: -35px;">
                                        <div class="">
                                            <select name="filter5" id="filter5" class="form-control" style="width: 47%;">
                                                <option value="=" @if (!isset($filter5) || $filter5 == '=')
                                                    selected
                                                @endif>=</option>
                                                <option value="<>" @if (isset($filter5) && $filter5 == '<>')
                                                    selected
                                                @endif><></option>
                                                <option value="<" @if (isset($filter5) && $filter5 == '<')
                                                    selected
                                                @endif><</option>
                                                <option value="<=" @if (isset($filter5) && $filter5 == '<=')
                                                    selected
                                                @endif><=</option>
                                                <option value=">" @if (isset($filter5) && $filter5 == '>')
                                                    selected
                                                @endif>></option>
                                                <option value=">="
                                                    @if (isset($filter5) && $filter5 == '>=')
                                                    selected
                                                @endif>>=</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%; float: left;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter6" name="filter6" style="width: 97%;"
                                                placeholder="Precio" value="{{ isset($filter6) ? $filter6 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/practices" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/practices" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-body table-full-width table-responsive">
                            @if ($practices->count() == 0)
                                <p class="ml-5 card-category">No hay prácticas profesionales ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>@sortablelink('id', 'ID')</th>
                                    <th>@sortablelink('name', 'Nombre')</th>
                                    <th>@sortablelink('maxtime', 'Duración máx.')</th>
                                    <th>@sortablelink('coverage.name', 'Obra social', ['name' => 'asc'])</th>
                                    <th>@sortablelink('price.price', 'Precio')</th>
                                    <th>@sortablelink('specialty.displayname', 'Especialidad')</th>
                                    <th>Editar</th>
                                </thead>
                                <tbody>
                                    @foreach ($practices as $practice)
                                        <tr>
                                            <td>{{ $practice->id}}</td>
                                            <td>{{ $practice->name}}</td>
                                             <td>{{ $practice->maxtime }} min.</td>
                                            <td>{{ $practice->coverage->name }}</td>
                                            <td>${{ $practice->price->price}}</td>
                                           <td>{{ $practice->nomenclature->specialty->displayname }}</td>
                                            <td><a class="nav-link" href="/practices/{{base64_encode(base64_encode($practice->id))}}/edit">
                                                <i class="nc-icon nc-badge"></i>
                                            </a></td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            {!! $practices->appends('practices')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection