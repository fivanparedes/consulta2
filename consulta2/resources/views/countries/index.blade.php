@extends('layouts.app', ['activePage' => 'config', 'title' => $companyName.' | Lista de Países',
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
                                    <h4 class="card-title">Países</h4>
                                    <p class="card-category">Listado de países soportados por el sistema</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('countries.create') }}">+
                                        Agregar país</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header table">
                            <form class="form-inline" action="{{ url('/countries') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <a href="/countries" class="btn bg-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($countries->count() == 0)
                                <p class="ml-5 card-category">No hay países. ¡Pruebe a añadir algunos!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('code', 'Código')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($countries as $country)
                                            <tr>
                                                <td>{{ $country->id }}</td>
                                                <td>{{ $country->name }}</td>
                                                <td>+{{ $country->code }}</td>
                                                <td><a class="nav-link"
                                                        href="/countries/{{ base64_encode(base64_encode($country->id)) }}/edit">
                                                        <i class="nc-icon nc-badge"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {!! $countries->appends('countries')->links('vendor.pagination.bootstrap-4') !!}
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
