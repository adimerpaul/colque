<div id="modalFactura" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generar Factura</h4>
            </div>
            <div class="modal-body">
                <div>

                    {!! Form::hidden('idVenta', null, ['class' => 'form-control', 'name'=>'idVenta', 'id'=>'idVenta']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('tipo_factura', 'Tipo :*') !!}
                        {!! Form::select('tipo_factura', \App\ Patrones\Fachada::getTiposFacturas(), null, ['class' => 'form-control', 'required',  'onchange' => 'cambiarTipo()']) !!}
                    </div>

                    <div class="form-group col-sm-12" id="divMonto">
                        {!! Form::label('monto_total', 'Monto total:*') !!}
                        {!! Form::number('monto_total', null, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'monto_total']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('moneda_id', 'Moneda :*') !!}
                        {!! Form::select('moneda_id', \App\ Patrones\Fachada::listarParametricasImpuestos(\App\Patrones\TipoImpuestos::Moneda), null, ['class' => 'form-control', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('tipo_cambio', 'Tipo cambio:*') !!}
                        {!! Form::number('tipo_cambio', '6.96', ['class' => 'form-control', 'required', 'maxlength' => '6','step'=>'0.01', 'min' =>'0']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('unidad_id', 'Unidad medida :*') !!}
                        {!! Form::select('unidad_id', \App\ Patrones\Fachada::listarParametricasImpuestos(\App\Patrones\TipoImpuestos::UnidadMedida), null, ['class' => 'form-control', 'required']) !!}
                    </div>

                    <div id="divExportacion">
                        <div class="form-group col-sm-12" id="divPais">
                            {!! Form::label('pais_id', 'Pais :*') !!}
                            {!! Form::select('pais_id', \App\ Patrones\Fachada::listarParametricasImpuestos(\App\Patrones\TipoImpuestos::PaisDestino), null, ['class' => 'form-control', 'id' => 'pais_id']) !!}
                        </div>

                        <div class="form-group col-sm-12" id="divTransito">
                            {!! Form::label('puerto_transito', 'Puerto Tránsito :*') !!}
                            {!! Form::select('puerto_transito', \App\ Patrones\Fachada::getPuertosTransito(), null, ['class' => 'form-control',  'id' => 'puerto_transito']) !!}
                        </div>

                        <div class="form-group col-sm-12" id="divDestino">
                            {!! Form::label('puerto_destino', 'Puerto Destino:*') !!}
                            {!! Form::text('puerto_destino', null, ['class' => 'form-control', 'required', 'maxlength' => '50', 'id' => 'puerto_destino']) !!}
                        </div>

                        <div class="form-group col-sm-12" id="divIncoterm">
                            {!! Form::label('incoterm', 'Incoterm :*') !!}
                            {!! Form::select('incoterm', \App\ Patrones\Fachada::getIncoterms(), null, ['class' => 'form-control',  'id' => 'incoterm']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('neto_humedo', 'Peso Neto Húmedo:*') !!}
                            {!! Form::number('neto_humedo', null, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'neto_humedo']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('humedad', 'Húmedad (%):*') !!}
                            {!! Form::number('humedad', null, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'humedad']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('merma', 'Merma (%):*') !!}
                            {!! Form::number('merma', null, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'merma']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('ley_ag', 'Ley Ag (DM):*') !!}
                            {!! Form::number('ley_ag', 0.00, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'ley_ag']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('ley_pb', 'Ley Pb (%):*') !!}
                            {!! Form::number('ley_pb', 0.00, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'ley_pb']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('ley_zn', 'Ley Zn (%):*') !!}
                            {!! Form::number('ley_zn', 0.00, ['class' => 'form-control', 'required', 'maxlength' => '12','step'=>'0.01', 'min' =>'0', 'id' => 'ley_zn']) !!}
                        </div>
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="asa">
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

@push('scripts')
    <script>

        $(document).ready(function () {
            $("#divExportacion").hide();
            document.getElementById('puerto_destino').removeAttribute('required', '');
            document.getElementById('ley_ag').removeAttribute('required', '');
            document.getElementById('ley_pb').removeAttribute('required', '');
            document.getElementById('ley_zn').removeAttribute('required', '');
            document.getElementById('neto_humedo').removeAttribute('required', '');
            document.getElementById('humedad').removeAttribute('required', '');
            document.getElementById('merma').removeAttribute('required', '');
        });

        function cambiarTipo() {
            const destino = document.getElementById('puerto_destino');
            const leyAg = document.getElementById('ley_ag');
            const leyPb = document.getElementById('ley_pb');
            const leyZn = document.getElementById('ley_zn');
            const netoHumedo = document.getElementById('neto_humedo');
            const humedad = document.getElementById('humedad');
            const merma = document.getElementById('merma');
            const monto = document.getElementById('monto_total');
            if (document.getElementById("tipo_factura").value == 'Compra Venta') {
                $("#divMonto").show();
                $("#divExportacion").hide();
                destino.removeAttribute('required', '');
                leyAg.removeAttribute('required', '');
                leyPb.removeAttribute('required', '');
                leyZn.removeAttribute('required', '');
                netoHumedo.removeAttribute('required', '');
                humedad.removeAttribute('required', '');
                merma.removeAttribute('required', '');
                monto.setAttribute('required', '');
            } else {
                $("#divMonto").hide();
                $("#divExportacion").show();
                destino.setAttribute('required', '');
                leyAg.setAttribute('required', '');
                leyPb.setAttribute('required', '');
                leyZn.setAttribute('required', '');
                netoHumedo.setAttribute('required', '');
                humedad.setAttribute('required', '');
                merma.setAttribute('required', '');
                monto.removeAttribute('required', '');

            }
        }
    </script>
@endpush
