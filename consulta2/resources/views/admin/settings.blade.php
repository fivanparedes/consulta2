@extends('layouts.app', ['activePage' => 'config', 'title' => 'Consulta2 | Ajustes de configuración', 'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Configuración</h1>
                    <p>Listado de distintos parámetros. <span class="text-danger"><i class="fa fa-danger"></i>
                            Advertencia: información delicada</span></p>
                </div>
                <div class="card-body">
                    <form action="{{ url('/config/settings') }}" method="post">
                        @method('patch')
                        @csrf
                        <div class="form-group">
                            <label for="timezone">Zona horaria</label>
                            <select name="timezone" id="timezone" class="form-control">
                                <option value="-0300" @if ($timezone == "-0300")
                                    selected
                                @endif>UTC -3</option>
                                <option value="-0200" @if ($timezone == "-0200")
                                    selected
                                @endif>UTC -2</option>
                                <option value="-0100" @if ($timezone == "-0100")
                                    selected
                                @endif>UTC -1</option>
                                <option value="-0000" @if ($timezone == "-0000")
                                    selected
                                @endif>UTC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dayrange">Rango máximo de días para agendar en el calendario</label>
                            <input type="number" name="dayrange" class="form-control" value="{{ $calendar_range }}">
                        </div>
                        <div class="form-group">
                            <label for="charge">Recargo por consulta adeudada (%)</label>
                            <input type="number" name="charge" class="form-control" value="{{ $recharge }}">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="register-patient">Habilitar registro de pacientes</label>
                                    <select name="register-patient" id="register-patient" class="form-control">
                                        <option value="1" @if ($patient_register == "1")
                                    selected
                                @endif>Sí</option>
                                        <option value="0" @if ($patient_register == "0")
                                    selected
                                @endif>No</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="register-prof">Habilitar registro de prestadores</label>
                                    <select name="register-professional" id="register-prof" class="form-control">
                                        <option value="1" @if ($professional_register == "1")
                                    selected
                                @endif>Sí</option>
                                        <option value="0" @if ($professional_register == "0")
                                    selected
                                @endif>No</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <a href="{{ url('/config') }}" class="btn bg-secondary text-light">Volver atrás</a>
                            <button class="btn bg-primary text-light" type="submit">Guardar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>
    <script>

    </script>
@endsection
