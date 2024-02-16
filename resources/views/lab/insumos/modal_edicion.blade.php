<div >
    <div id="modalEdicion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Editar</span> Insumo</h4>
                </div>
                <div class="modal-body">

                    <div>
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'idInsumo']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'id'=>'nombre',  'maxlength' => '50', 'required',  'autocomplete' => 'off']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('unidad', 'Unidad: *') !!}
                            {!! Form::select('unidad', [null => 'Seleccione...'] +  \App\Patrones\FachadaLab::getUnidadesInsumos(), null, ['class' => 'form-control', 'required', 'id'=>'unidad']) !!}
                        </div>


                        <div class="form-group col-sm-12">
                            {!! Form::label('cantidad_minima', 'Cantidad Mínima: *') !!}
                            {!! Form::number('cantidad_minima', null, ['class' => 'form-control', 'id'=>'cantidad_minima', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
