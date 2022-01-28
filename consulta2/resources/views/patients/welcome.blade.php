@extends('layouts.app', ['activePage' => 'dashboard', 'title' => 'Consulta2 | Panel', 'navName' => 'Dashboard', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header ">
                            <h4 class="card-title">Agendar un nuevo turno</h4>
                            <p class="card-category">Elija cómo quiere encontrar a su profesional</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col">
                                    <a class="btn bg-primary text-light" href="{{ url('/professionals/list') }}">Ver lista de prestadores</a>
                                </div>
                                <div class="col">
                                    <a href="{{ url('/institutions/list') }}" class="btn bg-primary text-light">Ver centros de salud cercanos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <div class="card-header ">
                            <h4 class="card-title">{{ __('2017 Sales') }}</h4>
                            <p class="card-category">{{ __('All products including Taxes') }}</p>
                        </div>
                        <div class="card-body ">
                            <div id="chartActivity" class="ct-chart"></div>
                        </div>
                        <div class="card-footer ">
                            <div class="legend">
                                <i class="fa fa-circle text-info"></i> {{ __('Tesla Model S') }}
                                <i class="fa fa-circle text-danger"></i> {{ __('BMW 5 Series') }}
                            </div>
                            <hr>
                            <div class="stats">
                                <i class="fa fa-check"></i> {{ __('Data information certified') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card  card-tasks">
                        <div class="card-header ">
                            <h4 class="card-title">{{ __('Tasks') }}</h4>
                            <p class="card-category">{{ __('Backend development') }}</p>
                        </div>
                        <div class="card-body ">
                            
                        </div>
                        <div class="card-footer ">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // Javascript method's body can be found in assets/js/demos.js
            demo.initDashboardPageCharts();

            demo.showNotification();

        });
    </script>
@endpush