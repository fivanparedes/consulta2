@extends('layouts.app', ['activePage' => 'patients', 'title' => 'Consulta2 | Lista de pacientes registrados',
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
                                    <h4 class="card-title">Pacientes</h4>
                                    <p class="card-category">Los pacientes son los que se atienden en los centros de salud.</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('patients.create') }}">+
                                        Agregar paciente</a>
                                        @if (Auth::user()->isAbleTo('manage-histories'))
                                            <a href="{{ url('/medical_history/create') }}" class="btn bg-secondary text-light">Agregar historial médico</a> 
                                        @endif
                                        
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url("/manage/patients")}}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter1" name="filter1" style="width: 97%;"
                                                placeholder="Nombre/Apellido" value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="filter2" name="filter2" style="width: 97%;"
                                                placeholder="DNI" value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter3" name="filter3" style="width: 97%;"
                                                placeholder="Obra social" value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter4" name="filter4" style="width: 97%;"
                                                placeholder="Ciudad" value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-4">
                                    <div class="col" style="width: 10%;">
                                        
                                            <button type="submit" class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                           
                                        
                                    </div>
                                    <div class="col ml-5" style="width: 10%;">
                                        
                                            <a href="/manage/patients" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        
                                    </div>
                                    <div class="col" style="width: 10%;">
                                         <a class="btn bg-secondary" href="/manage/patients/pdf?filter1={{ isset($filter1) ? $filter1 : '' }}&filter2={{ isset($filter2) ? $filter2 : '' }}&filter3={{ isset($filter3) ? $filter3 : '' }}&filter4={{ isset($filter4) ? $filter4 : '' }}" title="Generar PDF">
                                                <i class="fa fa-file-pdf"></i>
                                            </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($patients->count() == 0)
                                <p class="ml-5 card-category">No hay pacientes registrados.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('lastname', 'Apellido')</th>
                                        <th>@sortablelink('dni', 'DNI')</th>
                                        <th>@sortablelink('name', 'Obra social')</th>
                                        <th>@sortablelink('name', 'Ciudad')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($patients as $patient)
                                            <tr>
                                                <td>{{ $patient->profile->user->name }}</td>
                                                <td>{{ $patient->profile->user->lastname }}</td>
                                                <td>{{ $patient->profile->user->dni }}</td>
                                                <td>{{ $patient->lifesheet->coverage->name}}</td>
                                                <td>{{ $patient->profile->city->name }}</td>
                                                <td><a class="nav-link"
                                                        href="/manage/patients/{{ base64_encode(base64_encode($patient->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $patients->appends('patients')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
