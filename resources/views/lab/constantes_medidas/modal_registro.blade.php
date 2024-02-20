<div >
    <div id="modalRegistro" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nueva</span> Constante</h4>
                </div>
                <div class="modal-body">

                    <div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('tipo', 'Tipo: *') !!}
                            {!! Form::text('tipo', null, ['class' => 'form-control', 'id'=>'valor', 'maxlength' => '30', 'required','autocomplete' => 'off']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('valor', 'Valor: *') !!}
                            {!! Form::number('valor', null, ['class' => 'form-control', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary" >Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
