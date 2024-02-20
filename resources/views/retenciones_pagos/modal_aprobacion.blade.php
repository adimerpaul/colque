<div id="modalPago" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registrar aprobación de retenciones</h4>
            </div>
            <div class="modal-body">
                <div>

                    <table class="table table-bordered">

                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Productor:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="cooperativa" id="cooperativa"></td>
                        </tr>

                    </table>

                    {!! Form::hidden('seleccionados', null, ['class' => 'form-control', 'maxlength' => '100', 'id' => 'seleccionados']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto total BOB :*') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'id' => 'monto','step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('glosa', 'Glosa :*') !!}
                        {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '500', 'required', 'id' => 'motivo']) !!}
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
