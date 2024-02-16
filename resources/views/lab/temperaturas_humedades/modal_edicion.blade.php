<div >
    <div id="modalEdicion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Editar</span> Temperatura / Humedad</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'idTemperatura']) !!}

                        {!! Form::hidden('ambiente', $ambiente) !!}


                        <div class="form-group col-sm-12">
                            {!! Form::label('valor', 'Valor: *') !!}
                            {!! Form::number('valor', null, ['class' => 'form-control', 'id'=>'valor', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary" @click="saveCalibracion">Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
