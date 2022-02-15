@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Lista de Especialidades',
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
                                    <h4 class="card-title">Especialidades</h4>
                                    <p class="card-category">Listado de especialidades del campo de la salud soportados por el sistema</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('specialties.create') }}">+
                                        Agregar especialidad</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/specialties') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/specialties" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($specialties->count() == 0)
                                <p class="ml-5 card-category">No hay especialidades. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('displayname', 'Nombre visual')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($specialties as $specialty)
                                            <tr>
                                                <td>{{ $specialty->id }}</td>
                                                <td>{{ $specialty->name }}</td>
                                                <td>{{ $specialty->displayname }}</td>
                                                <td><a class="nav-link"
                                                        href="/specialties/{{ base64_encode(base64_encode($specialty->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $specialties->appends('specialties')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
