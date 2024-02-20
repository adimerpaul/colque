<div>
    <div id="modalTransferencia" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Transferencia interna</strong></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group col-sm-12">
                        {!! Form::label('tipo', 'Tipo: *') !!}
                        {!! Form::select('tipo_transferencia', [null => 'Seleccione...'] + \App\Patrones\Fachada::getTransferenciasInterna(), null, ['class' => 'form-control', 'required' ]) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'id' => 'numero_recibo_total']) !!}
                    </div>
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id' => 'botonGuardar']) !!}
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
