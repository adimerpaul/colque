<div class="form-group col-sm-12">
    {!! Form::open(['route' => 'movimientos.store', 'id' => 'formularioModalParcial']) !!}

    <div class="form-group col-sm-6">
        {!! Form::label('tipo', 'Tipo: *') !!}
        {!! Form::select('tipo', [null => 'Seleccione...'] +  \App\Patrones\Fachada::listarTiposMovimientos(), null, ['class' => 'form-control', 'required' ]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('proveedor_id', 'Proveedor: *') !!}
        <a data-toggle="modal" data-target="#modalProveedor" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
        <a onclick="getProveedores()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i
                class="glyphicon glyphicon-refresh"></i></a>
        {!! Form::select('proveedor_id', [null => 'Seleccione...'] +  \App\Models\Proveedor::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('monto', 'Monto Parcial BOB: *') !!}
        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('monto', 'Monto Total BOB: *') !!}
        {!! Form::number('total', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('motivo', 'Motivo: *') !!}
        {!! Form::text('motivo', null, ['class' => 'form-control','required']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('glosa', 'Glosa: *') !!}
        {!! Form::text('glosa', null, ['class' => 'form-control','required']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('metodo_pago', 'Método de pago :*') !!}
        {!! Form::select('metodo', \App\ Patrones\Fachada::listarTiposPagos(), null,
            ['class' => 'form-control', 'required', 'id'=>'metodo', 'onchange' => 'cambiarMetodoParcial()']) !!}

    </div>
    <div class="form-group col-sm-6" id="bancoDiv">
        {!! Form::label('tipo_banco', 'Banco :*') !!}
        {!! Form::select('banco', \App\ Patrones\Fachada::listarBancos(), null,
            ['class' => 'form-control', 'id' => 'banco']) !!}
    </div>
    <div class="form-group col-sm-6" id="nroReciboParcial">
        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'id' => 'numero_recibo_parcial']) !!}
    </div>

    {!! Form::hidden('tipo_pago', 'parcial', ['class' => 'form-control','required']) !!}

<!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardarParcial']) !!}
    </div>

    {!! Form::close() !!}
</div>
@push('scripts')
    <script>
        $("#formularioModalParcial").on("submit", function() {
            $("#botonGuardarParcial").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroReciboParcial").hide();
            document.getElementById('numero_recibo_parcial').removeAttribute('required', '');
        });
        function cambiarMetodoParcial() {
            const input = document.getElementById('numero_recibo_parcial');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#bancoDiv").show();
                $("#nroReciboParcial").show();
                input.setAttribute('required', '');
            } else {
                $("#bancoDiv").hide();
                $("#nroReciboParcial").hide();
                input.removeAttribute('required', '');
            }
        }
    </script>
@endpush

