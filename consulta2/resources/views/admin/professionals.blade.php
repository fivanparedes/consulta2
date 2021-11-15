@extends('layouts.app', ['activePage' => 'professionals', 'title' => 'Consulta2 | Lista de profesionales', 'navName' => 'Sesiones programadas', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Profesionales</h4>
                            <p class="card-category">Lista de profesionales inscriptos al sistema.</p>
                        </div>
                       {{--  <div class="card-header ">
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
                            @if ($professionals->count() == 0)
                                <p class="ml-5 card-category">¡Aún no se inscribieron profesionales al sistema!</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>DNI</th>
                                    <th>Nombre y apellido</th>
                                    <th>Ciudad</th>
                                    <th>Especialidad</th>
                                    <th>Fecha de registro</th>
                                    <th>Estado</th>
                                    <th>Más</th>
                                </thead>
                                <tbody>
                                    @foreach ($professionals as $professional)
                                        <tr>
                                            <td>{{ $professional->profile->user->dni}}</td>
                                            <td>{{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname}}</td>
                                            <td>{{ $professional->profile->city->name }}</td>
                                            <td>{{ $professional->specialty->displayname }}</td>
                                            <td>{{ date_create($professional->profile->user->created_at)->format('d-m-Y h:m') }}</td>
                                            <td>@if ($professional->status == 0)
                                                <span class="badge badge-secondary">Inhabilitado</span>
                                            @else
                                                <span class="badge badge-secondary bg-success">Habilitado</span>
                                            @endif</td>
                                            <td><a class="nav-link" href="/admin/professionals/edit/{{$professional->id}}">
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