<div>
    <div id="modalAnulacion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Anular pago</strong></h4>
                </div>
                <div class="modal-body">

                    {!! Form::hidden('idPago', null, ['class' => 'form-control', 'name'=>'idPago', 'id'=>'idPago']) !!}
                    <table class="table table-bordered">
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Comprobante:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control"
                                       name="comprobante" id="comprobante"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Monto BOB:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control"
                                       name="monto" id="monto"></td>
                        </tr>
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Cliente:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control"
                                       name="cliente" id="cliente"></td>
                        </tr>

                    </table>
                    <div class="form-group col-sm-12">
                        {!! Form::label('motivo_anulacion', 'Motivo: *') !!}
                        {!! Form::text('motivo_anulacion', null, ['class' => 'form-control', 'required', 'maxlength' => '200' ]) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Anular', ['class' => 'btn btn-primary', 'id'=>"botonGuardarAnular"]) !!}
                    </div>


                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
