<div id="modalVenta" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Finalizar venta</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('cambiar', null, ['class' => 'form-control','id' => 'cambiar', 'maxlength' => '20']) !!}
                    {!! Form::hidden('utilidad', null, ['class' => 'form-control','id' => 'utilidad']) !!}
                    {!! Form::hidden('margen', null, ['class' => 'form-control', 'required', 'id'=>'margen']) !!}
{{--                    {!! Form::text('totalCobrar', null, ['class' => 'form-control', 'required', 'id'=>'totalCobrar']) !!}--}}

                    <div class="form-group col-sm-12" >
                        {!! Form::label('monto', 'Monto a cobrar BOB:*') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control','id' => 'montos', 'maxlength' => '20', 'required', 'step'=>'0.01', 'min' =>'0']) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="botonFinalizarModal">
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
