<div>
    <div id="modalAnulacion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Anular formulario</strong></h4>
                </div>
                <div class="modal-body">
                    {!! Form::model($formularioLiquidacion, ['route' => ['anular-formulario', $formularioLiquidacion->id], 'method' => 'patch']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('motivo_anulacion', 'Motivo: *') !!}
                        {!! Form::select('motivo_anulacion', [null => 'Seleccione...'] + \App\Patrones\Fachada::getMotivosAnulacion(), null, ['class' => 'form-control', 'required' ]) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Anular', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
