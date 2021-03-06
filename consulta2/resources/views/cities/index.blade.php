@extends('layouts.app', ['activePage' => 'config', 'title' => $companyName.' | Lista de Ciudades',
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
                                    <h4 class="card-title">Ciudades</h4>
                                    <p class="card-category">Listado de ciudades soportadas por el sistema</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('cities.create') }}">+
                                        Agregar ciudad</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/cities') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/cities" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($cities->count() == 0)
                                <p class="ml-5 card-category">No hay ciudades. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('zipcode', 'Código postal')</th>
                                        <th>@sortablelink('province_id', 'Provincia')</th>
                                        <th>@sortablelink('province.country_id', 'País')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $city)
                                            <tr>
                                                <td>{{ $city->id }}</td>
                                                <td>{{ $city->name }}</td>
                                                <td>{{ $city->zipcode }}</td>
                                                <td>{{ $city->province->name }}</td>
                                                <td>{{ $city->province->country->name }}</td>
                                                <td><a class="nav-link"
                                                        href="/cities/{{ base64_encode(base64_encode($city->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $cities->appends('cities')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
