@extends('layouts.app', ['activePage' => 'professional_show', 'title' => 'Consulta2 | Confirmar turno', 'navName' => 'Agendar un turno', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2>Turno agendado exitosamente!</h2>
                                <strong>Nombre del profesional: </strong>
                                <p>{{ $professional->profile->user->name . ' ' . $professional->profile->user->lastname }}
                                </p>
                                <input type="hidden" name="profid" value="{{ $professional->profile->user->id }}">
                                <strong>Área:</strong>
                                <p> {{ $professional->specialty->displayname }}</p>
                                <strong>Fecha y hora: </strong>
                                <p> {{ $selectedDate }}</p>
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <strong>Tipo de consulta:</strong>
                                <p> {{ $consult_type->name }}</p>
                                <input type="hidden" name="consult-type" value="{{ $consult_type->id }}">
                                <input type="hidden" name="practice-id" value="{{ $practice->id }}">
                                <strong>Modalidad:</strong>
                                <p>
                                    @if ($isVirtual == 0)
                                        Presencial
                                    @else
                                        Virtual
                                    @endif
                                </p>
                                <input type="hidden" name="isVirtual" value="{{ $isVirtual }}">
                                <strong>Precio:</strong>
                                <p>
                                    
                                        @if ($practice->price->price == 0)
                                            @if ($practice->price->copayment == 0)
                                                No es necesario abonar nada.
                                            @else
                                                El afiliado debe abonar ${{ $practice->price->copayment }}
                                            @endif
                                        @else
                                            @if (Auth::user()->profile->patientProfile->lifesheet->coverage_id == 1 || $practice->coverage->id == 1)
                                                El afiliado debe abonar ${{ $practice->price->price }}
                                            @else
                                                @if ($practice->price->copayment == 0)
                                                    No es necesario abonar nada.
                                                @else
                                                    El afiliado debe abonar ${{ $practice->price->copayment }}
                                                @endif
                                            @endif
                                        @endif
                                    

                                </p>
                                <hr>
                                <strong>Confirmar:</strong>
                                <a class="btn btn-dark text-light" href="{{ url('professionals/list/') }}">Volver al listado</a>
                                <button class="btn btn-primary text-light" type="submit">Imprimir comprobante</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
