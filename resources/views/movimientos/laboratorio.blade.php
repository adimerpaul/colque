<div class="form-group col-sm-12">
    {!! Form::open(['route' => 'registrar-pago-laboratorio', 'id' => 'formularioModalLab']) !!}

    <div class="form-group col-sm-6" id="divProveedor">
        {!! Form::label('proveedor_id', 'Proveedor: *') !!}
        <a data-toggle="modal" data-target="#modalProveedor" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
        <a onclick="getProveedores()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i
                class="glyphicon glyphicon-refresh"></i></a>

        {!! Form::select('laboratorio_id', [null => 'Seleccione...'] +  \App\Models\Proveedor::whereEsAprobado(true)->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required', 'onchange'=> "getPrecios(this.value)"]) !!}
    </div>

    <div class="form-group col-sm-6" id="divTipoLoteLab">
        {!! Form::label('tipo_analisis', 'Tipo Análisis: *') !!}
        {!! Form::select('tipo_analisis', \App\Patrones\Fachada::getTiposPagosLaboratorios(), null, ['class' => 'form-control', 'id'=> "tipo_analisis",  'onchange'=> "cambiarTipoAnalisis()" ]) !!}
    </div>

    <div class="form-group col-sm-6" id="divTipoLoteLab">
        {!! Form::label('tipo', 'Tipo Lote: *') !!}
        {!! Form::select('tipoLoteLab', \App\Patrones\Fachada::listarTiposLotes(), null, ['class' => 'form-control', 'id'=> "tipoLoteLab",  'onchange'=> "cambiarTipoLoteLab()" ]) !!}
    </div>

    <div class="form-group col-sm-6" id="divLoteCompraLab">
        {!! Form::label('lote', 'Lote: *') !!}
        {!! Form::select('lotes',  \App\Patrones\Fachada::listarLotesParaLab() , null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'name'=>'lotes[]']) !!}
    </div>

    <div class="form-group col-sm-6" id="divLoteVentaLab">
        {!! Form::label('lote', 'Lote: *') !!}
        {!! Form::select('lotesVentas',  \App\Patrones\Fachada::listarLotesParaLabVenta() , null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'name'=>'lotesVentas[]']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('factura', 'Factura:') !!}
        {!! Form::text('factura',  null, ['class' => 'form-control',  'id' => 'factura', 'maxlength' => '80']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
        {!! Form::select('metodo', \App\ Patrones\Fachada::listarTiposPagos(), null,
            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodoLab()']) !!}

    </div>
    <div class="form-group col-sm-6" id="bancoDiv">
        {!! Form::label('tipo_banco', 'Banco :*') !!}
        {!! Form::select('banco', \App\ Patrones\Fachada::listarBancos(), null,
            ['class' => 'form-control', 'id' => 'banco']) !!}
    </div>
    <div class="form-group col-sm-6" id="nroReciboLab">
        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'id' => 'numero_recibo_parcial']) !!}
    </div>

    <div class="form-group col-sm-12" id="divObservacion">
        {!! Form::label('observacion', 'Observación:') !!}
        {!! Form::text('observacion', null, ['class' => 'form-control', 'maxlength' => '200']) !!}
    </div>


    <div class="form-group col-sm-12" style="background-color: #ECEFF1">
        <br>
        <div class="form-group col-sm-3" >
            {!! Form::label('monto', 'Monto Unitario (A | Zinc Plata): *') !!}
            {!! Form::number('montoA', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoA']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (B | Plomo Plata): *') !!}
            {!! Form::number('montoB', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoB']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (C | Complejo): *') !!}
            {!! Form::number('montoC', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoC']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (D | Estaño): *') !!}
            {!! Form::number('montoD', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoD']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (E | Plata): *') !!}
            {!! Form::number('montoE', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoE']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (F | Antimonio): *') !!}
            {!! Form::number('montoF', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoF']) !!}
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('monto', 'Monto Unitario (G | Cobre): *') !!}
            {!! Form::number('montoG', 0.00, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01', 'id'=>'montoG']) !!}
        </div>
    </div>

    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
    </div>

    {!! Form::close() !!}
</div>

@push('scripts')
    <script>
        $("#formularioModalLab").on("submit", function () {
            $("#botonGuardarLab").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroReciboLab").hide();
            $("#divLoteVentaLab").hide();
            $("#divObservacion").hide();
            document.getElementById('numero_recibo_parcial').removeAttribute('required', '');
        });

        function cambiarTipoAnalisis() {
            var tipo = document.getElementById('tipo_analisis').value;
            if(tipo=='Normal'){
                $("#divObservacion").hide();
            }
            else{
                $("#divObservacion").show();
            }
        }

        function cambiarTipoLoteLab() {
            var tipo = document.getElementById('tipoLoteLab').value;
            if(tipo=='Compra'){
                $("#divLoteCompraLab").show();
                $("#divLoteVentaLab").hide();
            }
            else{
                $("#divLoteVentaLab").show();
                $("#divLoteCompraLab").hide();
            }
        }

        function cambiarMetodoLab() {
            const input = document.getElementById('numero_recibo_parcial');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#bancoDiv").show();
                $("#nroReciboLab").show();
                input.setAttribute('required', '');
            } else {
                $("#bancoDiv").hide();
                $("#nroReciboLab").hide();
                input.removeAttribute('required', '');
            }
        }

        function getPrecios(laboratorioId) {
            let url = "{{ url('get-precios-laboratorios') }}" + "/" + laboratorioId;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    document.getElementById('montoA').value = data[0].monto;
                    document.getElementById('montoB').value = data[1].monto;
                    document.getElementById('montoC').value = data[2].monto;
                    document.getElementById('montoD').value = data[3].monto;
                    document.getElementById('montoE').value = data[5].monto;
                    document.getElementById('montoF').value = data[4].monto;
                    document.getElementById('montoG').value = data[6].monto;
                    //console.log(data[0].id);
                },
            });
        }
    </script>
@endpush

