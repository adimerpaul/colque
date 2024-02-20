<div id="modalVenta" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar a Venta</h4>
            </div>
            <div class="modal-body">

                <div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('venta_id', 'Lote Venta:') !!}

                        {!! Form::select('venta_id', \App\Patrones\Fachada::listarLotesVentas($producto->letra), null,
                            ['class' => 'form-control', 'required', 'v-on:input' => 'getCalculos($event)']) !!}
                    </div>
                    <div class="form-group col-sm-12" style="text-align: right"
                    >
                        <button type="submit" class="btn btn-primary" id="botonAgregar"><i
                                class="glyphicon glyphicon-plus"></i>
                            Guardar
                        </button>
                    </div>


                    <div class="form-group col-sm-12 alert alert-success" role="alert" style="font-weight: bold">
                        <h4 class="alert-heading">Cálculos</h4>
                        <span id="pesoNetoSecoSumados"
                              class="form-group col-sm-6">- Peso Neto Seco: </span>
                        <span id="pesoNetoHumedoSumados"
                              class="form-group col-sm-6">- Peso Neto Húmedo:</span>
                        <span id="valorNetoVentaSumados"
                              class="form-group col-sm-6">- Valor Neto Venta: </span>
                        <span
                              class="form-group col-sm-6">&nbsp;</span>
                        @if($producto)
                            @if($producto->id===1 OR $producto->id===2 OR $producto->id===3)
                                <span id="leyAgSumados" class="form-group col-sm-6">- Ag DM: </span>
                                <span id="cotizacionAgSumados"
                                      class="form-group col-sm-6">- Ag Cotización Diaria: </span>
                            @endif

                            @if($producto->id===2 OR $producto->id===3)

                                <span id="leyPbSumados" class="form-group col-sm-6">- Pb %: </span>
                                <span id="cotizacionPbSumados"
                                      class="form-group col-sm-6">- Pb Cotización Diaria: </span>
                            @endif

                            @if($producto->id===4)
                                <span id="leySnSumados" class="form-group col-sm-6">- Sn %: </span>
                                <span id="cotizacionSnSumados"
                                      class="form-group col-sm-6">- Sn Cotización Diaria: </span>
                            @endif

                            @if($producto->id===1 OR $producto->id===3)
                                <span id="leyZnSumados" class="form-group col-sm-6">- Zn %: </span>
                                <span id="cotizacionZnSumados"
                                      class="form-group col-sm-6">- Zn Cotización Diaria: </span>
                            @endif

                            @if($producto->id===5)

                                <span id="leySbSumados" class="form-group col-sm-6">- Sb %: </span>
                                <span id="cotizacionSbSumados"
                                      class="form-group col-sm-6">- Sb Cotización Diaria: </span>
                                <span id="leyAuSumados" class="form-group col-sm-6">- Au G/T: </span>
                                <span id="cotizacionAuSumados"
                                      class="form-group col-sm-6">- Au Cotización Diaria: </span>
                            @endif
                        @endif
                        <p style="font-size: 1px;">.</p>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
