@extends('layouts.app', ['activePage' => 'practices', 'title' => $companyName.' | Editar práctica profesional',
'navName' =>
'Configuración', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="flex">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Crear práctica profesional</h1>
                    <p>La práctica tendrá un precio, un coseguro o copago (si la obra social lo exige) y está vinculada al
                        Nomenclador Nacional.</p>
                </div>
                @include('alerts.errors')
            </div>
            <form id="create-form" class="form-horizontal" action="{{ url('/practices') }}" method="post">
                @csrf
                <div class="card" id="card-one">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="input-name">Nombre de la práctica</label>
                                    <input class="form-control" type="text" name="name" id="input-name"
                                        placeholder="Ej: Psicodiagnóstico">
                                </div>
                                <div class="form-group">
                                    <label for="input-coverage">Obra social:</label>
                                    <select name="coverage" id="input-coverage" class="form-control">
                                        @foreach ($coverages as $coverage)
                                            <option value="{{ $coverage->id }}">{{ $coverage->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="input-nomenclature">Nomenclatura</label>
                                    <select name="nomenclature" id="input-nomenclature" class="form-control">
                                        @foreach ($nomenclatures as $nom)
                                            <option value="{{ $nom->id }}">{{ $nom->code . ' ' . $nom->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="input-description">Descripción</label>
                                    <input type="text" id="input-description" class="form-control" name="description">
                                </div>
                            </div>
                            <div class="col ml-10">
                                <div class="form-group">
                                    <label for="input-maxtime">Tiempo máximo (en minutos)</label>
                                    <input type="number" id="input-maxtime" class="form-control" name="maxtime">
                                </div>
                                <div class="form-group">
                                    <label for="input-price">Precio total</label>
                                    <input type="decimal" class="form-control" id="input-price" name="price">
                                </div>
                                <div class="form-group">
                                    <label for="input-copayment">Coseguro (monto mínimo abonado por afiliados a la obra
                                        social)</label>
                                    <input type="decimal" class="form-control" id="input-copayment" name="copayment">
                                </div>
                                <div class="form-group">
                                    <p>Modalidades aceptadas</p>
                                    <div class="form-check-inline">
                                        <input type="radio" class="form-check-input" name="allowed_modes"
                                            id="allowed_modes0" value="0">
                                        <label class="form-check-label"
                                            for="allowed_modes0"><strong>Presencial</strong></label>

                                    </div>
                                    <div class="form-check-inline">
                                        <input type="radio" class="form-check-input" name="allowed_modes"
                                            id="allowed_modes1" value="1">
                                        <label class="form-check-label"
                                            for="allowed_modes1"><strong>Virtual</strong></label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="radio" class="form-check-input" name="allowed_modes"
                                            id="allowed_modes2" checked>
                                        <label class="form-check-label" for="allowed_modes2"><strong>Ambos</strong></label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <hr>
                    <div class="form-group text-center">
                        <a id="btn-next" class="btn btn-danger text-light" href="{{ url('/practices') }}"> Cancelar</a>
                        <button type="submit" class="btn btn-light text-dark">Guardar</button>
                    </div>
                </div>


        </div>
    </div>
    </form>
    </div>
    </div>
@endsection
