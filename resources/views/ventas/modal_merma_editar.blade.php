<div id="modalMermaEditar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar valor para cálculo de merma</h4>
            </div>
            <div class="modal-body">
                <div>

                    <div class="form-group col-sm-12" >
                        {!! Form::label('monto', 'Porcentaje para merma :*') !!}
                        {!! Form::number('merma_porcentaje_editar', null, ['class' => 'form-control', 'required', 'id' => 'merma_porcentaje_editar', 'v-model' => 'merma_porcentaje_editar', 'maxlength' => '7','step'=>'0.001', 'min' =>'0']) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="asa">
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
