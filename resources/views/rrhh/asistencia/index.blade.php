@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Asistencia</h1>
        <h1 class="pull-right"></h1>
    </section>
    <section class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div>
                                {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                {!! Form::open(['route' => 'asistencias.index', 'method' => 'get']) !!}
                                    <div class="form-group col-sm-3">
                                        {!! Form::label('fecha_i', 'Fecha Inicial:') !!}
                                        {!! Form::date('fecha_i', isset($_GET['fecha_i']) ? $_GET['fecha_i'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months')), ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group col-sm-3">
                                        {!! Form::label('fecha_f', 'Fecha Final:') !!}
                                        {!! Form::date('fecha_f', old('fecha_f', isset($_GET['fecha_f']) ? $_GET['fecha_f'] : date('Y-m-d')), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group col-sm-3">
                                        {!! Form::label('personal_id', 'Trabajador :') !!}
                                        {!! Form::select('personal_id', ['%' => 'Todos']+\App\Patrones\Fachada::getPersonal(),isset($_GET['personal_id']) ? $_GET['personal_id'] : null, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group col-sm-1" style="margin-top: 24px">
                                        <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar Datos"></button>
                                    </div>
                                {!! Form::close() !!}
                                <div class="form-group col-sm" style="margin-top: 26px">
                                    <a class="btn btn-success fa fa-file"
                                        href="#"
                                        data-target="#modalImportar"
                                        data-toggle="modal"
                                        title="Importar documento"> Importar</a>
                                </div>
                            </div>
                        
                          @if($mensaje = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{$mensaje}}
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('rrhh.asistencia.asistencia_table')
                    </div>
                </div>
                <div class="text-center">
                    {{ $resultados->appends($_GET)->links() }}
                    
                </div>
            </div>
        </div>
        @include("rrhh.asistencia.modal_importar")

          
           
    </section>
    <script>
        function importarAsistencia() {
        // Obtener el formulario de importación
        var form = document.getElementById('formImportar');
        
        // Enviar el formulario mediante AJAX
        var formData = new FormData(form);
        $.ajax({
            url: form.action,
            method: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Manejar la respuesta exitosa si es necesario
                console.log(response);
                // Cerrar el modal
                $('#modalImportar').modal('hide');
                // Actualizar la vista o hacer alguna acción adicional si es necesario
                location.reload(); // Ejemplo: recargar la página
            },
            error: function(xhr, status, error) {
                // Manejar el error si es necesario
                console.error(error);
            }
        });
    }

    </script>

@endsection

