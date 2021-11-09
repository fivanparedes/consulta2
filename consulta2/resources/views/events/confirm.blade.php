@extends('layouts.app', ['activePage' => 'event', 'title' => 'Consulta2 | Confirmar turno', 'navName' => 'Agendar un turno', 'activeButton' => 'laravel'])

@section('content')
 <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Confirmar turno</h2>
                        <form class="form" action="/event/store" method="POST">
                            @csrf
                            <h3>Nombre del profesional: </h3>
                            <label>{{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname }}</label>
                            <input type="hidden" name="profid" value="{{ $professional->profile->user->id }}">
                            <h3>Área:</h3>
                            <label> {{ $professional->field }}</label>
                            <h3>Fecha y hora: </h3>
                            <label> {{ date_create($selectedDate)->format('d/m/Y h:m') }}</label>
                            <input type="hidden" name="date" value="{{ $selectedDate }}">
                            <h3>Tipo de consulta:</h3>
                            <label> {{ $consult_type->name }}</label>
                            <input type="hidden" name="consult-type" value="{{ $consult_type->id }}">
                            <br>
                            <br>
                            <label>Confirmar:</label>
                            <br>
                            <button class="btn" type="submit">Confirmar turno</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection