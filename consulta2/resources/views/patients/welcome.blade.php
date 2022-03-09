@extends('layouts.app', ['activePage' => 'dashboard', 'title' => $companyName.' | Panel', 'navName' => 'Bienvenida',
'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    @if (Auth::user()->isAbleTo('patient-profile'))
                        <div class="col" style="max-width: 20vw">

                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <h2>Inicio</h2>
                                        </div>
                                        <div class="col">
                                            <a class="btn bg-primary text-light"
                                                href="{{ url('/professionals/list') }}">Ver
                                                lista
                                                de prestadores</a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ url('/institutions/list') }}"
                                                class="btn bg-primary text-light">Ver
                                                centros de salud cercanos</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    <div class="col" style="width: 70vw">
                        <div id="calendar" style="margin-top:2px"></div>
                    </div>
                </div>



            </div>
            <script>
                $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var today = new Date();
                    // TODO: PARAMETRIZE THE CALENDAR AS MUCH AS POSSIBLE
                    var calendar = $('#calendar').fullCalendar({
                        height: 700,
                        locale: 'es',
                        editable: false,
                        nowIndicator: true,
                        eventOverlap: false,
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        events: '/show-event-calendar',
                        slotDuration: '01:00',
                        minTime: '08:00:00',
                        maxTime: '20:00:00',
                        validRange: {
                            start: today.setDate(today.getDate() - 1), //este recobeco es para sacar 1 dia antes
                        },
                        selectable: true,
                        selectOverlap: false,
                        selectHelper: true,
                        editable: false,
                        eventClick: function(event) {
                            var id = event.id;
                            window.location.href = {{ Auth::user()->isAbleTo('patient-profile') ? "/profile/events/" : "/cite/" }} + id;
                        }
                    });

                });
            </script>
        @endsection
