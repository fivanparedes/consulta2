    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title">Profesionales</h4>
                                    <p class="card-category">Lista de profesionales inscriptos al sistema.</p>
                                </div>
                                <div class="col">
                                    @if (Auth::user()->hasPermission('ProfessionalController@create'))
                                        <a class="btn bg-primary text-light" href="{{ url('/manage/professionals/create') }}">+ Adherir nuevo profesional</a>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                        <div class="card-body table-full-width table-responsive">
                            @if ($professionals->count() == 0)
                                <p class="ml-5 card-category">¡Aún no se inscribieron profesionales al sistema!</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('user.dni', 'DNI')</th>
                                        <th>@sortablelink('user.lastname', 'Apellido(s)')</th>
                                        <th>@sortablelink('user.name', 'Nombre(s)')</th>
                                        <th>@sortablelink('licensePlate', 'Matrícula')</th>
                                        <th>@sortablelink('city.name', 'Ciudad')</th>
                                        <th>@sortablelink('specialty.displayname', 'Especialidad')</th>
                                        <th>@sortablelink('created_at', 'Fecha de registro')</th>
                                        <th>@sortablelink('status', 'Estado')</th>
                                        <th>Más</th>
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
                                                        <span class="badge badge-secondary">Inhabilitado</span>
                                                    @else
                                                        <span class="badge badge-secondary bg-success">Habilitado</span>
                                                    @endif
                                                </td>
                                                <td><a class="nav-link"
                                                        href="/manage/professionals/edit/{{ $professional->id }}">
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
