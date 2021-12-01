@extends('layouts.app', ['activePage' => 'consult_types', 'title' => 'Consulta2 | Configuración de horarios', 'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-12 left">
                                    <h4 class="card-title">Horarios de consulta</h4>
                                    <p class="card-category">Agrupamiento de prácticas de acuerdo a fecha y horario</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary" href="{{ route('consult_types.create') }}">+ Agregar tipo de consulta</a>
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
                            @if ($professional->status == 0)
                                <div class="alert alert-info">
                                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                                            <i class="nc-icon nc-simple-remove"></i>
                                        </button>
                                        <span>
                                            <b> Información: </b> Para poder recibir turnos, primero debe ser aprobado por el sistema.</span>
                                    </div>
                            @endif
                            @if ($types->count() == 0)
                                <p class="ml-5 card-category">No hay horarios configurados. ¡Pruebe a añadir uno!</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Días por semana</th>
                                    <th>Cant. prácticas</th>
                                    <th>Visible</th>
                                    <th>¿Requiere autorización?</th>
                                    <th>Editar</th>
                                </thead>
                                <tbody>
                                    @foreach ($types as $type)
                                        <tr>
                                            <td>{{ $type->id}}</td>
                                            <td>{{ $type->name}}</td>
                                            <td>{{ count(explode(';', $type->availability)) }}</td>
                                            <td>{{ count($type->practices) }}</td>
                                            <td>@if ($type->visible)
                                                Visible
                                            @else
                                                Oculto
                                            @endif</td>
                                            <td>@if ($type->requires_auth)
                                                Sí.
                                            @else
                                                No.
                                            @endif</td>
                                            <td><a class="nav-link" href="/consult_types/{{base64_encode(base64_encode($type->id))}}/edit">
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