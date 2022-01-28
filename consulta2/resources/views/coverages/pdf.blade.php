    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-12 left">
                                    <h4 class="card-title">Obras sociales</h4>
                                    <p class="card-category">Listado de obras sociales soportadas por el sistema</p>
                                </div>
                            </div>
                        </div>
                        

                        <div class="card-body table-full-width table-responsive">
                            @if ($coverages->count() == 0)
                                <p class="ml-5 card-category">No hay Obras Sociales. ¡Pruebe a añadir algunas!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('address', 'Dirección')</th>
                                        <th>@sortablelink('phone', 'Teléfono')</th>
                                        <th>@sortablelink('city.name', 'Ciudad')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($coverages as $coverage)
                                            <tr>
                                                <td>{{ $coverage->id }}</td>
                                                <td>{{ $coverage->name }}</td>
                                                <td>{{ $coverage->address }}</td>
                                                <td>{{ $coverage->phone }}</td>
                                                <td>{{ $coverage->city->name }}</td>
                                                <td><a class="nav-link"
                                                        href="/coverages/{{ base64_encode(base64_encode($coverage->id)) }}/edit">
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
