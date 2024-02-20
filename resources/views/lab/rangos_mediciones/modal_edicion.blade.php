<div >
    <div id="modalEdicion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Editar</span> Rango</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'idRango']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('tipo', 'Tipo: *') !!}
                            {!! Form::select('tipo', \App\Patrones\FachadaLab::getMedicionesAmbientes(), null, ['class' => 'form-control', 'required', 'id'=>'tipo']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('minimo', 'Valor Mínimo: *') !!}
                            {!! Form::number('minimo', null, ['class' => 'form-control', 'id'=>'minimo', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('maximo', 'Valor Máximo: *') !!}
                            {!! Form::number('maximo', null, ['class' => 'form-control', 'id'=>'maximo', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>