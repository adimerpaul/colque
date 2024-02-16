@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1>
            Egreso
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'cajas-lab.store', 'id' => 'formularioRegistro']) !!}


                    <div class="form-group col-sm-6">
                        {!! Form::label('proveedorId', 'Proveedor: *') !!}
                        <a data-toggle="modal" data-target="#modalProveedorRegistro" title="Agregar"
                           class='btn btn-primary btn-xs pull-right'><i
                                class="glyphicon glyphicon-plus"></i></a>

                        {!! Form::select('proveedor_id',  [null => 'Seleccione...'] +\App\Models\Lab\Proveedor::get()->pluck('info_proveedor', 'id')->toArray(), null
                    , ['class' => 'form-control select2', 'required', 'id' =>'proveedor_id']) !!}

                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('glosa', 'Glosa: *') !!}
                        {!! Form::select('glosa',  [null => 'Seleccione...'] +\App\Models\Lab\CuentaContable::whereTipo('Egreso')->get()->pluck('descripcion', 'descripcion')->toArray(), null
                    , ['class' => 'form-control select2', 'required', 'id' =>'glosa']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('factura', 'Factura:') !!}
                        {!! Form::text('factura',  null,
                            ['class' => 'form-control',  'id' => 'factura', 'maxlength' => '80']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
                        {!! Form::select('metodo', \App\ Patrones\Fachada::listarTiposPagos(), null,
                            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodo()']) !!}
                    </div>

                    <div class="form-group col-sm-6" id="nroRecibo">
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '80', 'required', 'id' => 'numero_recibo']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @include("lab.proveedores.modal_registro")
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $("#formularioRegistro").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#nroRecibo").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
        });
        function getProveedores() {
            let url = "{{ url('get-proveedores-lab') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="proveedor_id"]').empty();
                    $('select[name="proveedor_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="proveedor_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }
        function cambiarMetodo() {
            const input = document.getElementById('numero_recibo');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#nroRecibo").show();
                input.setAttribute('required', '');
            } else {
                $("#nroRecibo").hide();
                input.removeAttribute('required', '');
            }
        }
    </script>
@endpush
