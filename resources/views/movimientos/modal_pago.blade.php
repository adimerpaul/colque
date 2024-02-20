<div id="modalMovimiento" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registrar movimiento</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('idMovimiento', null, ['class' => 'form-control', 'name'=>'idMovimiento', 'id'=>'idMovimiento']) !!}

                    <table class="table table-bordered">
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Monto BOB:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="monto" id="monto"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Tipo:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="tipo" id="tipo"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Cliente:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="proveedor" id="proveedor"></td>
                        </tr>

                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Empresa:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="empresa" id="empresa"></td>
                        </tr>

                    </table>
                    <div class="form-group col-sm-12">
                        {!! Form::label('factura', 'Factura:') !!}
                        {!! Form::text('factura',  null,
                            ['class' => 'form-control',  'id' => 'factura', 'maxlength' => '80']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
                        {!! Form::select('metodo', \App\ Patrones\Fachada::listarTiposPagos(), null,
                            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodo()']) !!}
                    </div>
                    <div class="form-group col-sm-12" id="bancoDiv">
                        {!! Form::label('tipo_banco', 'Banco :*') !!}
                        {!! Form::select('banco', \App\ Patrones\Fachada::listarBancos(), null,
                            ['class' => 'form-control', 'id' => 'banco']) !!}
                    </div>
                    <div class="form-group col-sm-12" id="nroRecibo">
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '80', 'required', 'id' => 'numero_recibo']) !!}
                    </div>
                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="botonGuardar">
                            Guardar
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
