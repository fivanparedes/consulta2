@extends('layouts.app', ['activePage' => 'statistics', 'title' => $companyName.' | Panel de estadística', 'navName' =>
'Estadísticas', 'activeButton' => 'laravel'])

@section('content')
    <style>
        .ct-bar {
            stroke-width: 60px
        }

    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-header ">
                            <h4 class="card-title">{{ __('Obras sociales más atendidas') }}</h4>
                            <p class="card-category">{{ __('Histórico en base a los turnos y consultas registradas') }}
                            </p>
                            <div class="form-group">
                                <label for="input-datestart">Rango de fechas</label>
                                <input type="date" name="datestart" id="input-datestart" class="form-control" value="{{ date('Y-m-d', strtotime(now() . '-30 day')) }}" max="{{ date_create()->format('Y-m-d') }}" onchange="refreshPieChart()">
                            </div>
                            <div class="form-group">
                                <input type="date" name="dateend" id="input-dateend" class="form-control" value="{{ date('Y-m-d', strtotime(now())) }}" max="{{ date_create()->format('Y-m-d') }}" onchange="refreshPieChart()">
                            </div>
                        </div>
                        <div class="card-body ">
                            <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                            <div class="legend">
                                <i class="fa fa-circle text-info"></i> <span id="particular"></span>
                                <i class="fa fa-circle text-danger"></i><span id="most_used_1"></span>
                                <i class="fa fa-circle text-warning"></i><span id="most_used_2"></span>
                                <i class="fa fa-circle" style="color: purple"></i><span id="most_used_3"></span>
                                <i class="fa fa-circle" style="color: lime"></i><span id="least_used"></span>
                            </div>
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> {{ __('Se actualiza al entrar a esta pantalla.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card ">
                            <div class="card-header ">
                                <h4 class="card-title">{{ __('Rangos etarios más típicos') }}</h4>
                                <p class="card-category">
                                    {{ __('Histórico basado en los turnos agendados y consultas realizadas') }}</p>
                                    <div class="form-group">
                                <label for="input-agestart">Rango de fechas</label>
                                <input type="date" name="agestart" id="input-agestart" class="form-control" value="{{ date('Y-m-d', strtotime(now() . '-30 day')) }}" max="{{ date_create()->format('Y-m-d') }}" onchange="refreshBarChart()">
                            </div>
                            <div class="form-group">
                                <input type="date" name="ageend" id="input-ageend" class="form-control" value="{{ date('Y-m-d', strtotime(now())) }}" max="{{ date_create()->format('Y-m-d') }}" onchange="refreshBarChart()">
                            </div>
                            </div>
                            <div class="card-body ">
                                <div id="chartActivity" class="ct-chart"></div>
                            </div>
                            <div class="card-footer ">
                                <div class="legend">
                                    {{ __('Rangos etarios en años de edad') }}
                                </div>
                                <hr>
                                <div class="legend">
                                    {{ __('Eje vertical: Consultas realizadas y turnos tomados hasta la fecha.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            refreshBarChart();
            refreshPieChart();
        });

        function refreshPieChart() {
            var datestart = $('#input-datestart').val();
            var dateend = $('#input-dateend').val();
            // Todo el renderizado de gráficos lo hago en el front, el controlador StatisticsController solo se encarga de juntar la data.
            var dataPreferences = {
                series: [
                    [25, 30, 20, 25]
                ]
            };

            var optionsPreferences = {
                donut: true,
                donutWidth: 40,
                startAngle: 0,
                total: 100,
                showLabel: false,
                axisX: {
                    showGrid: false
                }
            };
            $('#particular').empty();
            $('#most_used_1').empty();
            $('#most_used_2').empty();
            $('#most_used_3').empty();
            $('#least_used').empty();
            $.ajax({
                method: 'GET',
                url: '/statistics/loadCoverageComparison',
                dataType: 'json',
                data: {
                    datestart: datestart,
                    dateend: dateend
                },
                success: function(response) {
                    if (response.status == "success") {
                        $('#chartPreferences').empty();
                        console.log(JSON.stringify(response));
                        var series = response.series;
                        var labels = response.labels;
                        var names = response.names;
                        pieData = {
                            
                        };
                        Chartist.Pie('#chartPreferences', {
                            labels: labels,
                            series: series
                        });
                        $('#particular').append(names[0]);
                        $('#most_used_1').append(names[1]);
                        $('#most_used_2').append(names[2]);
                        $('#most_used_3').append(names[3]);
                        $('#least_used').append(names[4]);
                    } else {
                        console.log(JSON.stringify(response));
                        Chartist.Pie('#chartPreferences', {
                            labels: ['Faltan datos.', '-', '-', '-', '-'],
                            series: [100, 0, 0, 0, 0]
                        });
                    }
                },
                error: function(response) {
                    console.log(JSON.stringify(response));
                    Chartist.Pie('#chartPreferences', {
                        labels: ['Faltan datos.', '-', '-', '-', '-'],
                        series: [100, 0, 0, 0, 0]
                    });
                    $('#particular').append('<p>' + pieData.labels[0] + '</p>');
                    $('#most_used_1').append('<p>' + pieData.labels[1] + '</p>');
                    $('#most_used_2').append('<p>' + pieData.labels[2] + '</p>');
                    $('#most_used_3').append('<p>' + pieData.labels[3] + '</p>');
                    $('#least_used').append('<p>' + pieData.labels[4] + '</p>');
                }
            });
        }

        function refreshBarChart() {
            var agestart = $('#input-agestart').val();
            var ageend = $('#input-ageend').val();
            var options = {
                seriesBarDistance: 10,
                axisX: {
                    showGrid: false
                },
                height: "245px",
                seriesBarWidth: "200px"
            };

            var responsiveOptions = [
                ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                    axisX: {
                        labelInterpolationFnc: function(value) {
                            return value[0];
                        }
                    }
                }]
            ];
                 $('#particular').empty();
            $('#most_used_1').empty();
            $('#most_used_2').empty();
            $('#most_used_3').empty();
            $('#least_used').empty();
            $.ajax({
                method: 'GET',
                url: '/statistics/loadAgeRange',
                dataType: 'json',
                data: {
                    datestart: agestart,
                    dateend: ageend
                },
                success: function(response) {
                    if (response.status == "success") {
                        $('#chartActivity').empty();
                        console.log(JSON.stringify(response));
                        var series = response.series;
                        var data = {
                            labels: ['0-9', '10-19', '20-29', '30-39', '40-49', '50-59', '60-69',
                                '+70'
                            ],
                            series: [
                                [series[0], series[1], series[2], series[3], series[4], series[5], series[6], series[7]],
                            ]
                        };
                        var chartActivity = Chartist.Bar('#chartActivity', data, options,
                            responsiveOptions);
                    } else {
                        console.log(JSON.stringify(response));
                        var data = {
                            labels: ['S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D',
                                'S/D'
                            ],
                            series: [
                                [10, 10, 10, 10, 10, 10, 10, 10],
                            ]
                        };
                        var chartActivity = Chartist.Bar('#chartActivity', data, options,
                            responsiveOptions);
                    }
                },
                error: function (response) {
                    console.log(JSON.stringify(response));
                    var data = {
                            labels: ['S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D',
                                'S/D'
                            ],
                            series: [
                                [10, 10, 10, 10, 10, 10, 10, 10],
                            ]
                        };
                        var chartActivity = Chartist.Bar('#chartActivity', data, options,
                            responsiveOptions);
                }
            });
        }
    </script>
@endpush
