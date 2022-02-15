@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Editar provincia', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Editar provincia N° {{ $province->id }}</h1>
                </div>
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/provinces/'.$province->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="input-name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="input-name" 
                                value="{{ $province->name }}">
                        </div>
                        <div class="form-group">
                            <label for="input-country_id">País</label>
                            <select name="country_id" id="input-country_id" class="form-control">
                                @foreach (\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}" @if ($province->country == $country)
                                        selected
                                    @endif>{{ $country->name }} (+{{ $country->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/provinces') }}">
                                Cancelar</a>
                            <button type="submit" class="btn btn-light text-dark">Guardar</button>
                        </div>
                    </div>


                </div>
        </div>
        </form>
        <form action="/provinces/{{ $province->id }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger text-light">Eliminar provincia</button>
        </form>
    </div>
    </div>
@endsection
