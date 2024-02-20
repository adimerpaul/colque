<div>
    <div id="modalEditarDesc" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Editar descuento </strong></h4>
                </div>
                <div class="modal-body">

                    {!! Form::hidden('idDescuento', null, ['class' => 'form-control', 'name'=>'idDescuento', 'id'=>'idDescuento']) !!}

                    <table class="table table-bordered">

                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Nombre:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="nombre" id="nombre"></td>
                        </tr>

                    </table>

                    <div class="form-group col-sm-12">
                        {!! Form::label('valor', 'Valor: *') !!}
                        {!! Form::number('valor', null, ['class' => 'form-control', 'required', 'step'=>'0.01', 'min' =>'0' ]) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
