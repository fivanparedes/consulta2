@extends('layouts.app', ['activePage' => 'professionals', 'title' => 'Consulta2 | Lista de profesionales', 'navName' =>
'Sesiones programadas', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title">Profesionales</h4>
                                    <p class="card-category">Lista de profesionales inscriptos al sistema.</p>
                                </div>
                                <div class="col">
                                    @if (Auth::user()->hasPermission('ProfessionalController@create'))
                                        <a class="btn bg-primary text-light" href="{{ url('/manage/professionals/create') }}">+ Adherir nuevo profesional</a>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/manage/professionals') }}" method="GET">

                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter1" name="filter1" style="width: 97%;"
                                                placeholder="DNI" value="{{ isset($filter1) ? $filter1 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="filter2" name="filter2" style="width: 97%;"
                                                placeholder="Apellidos" value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter3" name="filter3" style="width: 97%;"
                                                placeholder="Nombres" value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <input type="text" class="form-control" id="filter4" name="filter4" style="width: 97%;"
                                                placeholder="Especialidad" value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <select name="filter5" id="filter5" class="form-control" style="width: 97%;">
                                                <option value="">Todos</option>
                                                <option value="0" @if (isset($filter5) && $filter5 == "0")
                                                    selected
                                                @endif>Inhabilitado</option>
                                                <option value="1" @if (isset($filter5) && $filter5 == "1")
                                                    selected
                                                @endif>Habilitado</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                            <a class="nav-link" href="/manage/professionals/pdf" title="Generar PDF">
                                                <i class="nc-icon nc-paper-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/manage/professionals" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>

                                </div>



                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($professionals->count() == 0)
                                <p class="ml-5 card-category">¡Aún no se inscribieron profesionales al sistema!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('user.dni', 'DNI')</th>
                                        <th>@sortablelink('user.lastname', 'Apellido(s)')</th>
                                        <th>@sortablelink('user.name', 'Nombre(s)')</th>
                                        <th>@sortablelink('licensePlate', 'Matrícula')</th>
                                        <th>@sortablelink('city.name', 'Ciudad')</th>
                                        <th>@sortablelink('specialty.displayname', 'Especialidad')</th>
                                        <th>@sortablelink('created_at', 'Fecha de registro')</th>
                                        <th>@sortablelink('status', 'Estado')</th>
                                        <th>Más</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($professionals as $professional)

                                            <tr>

                                                <td>{{ $professional->profile->user->dni }}</td>
                                                <td>{{ $professional->profile->user->lastname }}</td>
                                                <td>{{ $professional->profile->user->name }}</td>
                                                <td>{{ $professional->licensePlate }}</td>
                                                <td>{{ $professional->profile->city->name }}</td>
                                                <td>{{ $professional->specialty->displayname }}</td>
                                                <td>{{ date_create($professional->profile->user->created_at)->format('d-m-Y h:i') }}
                                                </td>
                                                <td>
                                                    @if ($professional->status == 0)
                                                        <span class="badge badge-secondary">Inhabilitado</span>
                                                    @else
                                                        <span class="badge badge-secondary bg-success">Habilitado</span>
                                                    @endif
                                                </td>
                                                <td><a class="nav-link"
                                                        href="/manage/professionals/edit/{{ $professional->id }}">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $professionals->appends('professionals')->links() !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
