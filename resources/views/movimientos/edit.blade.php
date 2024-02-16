@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pagar movimiento
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($movimiento, ['route' => ['movimientos.update', $movimiento->id], 'method' => 'patch', 'id' => 'formularioModal']) !!}
                    <table class="table table-bordered" style="background-color: #f9f9f9;width: 90%; margin-left: 10px">
                        <tr style="height: 40px">
                            <td>
                                <strong>Motivo: </strong> {{ $movimiento->motivo }}
                            </td>
                            <td>
                                <strong>Tipo: </strong> {{ $movimiento->tipo }}
                            </td>
                            <td>
                                <strong>Monto total: </strong> {{ $movimiento->total }} BOB
                            <td>
                                <strong>Saldo: </strong> {{ $movimiento->saldo_pago }} BOB
                            </td>
                        </tr>
                    </table>
                    <hr style="margin-top: -15px">

                    <div class="form-group col-sm-6">
                        {!! Form::label('proveedor_id', 'Proveedor: *') !!}
                        <a data-toggle="modal" data-target="#modalProveedor" title="Agregar"
                           class='btn btn-primary btn-xs pull-right'><i
                                class="glyphicon glyphicon-plus"></i></a>
                        <a onclick="getProveedores()" href="#" title="Refrescar" style="background-color: #d9dde0"
                           class='btn btn-xs pull-right'><i
                                class="glyphicon glyphicon-refresh"></i></a>
                        {!! Form::select('proveedor_id', [null => 'Seleccione...'] +  \App\Models\Proveedor::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('glosa', 'Glosa: *') !!}
                        {!! Form::text('glosa', null, ['class' => 'form-control','required']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
                        {!! Form::select('metodo', \App\ Patrones\Fachada::listarTiposPagos(), null,
                            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodo()']) !!}

                    </div>
                    <div class="form-group col-sm-6" id="nroRecibo">
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::number('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'id' => 'numero_recibo']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include("proveedores.modal")

@endsection

@push('scripts')
    <script>
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
        function getProveedores() {
            let url = "{{ url('get_proveedores') }}";
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
        $(document).ready(function () {
            $("#nroRecibo").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
        });
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

