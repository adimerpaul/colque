<div id="modalDividir" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dividir monto </h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('idCuenta', null, ['class' => 'form-control', 'name'=>'idCuenta', 'id'=>'idCuenta']) !!}

                    <table class="table table-bordered">

                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Motivo:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="motivo" id="motivo"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Monto total BOB:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="montoOriginal" id="montoOriginal"></td>
                        </tr>

                    </table>

                    <div class="form-group col-sm-12">
                        {!! Form::label('destino', 'Monto para este lote:') !!}

                        {!! Form::number('monto', null,
                            ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01' ]) !!}
                    </div>
                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" >
                            Aceptar
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
