<div id="modalPago" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registrar pago de retenciones</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('idRetencion', null, ['class' => 'form-control', 'name'=>'idRetencion', 'id'=>'idRetencion']) !!}

                    <table class="table table-bordered">
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Monto BOB:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="monto" id="monto"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Productor:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="cooperativa" id="cooperativa"></td>
                        </tr>

                    </table>
                    <div class="form-group col-sm-12">
                        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
                        {!! Form::select('metodo', \App\Patrones\Fachada::listarTiposPagos(), null,
                            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodo()']) !!}

                    </div>
                    <div class="form-group col-sm-12" id="bancoDiv">
                        {!! Form::label('tipo_banco', 'Banco :*') !!}
                        {!! Form::select('banco', \App\Patrones\Fachada::listarBancos(), null,
                            ['class' => 'form-control', 'id' => 'banco']) !!}

                    </div>
                    <div class="form-group col-sm-12" id="nroRecibo">
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::number('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'id' => 'numero_recibo']) !!}
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
