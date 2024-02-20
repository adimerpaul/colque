<div>
    <div id="modalRetiro" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Retiro de lote</strong></h4>
                </div>
                <div class="modal-body">
                    <div>
                        {!! Form::model($formularioLiquidacion, ['route' => ['anular-formulario', $formularioLiquidacion->id], 'method' => 'patch']) !!}
                        {!! Form::hidden('es_retiro', true) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('motivo_anulacion', 'Motivo: *') !!}
                            {!! Form::select('motivo_anulacion', [null => 'Seleccione...'] + \App\Patrones\Fachada::getMotivosRetiro(), null, ['class' => 'form-control', 'required' ]) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('destino', 'Destino:') !!}

                            {!! Form::select('destino', \App\ Patrones\Fachada::listarLotesActivosParaDevoluciones(), null,
                                ['class' => 'form-control' ]) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
