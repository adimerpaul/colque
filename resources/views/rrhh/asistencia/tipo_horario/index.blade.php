@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tipo Horario</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right " 
                href="#" 
                onclick="agregarTipoHorario()" 
                title="Agregar">Agregar</a>
        </h1>
    </section>
    <section class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class=¨card¨>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @if($mensaje = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{$mensaje}}
                                </div>
                            @endif
                        </div>
                        <hr>
                    </div>
                    <div class="table-responsive">
                        @include('rrhh.asistencia.tipo_horario.table')
                    </div>
                </div>
                <div class="text-center">
                    
                    
                </div>
            </div>
        </div>
        <div class="text-center">
                    {{ $tiposHorarios->links() }}
                    
        </div>
        
        @include("rrhh.asistencia.tipo_horario.modal_create")
        @include("rrhh.asistencia.tipo_horario.modal_edit")
                
    </section>
    <script>
        

    function agregarTipoHorario() {
            $("#modalCreate").modal("show");
        }
    
    // Al abrir el modal, asigna el valor del atributo data-txtid al input hidden y al campo de texto de descripción
    $('#modalEdit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var id = button.data('txtid');
            var descripcion = button.data('txtdescripcion'); // Valor del atributo data-descripcion

            var modal = $(this);
            modal.find('.modal-body #idTipo').val(id); // Asigna el valor al input hidden con idTipo
            modal.find('.modal-body #idDescripcion').val(descripcion); // Asigna el valor al campo de texto con miIdDeDescripcion
        });

        // Deshabilitar el botón de guardar al enviar el formulario
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });

    </script>


@endsection
