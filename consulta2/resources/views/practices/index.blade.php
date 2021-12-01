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
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary" href="{{ route('practices.create') }}">+ Agregar práctica</a>
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
                            @if ($practices->count() == 0)
                                <p class="ml-5 card-category">No hay prácticas profesionales ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Obra social</th>
                                    <th>Cod.Nomenclador</th>
                                    <th>Duración (min.)</th>
                                    <th>Editar</th>
                                </thead>
                                <tbody>
                                    @foreach ($practices as $practice)
                                        <tr>
                                            <td>{{ $practice->id}}</td>
                                            <td>{{ $practice->name}}</td>
                                            <td>{{ $practice->nomenclature->specialty->displayname }}</td>
                                            <td>{{ $practice->coverage->name }}</td>
                                            <td>{{ $practice->nomenclature->code }}</td>
                                            <td>{{ $practice->maxtime }}</td>
                                            <td><a class="nav-link" href="/practices/{{base64_encode(base64_encode($practice->id))}}/edit">
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