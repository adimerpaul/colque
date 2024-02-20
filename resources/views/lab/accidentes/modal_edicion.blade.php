<div >
    <div id="modalEdicion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Editar</span> Accidente</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'idAccidente']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('fecha', 'Fecha: *') !!}
                            {!! Form::date('fecha', null, ['class' => 'form-control', 'required', 'id' => 'fecha']) !!}
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::label('hora', 'Hora: *') !!}
                            {!! Form::time('hora', null, ['class' => 'form-control', 'required', 'id' => 'hora']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('tipo', 'Tipo: *') !!}
                            {!! Form::text('tipo', null, ['class' => 'form-control', 'id'=>'tipo', 'maxlength' => '20', 'required','autocomplete' => 'off']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('descripcion', 'Descripción: *') !!}
                            {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'maxlength' => '500', 'required', 'autocomplete' => 'off', 'id' =>'descripcion']) !!}
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