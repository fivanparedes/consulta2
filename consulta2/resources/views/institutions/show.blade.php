@extends('layouts.app', ['activePage' => 'institutions', 'title' => $companyName.' | '.$institution->name, 'navName'
=> 'Agendar turno', 'activeButton' => 'laravel'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card strpied-tabled-with-hover">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col">
                                    <h2 class="card-title">{{ $institution->name }}</h2>
                                    <p class="card-category">{{ $institution->description }}</p>
                                </div>
                                <div class="col">
                                    <h4>Contacto</h4>
                                    <p><strong>Dirección:</strong> {{ $institution->address }}</p>
                                    <p><strong>Teléfono:</strong> {{ $institution->phone }}</p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="specialty"></label>
                                        <select name="specialty" id="specialty" class="form-control" onchange="refreshList()">
                                            <option value="0">Todas las especialidades</option>
                                            @foreach (\App\Models\Specialty::all() as $specialty)
                                                <option value="{{ $specialty->id }}">{{ $specialty->displayname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="location"></label>
                                        <select name="location" id="location" class="form-control" onchange="refreshList()">
                                            <option value="city">Mi ciudad</option>
                                            <option value="province">Mi provincia</option>
                                            <option value="all">Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div id="listbody">
                
            </div>


        </div>
    </div>
    <script>
        $(document).ready(function() {
            refreshList();
        });

        function refreshList() {
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            $('#listbody').empty();
            $('#listbody').append(
                '<div class="row"><div class="col-md-12"><div class="card"><div class="card-body"><h4 class="card-title font-weight-bold">Cargando...</h4> <h5 class = "card-subtitle" ><span class = "badge badge-primary" ><i class="fa fa-spinner"></i> </span> </h5> <p class = "card-text" >Cargando...</p> </div> </div> </div> </div>');
                $.ajax({
                    method: 'GET',
                    url: '/institution/<?php echo encrypt($institution->id) ?>/getFilteredList',
                    headers: headers,
                    data: {
                        specialty: $('#specialty option:selected').val(),
                        location: $('#location option:selected').val(),
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (response.status == "success") {
                            let content = response.content;
                            if (content.length > 0) {
                                $('#listbody').empty();
                                for (var i = 0; i < content.length; i++) {
                                    
                                    $('#listbody').append(
                                        '<div class="row"><div class="col-md-12"><div class="card"><div class="card-body"><h4 class="card-title font-weight-bold">'+content[i].fullname+'</h4> <h5 class = "card-subtitle" ><span class = "badge badge-primary" >'+content[i].specialty+'</span> </h5> <p class = "card-text" >'+content[i].institution+'</p><p class="card-text"><strong>Cobertura médica: </strong>'+content[i].coverages+'</p> <a href = "/professional/show/'+content[i].id+'"class = "btn btn-primary" > Reservar turno</a> </div> </div> </div> </div>');
                
                                }
                            } else {
                                $('#listbody').empty();
                                $('#listbody').append('<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">No hay registros que coincidan con los filtros.</div></div></div></div>');
                            }
                        }
                    },
                    error: function(response) {
                        $('#listbody').empty();
                                $('#listbody').append('<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">No hay registros que coincidan con los filtros.</div></div></div></div>');
                            
                    }
                });
            }
    </script>
@endsection
