@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Configuración', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Configuración</h1>
                    <p>Listado de distintos parámetros. <span class="text-danger"><i class="fa fa-danger"></i>
                            Advertencia: información delicada</span></p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Países</h3>
                            <a href="{{ url('/countries') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Provincias</h3>
                            <a href="{{ url('/provinces') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Ciudades</h3>
                            <a href="{{ url('/cities') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Especialidades</h3>
                            <a href="{{ url('/specialties') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Ajustes</h3>
                            <a href="{{ url('/config/settings') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Log de auditoría</h3>
                            <a href="{{ url('/audits') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" id="card-one">
                        <div class="card-body">
                            <h3>Roles y permisos</h3>
                            <a href="{{ url('/config/roles_permissions/roles-assignment') }}" class="btn bg-primary text-light">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

    </script>
@endsection
