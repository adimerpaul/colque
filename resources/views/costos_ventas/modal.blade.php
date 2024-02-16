<div id="modalOtrosCostos" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar costo</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'id'=>'frmAnticipo', 'v-on:submit.prevent' => 'saveOtroCosto']) !!}

                <div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto :*') !!}
                        {!! Form::number('monto_otro', null, ['class' => 'form-control', 'required', 'id' => 'monto_otro', 'v-model' => 'monto_otro', 'maxlength' => '11','step'=>'0.01', 'min' =>'0']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('descripcion', 'Descripción :*') !!}
                        {!! Form::select('descripcion_otro', \App\ Patrones\Fachada::listarOtrosCostos(), null,
                            ['class' => 'form-control', 'id' => 'descripcion_otro', 'v-model' => 'descripcion_otro', 'required', 'onchange' => 'cambiarNombre()']) !!}
                    </div>

                    <div class="form-group col-sm-12" id="otrosDiv">
                        {!! Form::label('otros', 'Descripción Otros :*') !!}
                        {!! Form::text('otros_otros', null, ['class' => 'form-control',  'id' => 'otros_otros', 'v-model' => 'otros', 'maxlength' =>'70' ]) !!}
                    </div>

                    @if($venta->estado==\App\Patrones\EstadoVenta::EnProceso AND \App\Patrones\Permiso::esComercial())
                        <div class="form-group col-sm-12" style="text-align: right">
                            {!! Form::submit('Agregar', ['class' => 'btn btn-primary', 'id'=>'btnGuardarCosto']) !!}
                        </div>
                    @endif

                </div>
                {!! Form::close() !!}
                @include("costos_ventas.table")

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
