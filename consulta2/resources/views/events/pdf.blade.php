<html>

<head>
    <title>Consulta2 | Comprobante - Turno agendado</title>
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
            <h2>Comprobante de turno</h2>
            <br>
            </div>
    <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2>Turno agendado exitosamente!</h2>
                            <strong>Nombre del prestador: </strong>
                            <p>{{ $event->professionalProfile->profile->user->lastname . ' ' . $event->professionalProfile->profile->user->name }}
                            </p>
                            <strong>Área:</strong>
                            <p> {{ $event->professionalProfile->specialty->displayname }}</p>
                            <strong>Fecha y hora: </strong>
                            <p> {{ date_create($event->start)->format('D M Y H:i') }}</p>
                            <strong>Tipo de consulta:</strong>
                            <p> {{ $event->consultType->name }}</p>
                            <strong>Modalidad:</strong>
                            <p>
                                @if ($event->isVirtual == 0)
                                    Presencial
                                @else
                                    Virtual
                                @endif
                            </p>
                            <strong>Precio:</strong>
                            <p>

                                @if ($event->cite->total == 0)
                                    No es necesario abonar nada.
                                @else
                                    El afiliado debe abonar ${{ $event->cite->total }}.
                                @endif

                            </p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>