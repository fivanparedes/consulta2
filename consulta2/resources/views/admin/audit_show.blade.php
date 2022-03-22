@extends('layouts.app', ['activePage' => 'audits', 'title' => $companyName.' | Ver registro de Auditoría', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Registro de auditoría</h1>
                    <h2>Registro N° {{ $audit->id }}</h2>
                    <div class="form-group">
                       <p><strong>Tipo de usuario:</strong> {{ $audit->user_type }}</p>
                        <p><strong>Usuario que generó el evento:</strong>{{ $audit->user->name . ' ' . $audit->user->lastname}}</p>
                    </div>
                </div>
            </div>
            <div class="card" id="card-one">
                <div class="card-body">
                    <div class="form-group">
                        <p><strong>Evento:</strong> {{ $audit->event }}</p>
                    </div>
                    <div class="form-group">
                        <p><strong>Evento:</strong> {{ strtolower(substr($audit->auditable_type, 11)) }}</p>
                    </div>
                    <div class="form-group">
                        <p><strong>Valores previos:</strong> @foreach ($audit->old_values as $key => $value)
                            <span style="font-style: italic">{{ $key }}:</span> {{ $value }} <br>
                        @endforeach</p>
                    </div>
                    <div class="form-group">
                        <p><strong>Valores nuevos:</strong> @foreach ($audit->new_values as $key => $value)
                            <span style="font-style: italic">{{ $key }}:</span> {{ $value }} <br>
                        @endforeach</p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <p><strong>URL del recurso:</strong> {{ $audit->url }}</p>
                    </div>
                    <div class="form-group">
                        <p><strong>Dirección IP del dispositivo:</strong> {{ $audit->ip_address }}</p>
                    </div>
                    <div class="form-group">
                        <p><strong>User-Agent:</strong> {{ $audit->user_agent }}</p>
                    </div>
                    <div class="form-group">
                        <p><strong>Etiquetas:</strong> {{ $audit->tags }}</p>
                    </div>
                </div>
                <hr>
                <div class="form-group text-center">
                    <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/audits') }}"> Volver atrás</a>
                </div>
            </div>
        </div>
    </div>
    <script>

    </script>
@endsection
