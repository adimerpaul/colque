<div >
    <div id="modalCantidadActual" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Cantidad actual de</span> Insumo</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::hidden('insumo_id', null, ['class' => 'form-control', 'id'=>'idInsumo']) !!}
                        {!! Form::hidden('tipo', 'Egreso', ['class' => 'form-control']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'id'=>'nombre',  'maxlength' => '50', 'required',  'disabled']) !!}
                        </div>


                        <div class="form-group col-sm-12">
                            {!! Form::label('Fecha', 'Fecha:') !!}
                            {!! Form::date('fecha', date('Y-m-d'), ['class' => 'form-control', 'id' => 'fecha', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('cantidad', 'Cantidad Actual: *') !!}
                            {!! Form::number('cantidad', null, ['class' => 'form-control', 'id'=>'cantidad', 'maxlength' => '7', 'required', 'step'=>'0.01', 'min' => '0.01', 'autocomplete' => 'off']) !!}
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
