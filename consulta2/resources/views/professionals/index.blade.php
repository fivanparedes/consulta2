@extends('layouts.app', ['activePage' => 'professional_index', 'title' => 'Consulta2 | Busca un profesional', 'navName'
=> 'Agendar turno', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        @if (isset($justregistered))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                Turno agendado exitosamente!
                            </div>
                        @endif
                        <div class="card-header ">
                            <h4 class="card-title">Agendar turno.</h4>
                            <p class="card-category">Lista de profesionales disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($professionals as $professional)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title font-weight-bold">
                                    {{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname }}
                                </h4>
                                <h5 class="card-subtitle">
                                   <span class="badge badge-primary">{{ $professional->specialty->displayname . ' ' . $professional->field }}</span> </h5>
                                <p class="card-text">
                                    @if ($professional->institution_id == null)
                                        Independiente.
                                    @else
                                        Trabaja en: {{ $professional->institution->name }}
                                    @endif
                                </p>
                                <a href="/professional/show/{{ $professional->id }}" class="btn btn-primary">Reservar
                                    turno</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@endsection
