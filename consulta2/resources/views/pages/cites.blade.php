@extends('layouts.app', ['activePage' => 'cites', 'title' => 'Consulta2 | Lista de sesiones y consultas', 'navName' => 'Sesiones programadas', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Sesiones y consultas</h4>
                            <p class="card-category">Lista de sesiones pasadas, presentes y futuras.</p>
                        </div>
                        <div class="card-header ">
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
                        </div>
                        
                        <div class="card-body table-full-width table-responsive">
                            @if ($cites->count() == 0)
                                <p class="ml-5 card-category">No hay sesiones agendadas.</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Nombre y apellido</th>
                                    <th>Fecha y hora</th>
                                    <th>¿Asistió?</th>
                                    <th>Modalidad</th>
                                    <th>¿Confirmó asistencia?</th>
                                    <th>Más</th>
                                </thead>
                                <tbody>
                                    @foreach ($cites as $cite)
                                        <tr>
                                            <td>{{ $cite->id}}</td>
                                            <td>{{ $cite->title}}</td>
                                            <td>{{ date('d-m-Y h:m',strtotime($cite->start)) }}</td>
                                            <td>@if ($cite->assisted)
                                                Sí.
                                            @else
                                                No.
                                            @endif</td>
                                            <td>@if ($cite->isVirtual)
                                                Virtual
                                            @else
                                                Presencial.
                                            @endif</td>
                                            <td>@if ($cite->confirmed)
                                                Confirmado.
                                            @else
                                                Sin confirmar.
                                            @endif</td>
                                            <td><a class="nav-link" href="/cite/{{$cite->id}}">
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