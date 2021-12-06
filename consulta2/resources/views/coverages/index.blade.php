@extends('layouts.app', ['activePage' => 'coverages', 'title' => 'Consulta2 | Lista de Obras Sociales',
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
                                    <h4 class="card-title">Obras sociales</h4>
                                    <p class="card-category">Listado de obras sociales soportadas por el sistema</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('coverages.create') }}">+
                                        Agregar Obra Social</a>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card-header ">
                            <form class="form-inline" method="GET">
                                
                                    <div class="pl-5 row">
                                        <p class="pt-1">Filtro</p>
                                        <div class="col w-50" style="float: left;">
                                            <input type="date" class="form-control ml-4" id="filter" name="filter" placeholder="Día" value="{{$filter}}">
                                        </div>
                                        <div class="col w-50" style="float:right;">
                                            <input type="text" class="form-control ml-4" id="filter2" name="filter2" placeholder="Nombre y/o apellido" value="{{$filter2}}">
                                        </div>
                                        
                                    </div>
                                  
                                
                                <button type="submit" class="btn btn-default mb-2 ml-5 ">Filtrar</button>
                                <a class="nav-link" href="/cite/pdf/{{$filter}}/{{$filter2}}" title="Generar PDF">
                                    <i class="nc-icon nc-paper-2"></i>
                                </a>
                            </form>
                        </div> --}}

                        <div class="card-body table-full-width table-responsive">
                            @if ($coverages->count() == 0)
                                <p class="ml-5 card-category">No hay Obras Sociales. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($coverages as $coverage)
                                            <tr>
                                                <td>{{ $coverage->id }}</td>
                                                <td>{{ $coverage->name }}</td>
                                                <td>{{ $coverage->address }}</td>
                                                <td>{{ $coverage->phone }}</td>
                                                <td><a class="nav-link"
                                                        href="/coverages/{{ base64_encode(base64_encode($coverage->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
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
@endsection
