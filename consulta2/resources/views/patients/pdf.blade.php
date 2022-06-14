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
                <img style="position: relative; float:left"
                    src='{{ $companyLogo != null? asset('/storage/images/' . explode('/', $companyLogo)[2]): asset('light-bootstrap/img/default-avatar.png') }}'
                    width="100" height="100" alt="Logo" />
            </div>
            <div class="col">
                <h1 style="position: relative; float: right;">Consulta2</h1>
                
            </div>
        </div>
    </div>
    <hr style="clear: both;">
    <div class="row">
        <h2>Informe - Pacientes</h2>
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Obra social</th>
                <th>Ciudad</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->profile->user->name }}</td>
                    <td>{{ $patient->profile->user->lastname }}</td>
                    <td>{{ $patient->profile->user->dni }}</td>
                    <td>{{ $patient->lifesheet->coverage->name }}</td>
                    <td>{{ $patient->profile->city->name }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
