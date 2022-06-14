<html>

<head>
    <title>{{$companyName}} | Informe - Profesionales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }

    </style>
    <div style="text-align: center">
        <div class="row">
            <div class="col">
                <img style="position: relative; float:left" src='{{ $companyLogo != null? asset('/storage/images/' . explode('/', $companyLogo)[2]): asset('light-bootstrap/img/default-avatar.png') }}'
                    width="100" height="100" alt="Logo" />
            </div>
            <div class="col">
                <h1 style="position: relative; float: right;">Consulta2</h1>
            </div>
        </div>
    </div>
    <hr style="clear: both;">
    <div class="row">
            <h2>Informe - Profesionales</h2>
            <br>
            </div>
    <div class="row">
        <p><strong>Emisor del formulario:</strong> {{ Auth::user()->name . ' ' . Auth::user()->lastname }}</p>
        
    </div>
    <div class="row">
        <p><strong>DNI:</strong> {{ Auth::user()->dni }}</p>
    </div>
    <div class="row">
        <p><strong>Rol:</strong> {{ Auth::user()->roles->first()->display_name }}</p>
    </div>

    <table class="table table-bordered" style="font-size: 80%;">
        <thead>
            <tr>
                <th>N° Documento</th>
                <th>Apellido(s)</th>
                <th>Nombre(s)</th>
                <th>Matrícula</th>
                <th>Ciudad</th>
                <th>Especialidad</th>
                <th>Fecha de registro</th>
                <th>Estado</th>
            </tr>

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
                            Inhabilitado
                        @else
                            Habilitado
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
