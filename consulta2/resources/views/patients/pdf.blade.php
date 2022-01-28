
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-12 left">
                                    <h4 class="card-title">Pacientes</h4>
                                    <p class="card-category">Los pacientes son los que se atienden en los centros de salud.</p>
                                </div>
                                <div class="col col-md-12 right">
                                    <a class="text-light right btn btn-primary"
                                        href="{{ route('patients.create') }}">+
                                        Agregar paciente</a>
                                        @if (Auth::user()->isAbleTo('manage-histories'))
                                            <a href="{{ url('/medical_history/create') }}" class="btn bg-secondary text-light">Agregar historial médico</a> 
                                        @endif
                                        
                                </div>
                            </div>
                        </div>
                        

                        <div class="card-body table-full-width table-responsive">
                            @if ($patients->count() == 0)
                                <p class="ml-5 card-category">No hay pacientes registrados.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>@sortablelink('name', 'Nombre')</th>
                                        <th>@sortablelink('lastname', 'Apellido')</th>
                                        <th>@sortablelink('dni', 'DNI')</th>
                                        <th>@sortablelink('name', 'Obra social')</th>
                                        <th>@sortablelink('name', 'Ciudad')</th>
                                        <th>Editar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($patients as $patient)
                                            <tr>
                                                <td>{{ $patient->profile->user->name }}</td>
                                                <td>{{ $patient->profile->user->lastname }}</td>
                                                <td>{{ $patient->profile->user->dni }}</td>
                                                <td>{{ $patient->lifesheet->coverage->name}}</td>
                                                <td>{{ $patient->profile->city->name }}</td>
                                                <td><a class="nav-link"
                                                        href="/manage/patients/{{ base64_encode(base64_encode($patient->id)) }}/edit">
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
