@extends('layouts.app', ['activePage' => 'professional_show', 'title' => 'Consulta2 | Ver perfil ', 'navName' => 'Ver perfil', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h2>{{ $professional->name }}  {{ $professional->lastname }}</h2>
                <h3><span class="badge badge-secondary">{{ $professional->field }} </span> <span class="badge badge-secondary">{{ $professional->specialty }}</span></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Reservar fecha</h4>
                <form action="/event/store">
                    <input type="date" name="day" id="input-day"/>
                    <input type="hidden" name="profid" value="{{ $professional->id }}"/>
                    <div class="form-group">
                        @foreach ($workingHours as $hour)
                            <span class="badge badge-secondary"><input type="radio" name="time" id="input-time"><p>{{ $hour->time }}</p></span>
                        @endforeach
                    </div>
                    
                    <button type="submit">Reservar turno</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#input-date').datepicker({
                format: 'mm/dd/yyyy',
                beforeShowDay: function(date){
                var dayNr = date.getDay();
                if (dayNr==0  ||  dayNr==6){
                    if (enableDays.indexOf(formatDate(date)) >= 0) {
                        return true;
                    }
                return false;
            }
            if (disabledDays.indexOf(formatDate(date)) >= 0) {
               return false;
            }
            return true;
        }
   });
        });
        $('#input-time').change(function() {

        });
    </script>
@endsection