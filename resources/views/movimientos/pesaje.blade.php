<div class="form-group col-sm-12">
    {!! Form::open(['route' => 'registrar-pago-pesaje', 'id' => 'formularioModalPesaje']) !!}

    <div class="form-group col-sm-12" id="divProveedor">
        {!! Form::label('proveedor_id', 'Proveedor: *') !!}
        <a data-toggle="modal" data-target="#modalProveedor" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
        <a onclick="getProveedores()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i
                class="glyphicon glyphicon-refresh"></i></a>

        {!! Form::select('proveedor_pesaje', [null => 'Seleccione...'] +  \App\Models\Proveedor::whereEsAprobado(true)->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required']) !!}
    </div>


    <div class="form-group col-sm-6" id="divTipoLotePesaje">
        {!! Form::label('tipo', 'Tipo Lote: *') !!}
        {!! Form::select('tipoLotePesaje', \App\Patrones\Fachada::listarTiposLotes(), null, ['class' => 'form-control', 'id'=> "tipoLotePesaje",  'onchange'=> "cambiarTipoLotePesaje()" ]) !!}
    </div>

    <div class="form-group col-sm-6" id="divLoteCompraPesaje">
        {!! Form::label('lote', 'Lote: *') !!}
        {!! Form::select('lotes',  \App\Patrones\Fachada::listarLotesParaLab() , null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'name'=>'lotes[]']) !!}
    </div>

    <div class="form-group col-sm-6" id="divLoteVentaPesaje">
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
            ['class' => 'form-control', 'required', 'id'=>'metodo_pesaje', 'onchange' => 'cambiarMetodoPesaje()']) !!}

    </div>
    <div class="form-group col-sm-6" id="bancoPesajeDiv">
        {!! Form::label('tipo_banco', 'Banco :*') !!}
        {!! Form::select('banco', \App\ Patrones\Fachada::listarBancos(), null,
            ['class' => 'form-control', 'id' => 'banco']) !!}
    </div>
    <div class="form-group col-sm-6" id="nroReciboPesaje">
        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
        {!! Form::text('numero_recibo_pesaje', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'id' => 'numero_recibo_pesaje']) !!}
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
        $("#formularioModalPesaje").on("submit", function () {
            $("#botonGuardarPesaje").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoPesajeDiv").hide();
            $("#nroReciboPesaje").hide();
            $("#divLoteVentaPesaje").hide();
            document.getElementById('numero_recibo_pesaje').removeAttribute('required', '');
        });

        function cambiarTipoLotePesaje() {
            var tipo = document.getElementById('tipoLotePesaje').value;
            if(tipo=='Compra'){
                $("#divLoteCompraPesaje").show();
                $("#divLoteVentaPesaje").hide();
            }
            else{
                $("#divLoteVentaPesaje").show();
                $("#divLoteCompraPesaje").hide();
            }
        }

        function cambiarMetodoPesaje() {
            const input = document.getElementById('numero_recibo_pesaje');
            if (document.getElementById("metodo_pesaje").value == 'Cuenta Bancaria') {
                $("#bancoPesajeDiv").show();
                $("#nroReciboPesaje").show();
                input.setAttribute('required', '');
            } else {
                $("#bancoPesajeDiv").hide();
                $("#nroReciboPesaje").hide();
                input.removeAttribute('required', '');
            }
        }

    </script>
@endpush

