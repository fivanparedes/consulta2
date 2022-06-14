@extends('layouts.app', ['activePage' => 'config', 'title' => $companyName.' | Agregar especialidad', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Crear especialidad</h1>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/specialties') }}" method="post">
                @csrf
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre de código</label>
                            <input class="form-control" type="text" name="name" id="input-name" placeholder="neurology"
                                >
                        </div>
                       
                        <div class="form-group">
                            <label for="input-code">Nombre visual</label>
                            <input class="form-control" type="text" name="displayname" id="input-code" placeholder="Neurología"
                                 >
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/specialties') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
    </div>
    </div>
@endsection
