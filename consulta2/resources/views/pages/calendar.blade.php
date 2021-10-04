@extends('layouts.app', ['activePage' => 'calendar', 'title' => 'Consulta2 | Agendar turnos', 'navName' => 'Agendar un turno', 'activeButton' => 'laravel'])

@section('content')
<div class="container">
    <p id="cuser_id" style="opacity: 0">{{auth()->user()->id}}</p>
    <div id="calendar" style="margin-top:1em"></div>
</div>
<script>
    $(document).ready(function () {
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    var today = new Date();
    // TODO: PARAMETRIZE THE CALENDAR AS MUCH AS POSSIBLE
    var calendar = $('#calendar').fullCalendar({
        height: 700,
        editable:false,
        nowIndicator: true,
        eventOverlap: false,
        header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
        },
        events:'/show-event-calendar',
        slotDuration: '00:50',
        minTime: '08:00:00',
        maxTime: '20:00:00',
        validRange: {
            start: today.setDate(today.getDate() - 1), //este recobeco es para sacar 1 dia antes
        },
        selectable:true,
        selectOverlap: false,
        selectHelper: true,
        select:function(start, end, allDay)
        {
            if (calendar.view == 'month') {
                calendar.changeView('agendaWeek');
            }
            if(confirm("¿Desea agendar un turno en esta fecha y hora?"))
            {
                var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

                var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                $.ajax({
                    url:"/manage-events",
                    type:"POST",
                    data:{
                        title: "Nuevo turno",
                        start: start,
                        end: end,
                        user_id: 1,
                        type: 'create'
                    },
                    success:function(data)
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Turno agendado.");
                    }
                })
            }
        },
        editable:false,
        /* eventResize: function(event, delta)
        {
            var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
            var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
            var title = event.title;
            var id = event.id;
            $.ajax({
                url:"/manage-events",
                type:"POST",
                data:{
                    title: title,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update'
                },
                success:function(response)
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Updated Successfully");
                }
            })
        }, */
        eventDrop: function(event, delta)
        {
            var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
            var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
            var title = event.title;
            var id = event.id;
            if (title != null) {
                $.ajax({
                url:"/manage-events",
                type:"POST",
                data:{
                    title: title,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update'
                },
                success:function(response)
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Turno actualizado.");
                }
            })
            }
            
        },

        eventClick:function(event)
        {
            var id = event.id;      
            if (event.title != null) {
                if(confirm("¿Desea remover este turno?"))
                {
                $.ajax({
                    url:"/manage-events",
                    type:"POST",
                    data:{
                        id:id,
                        type:"delete"
                    },
                    success:function(response)
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Turno eliminado. Puede volver a agendar en otra fecha.");
                    }
                })
            }}
            
        }
    });

});
  
</script>
@endsection
