
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <h4 class="card-title">Sesiones y consultas</h4>
                            <p class="card-category">Lista de sesiones pasadas, presentes y futuras.</p>
                        </div>
                        <div class="card-body table-full-width table-responsive">
                            @if (isset($professional))
                                @if ($professional->status == 0)
                                <div class="alert alert-info">
                                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                                        <i class="nc-icon nc-simple-remove"></i>
                                    </button>
                                    <span>
                                        <b> Información: </b> Para poder recibir turnos, primero debe ser aprobado por el
                                        sistema.</span>
                                </div>
                            @endif
                            @endif
                            
                            @if ($cites->count() == 0)
                                <p class="ml-5 card-category">No hay sesiones agendadas.</p>
                            @else
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Nombre y apellido</th>
                                        <th>Fecha y hora</th>
                                        <th>¿Asistió?</th>
                                        <th>'Modalidad'</th>
                                        <th>¿Confirmó asistencia?</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($cites as $cite)
                                            <tr>
                                                <td>{{ $cite->id }}</td>
                                                <td>{{ $cite->title }}</td>
                                                <td>{{ date('d-m-Y h:i', strtotime($cite->start)) }}</td>
                                                <td>
                                                    @if ($cite->assisted)
                                                        Sí.
                                                    @else
                                                        No.
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($cite->isVirtual)
                                                        Virtual
                                                    @else
                                                        Presencial.
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($cite->confirmed)
                                                        Confirmado.
                                                    @else
                                                        Sin confirmar.
                                                    @endif
                                                </td>
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