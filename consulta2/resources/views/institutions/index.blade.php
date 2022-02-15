@extends('layouts.app', ['activePage' => 'institutions', 'title' => 'Consulta2 | Lista de instituciones',
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
                                    <h4 class="card-title">Instituciones</h4>
                                    <p class="card-category">Las cuentas institucionales representan los centros de salud habilitados.</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('institutions.create') }}">+
                                        Adherir institución</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/institutions') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter1" name="filter1" style="width: 97%;"
                                                placeholder="Nombre" value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="filter2" name="filter2" style="width: 97%;"
                                                placeholder="Dirección" value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter3" name="filter3" style="width: 97%;"
                                                placeholder="Teléfono" value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter4" name="filter4" style="width: 97%;"
                                                placeholder="Ciudad" value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/institutions/pdf" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/institutions" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($institutions->count() == 0)
                                <p class="ml-5 card-category">No hay instituciones. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('description', 'Descripción')</th>
                                        <th>@sortablelink('address', 'Dirección')</th>
                                        <th>@sortablelink('phone', 'Teléfono')</th>
                                        <th>@sortablelink('city.name', 'Ciudad')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($institutions as $institution)
                                            <tr>
                                                <td>{{ $institution->id }}</td>
                                                <td>{{ $institution->name }}</td>
                                                <td>{{ $institution->description }}</td>
                                                <td>{{ $institution->address }}</td>
                                                <td>{{ $institution->phone }}</td>
                                                <td>{{ $institution->city->name }}</td>
                                                <td><a class="nav-link"
                                                        href="/institutions/{{ base64_encode(base64_encode($institution->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $institutions->appends('institutions')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
