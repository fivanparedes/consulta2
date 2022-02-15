@extends('layouts/app', ['activePage' => 'welcome', 'title' => 'Consulta2 | Bienvenido/a'])

@section('content')
    <div class="full-page section-image" data-color="black" data-image="https://img-new.cgtrader.com/items/719713/9c483c1c1f/modern-grey-hospital-building-001-3d-model-max.jpg">
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                        <h1 class="text-white text-center">{{ __('Agenda turnos sin salirte de esta pantalla.') }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();

            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700)
        });
    </script>
@endpush