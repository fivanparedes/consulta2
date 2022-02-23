<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card strpied-tabled-with-hover">
                    <div class="card-header">
                        <div class="row">
                            <div class="col col-md-12 left">
                                <h4 class="card-title">Instituciones</h4>
                                <p class="card-category">Las cuentas institucionales representan los centros de salud
                                    habilitados.</p>
                            </div>
                            <div class="col col-md-12 right">
                                <a class="text-light right btn btn-primary"
                                    href="{{ route('institutions.create') }}">+
                                    Adherir institución</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-full-width table-responsive">
                        @if ($institutions->count() == 0)
                            <p class="ml-5 card-category">No hay instituciones. ¡Pruebe a añadir algunas!</p>
                        @else
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>@sortablelink('id', 'ID')</th>
                                    <th>@sortablelink('name', 'Nombre')</th>
                                    <th>@sortablelink('description', 'Descripción')</th>
                                    <th>@sortablelink('address', 'Dirección')</th>
                                    <th>@sortablelink('phone', 'Teléfono')</th>
                                    <th>@sortablelink('city.name', 'Ciudad')</th>
                                    <th>Editar</th>
                                </thead>
                                <tbody>
                                    @foreach ($institutions as $institution)
                                        <tr>
                                            <td>{{ $institution->id }}</td>
                                            <td>{{ $institution->name }}</td>
                                            <td>{{ $institution->description }}</td>
                                            <td>{{ $institution->address }}</td>
                                            <td>{{ $institution->phone }}</td>
                                            <td>{{ $institution->city->name }}</td>
                                            <td><a class="nav-link"
                                                    href="/institutions/{{ base64_encode(base64_encode($institution->id)) }}/edit">
                                                    <i class="nc-icon nc-badge"></i>
                                                </a></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
