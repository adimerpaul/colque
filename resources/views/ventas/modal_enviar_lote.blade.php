<div id="modalEnviar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enviar a lote</h4>
            </div>
            <div class="modal-body">
                <div>

                    <div class="form-group col-sm-12" >
                        {!! Form::label('lot', 'Lote destino :*') !!}
                        {!! Form::select('lote_destino', \App\Patrones\Fachada::listarLotesVentasSinIngenio($venta->letra), null,
                            ['class' => 'form-control', 'required', 'v-on:input' => 'getCalculos($event)', 'v-model' => 'lote_destino']) !!}
                    </div>


                    @if($venta->letra=='E')
                        <div class="form-group col-sm-12" >
                            {!! Form::label('Con_Plomo', '¿Con plomo?:*') !!}
                            {!! Form::select('con_plomo', \App\Patrones\Fachada::getEstadoMolienda(), null,
                                ['class' => 'form-control', 'id' => 'con_pb', 'required',  'v-model' => 'con_plomo', 'onchange' => 'cambiarPb()']) !!}
                        </div>

                        <div id="divPb">
                            <div class="form-group col-sm-12" >
                                {!! Form::label('leyPb', 'Ley Pb %:*') !!}
                                {!! Form::number('ley_pb', null,
                                    ['class' => 'form-control', 'id' => 'enviar_ley_pb', 'required',  'v-model' => 'plomo.ley', 'step'=>'0.001', 'min' => '0']) !!}
                            </div>

                            <div class="form-group col-sm-12" >
                                {!! Form::label('cotiPb', 'Cotización Pb:*') !!}
                                {!! Form::number('cotizacion_pb', null,
                                    ['class' => 'form-control', 'id' => 'enviar_cotizacion_pb', 'required',  'v-model' => 'plomo.cotizacion', 'step'=>'0.001', 'min' => '0']) !!}
                            </div>
                        </div>


                        <div class="form-group col-sm-12" >
                            {!! Form::label('Con_Zinc', '¿Con zinc?:*') !!}
                            {!! Form::select('con_zinc', \App\Patrones\Fachada::getEstadoMolienda(), null,
                                ['class' => 'form-control', 'id' => 'con_zn', 'required',  'v-model' => 'con_zinc', 'onchange' => 'cambiarZn()']) !!}
                        </div>

                        <div id="divZn">
                            <div class="form-group col-sm-12" >
                                {!! Form::label('leyZn', 'Ley Zn %:*') !!}
                                {!! Form::number('ley_zn', null,
                                    ['class' => 'form-control', 'id' => 'enviar_ley_zn', 'required',  'v-model' => 'zinc.ley', 'step'=>'0.001', 'min' => '0']) !!}
                            </div>

                            <div class="form-group col-sm-12" >
                                {!! Form::label('cotiZn', 'Cotización Zn:*') !!}
                                {!! Form::number('cotizacion_zn', null,
                                    ['class' => 'form-control', 'id' => 'enviar_cotizacion_zn', 'required',  'v-model' => 'zinc.cotizacion', 'step'=>'0.001', 'min' => '0']) !!}
                            </div>
                        </div>
                    @endif

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="botonEnviar">
                            Enviar
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>


