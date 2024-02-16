<div id="modalFactura" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registrar factura</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('idMovimiento', null, ['class' => 'form-control', 'name'=>'idMovimiento', 'id'=>'idMovimiento']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('factura', 'Factura:') !!}
                        {!! Form::text('factura',  null,
                            ['class' => 'form-control', 'required', 'id' => 'factura'
                             , 'maxlength' => '80']) !!}
                    </div>
                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" >
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
