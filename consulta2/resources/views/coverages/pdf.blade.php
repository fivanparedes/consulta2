    <html>

    <head>
        <title>Consulta2 | Informe - Obras sociales</title>
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
                        src='https://static.vecteezy.com/system/resources/previews/000/499/145/original/vector-health-sign-icon-design.jpg'
                        width="100" height="100" alt="Logo" />
                </div>
                <div class="col">
                    <h1 style="position: relative; float: right;">Consulta2</h1>
                </div>
            </div>
        </div>
        <hr style="clear: both;">
        <div class="row">
            <h2>Informe - Obras sociales</h2>
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
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($coverages as $coverage)
                    <tr>
                        <td>{{ $coverage->id }}</td>
                        <td>{{ $coverage->name }}</td>
                        <td>{{ $coverage->address }}</td>
                        <td>{{ $coverage->phone }}</td>
                        <td>{{ $coverage->city->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

    </html>
