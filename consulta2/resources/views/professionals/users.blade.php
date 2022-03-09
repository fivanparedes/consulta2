@extends('layouts.app', ['activePage' => 'attendees', 'title' => $companyName.' | Mis pacientes', 'navName' => 'Pacientes', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Pacientes</h4>
                            <p class="card-category">Lista de pacientes que participaron o participan de sesiones en el consultorio.</p>
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
                            @if ($patients->count() == 0)
                                <p class="ml-5 card-category">Aún no se atendieron pacientes en el consultorio.</p>
                            @else
                                <table class="table table-hover table-striped">
                                <thead>
                                    <th>DNI</th>
                                    <th>Nombre y apellido</th>
                                    <th>Ciudad</th>
                                    <th>Hoja de vida</th>
                                    <th>Historia médica</th>
                                    <th>Ver perfil</th>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $patient)
                                        <tr>
                                            <td>{{ $patient->profile->user->dni}}</td>
                                            <td>{{ $patient->profile->user->name .' '.$patient->profile->user->lastname}}</td>
                                            <td>{{ $patient->profile->city->name }}</td>
                                            <td><a href="/profile/attendees/lifesheet/{{ $patient->id }}" class="btn bg-blue">Ver salud</a></td>
                                            <td><a href="/profile/attendees/history/{{ $patient->id }}" class="btn bg-blue">Ver historia médica</a></td>
                                            <td><a href="/profile/attendees/{{ $patient->id }}" class="btn bg-blue">Perfil</a></td>
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