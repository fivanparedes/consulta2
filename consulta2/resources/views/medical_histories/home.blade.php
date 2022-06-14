@extends('layouts.app', ['activePage' => 'medical_histories', 'title' => $companyName.' | Mi historia clínica',
'navName' => 'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-body">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col col-md-12 left">
                                        <h4 class="card-title">Mi historia clínica</h4>
                                        <p class="card-category">Evaluaciones, atenciones, estudios...</p>
                                    </div>
                                </div>
                                @if ($medicalHistories->count() > 0)
                                <div class="card-header table">
                            <form class="form-inline" action="{{ url('/medical_history') }}" method="GET">
                                <div class="row ml-4">
                                    <p class="pt-1 ">Filtro</p>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter2">Prestador</label>
                                            <input type="text" class="form-control" id="filter2" name="filter2"
                                                style="width: 97%;" placeholder="Nombre o apellido"
                                                value="{{ isset($filter2) ? $filter2 : '' }}">
                                        </div>
                                    </div>

                                    <div class="col" style="width: 10%;">
                                        <div class="form-group">
                                            <label for="filter3">Desde</label>
                                            <input type="date" class="form-control" id="filter3" name="filter3"
                                                style="width: 97%;" placeholder="Desde"
                                                value="{{ isset($filter3) ? $filter3 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter4">Hasta</label>
                                            <input type="date" class="form-control" id="filter4" name="filter4"
                                                style="width: 97%;" placeholder="Hasta"
                                                value="{{ isset($filter4) ? $filter4 : '' }}">
                                        </div>
                                    </div>
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <label for="filter5">Rubro</label>
                                            <input type="text" class="form-control" name="filter5" value="{{ isset($filter5) ? $filter5 : '' }}" placeholder="Rubro">
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-4">
                                    <div class="col" style="width: 10%;">
                                        <div class="">
                                            <button type="submit"
                                                class="btn bg-primary mb-2 ml-5 text-light">Filtrar</button>
                                        </div>
                                    </div>
                                    <div class="col pr-3" style="width: 10%;">
                                        <div class="">
                                            <a href="/cite" class="btn bg-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                    <div class="col" style="width:10%">
                                        <a class="btn bg-primary text-light" href="/medical_history_pdf?filter1={{ $filter1 }}&filter2={{ $filter2 }}&filter3={{ $filter3 }}&filter4={{ $filter4 }}&filter5={{ $filter5 }}" title="Generar PDF">
                                                <i class="fa fa-file-pdf"></i>
                                            </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                                    <div class="col col-pl-4 mt-5">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <th>@sortablelink('start', 'Fecha y hora')</th>
                                                <th>@sortablelink('professionalProfile', 'Prestador')</th>
                                                <th>@sortablelink('specialty', 'Rubro')</th>
                                                <th>@sortablelink('isVirtual', 'Modalidad')</th>
                                                <th>Tipo de consulta</th>
                                                <th>@sortablelink('resume', 'Resumen')</th>
                                                <th>Más</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($events as $event)
                                                    @if (isset($event->cite))
                                                        <tr>
                                                            <td>{{ date('d-m-Y h:i', strtotime($event->start)) }}</td>
                                                            <td>{{ $event->professionalProfile->profile->user->name .' ' .$event->professionalProfile->profile->user->lastname }}
                                                            </td>
                                                            <td>{{ $event->professionalProfile->specialty->displayname }}
                                                            </td>
                                                            <td>
                                                                @if ($event->isVirtual)
                                                                    Virtual
                                                                @else
                                                                    Presencial.
                                                                @endif
                                                            </td>
                                                            <td>{{ $event->consultType->name }}</td>
                                                            <td>
                                                                {{ $event->cite->resume != null ? decrypt($event->cite->resume) : "Consulta ambulatoria" }}
                                                            </td>
                                                            <td><a class="nav-link"
                                                                    href="/profile/events/{{ $event->id }}" title="Más">
                                                                    <i class="nc-icon nc-badge"></i>
                                                                </a></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! $events->appends('events')->links('vendor.pagination.bootstrap-4') !!}
                                    </div>
                                @else
                                    <div class="col-pl-4 mt-5">
                                        <h4>No hay registros médicos suyos en el sistema actualmente.</h4>
                                    </div>
                                @endif
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
