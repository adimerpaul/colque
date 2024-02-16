<div id="modalCambioProducto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cambiar Producto</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::open(['route' => 'actualizar-producto-lote', 'id' => 'formularioModalCambio']) !!}

                    {!! Form::hidden('idFormu', null, ['class' => 'form-control', 'name'=>'idFormu', 'id'=>'idFormu']) !!}

                    <div class="form-group col-sm-12" id="bancoDiv">
                        {!! Form::label('producto', 'Producto :*') !!}
                        {!! Form::select('producto_id', \App\Patrones\Fachada::listarComplejos(), null,
                            ['class' => 'form-control', 'id' => 'producto_id', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="botonGuardarCambio">
                            Guardar
                        </button>
                    </div>
                    {!! Form::close() !!}


                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
