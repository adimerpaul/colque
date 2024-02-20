@if(!isset($formularioLiquidacion))
    <div class="form-group col-sm-12">
        {!! Form::label('producto_id', 'Producto: *') !!}
        {!! Form::select('producto_id', [null => 'Seleccione...'] +  \App\Models\Producto::orderBy('letra')->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control', 'required' ]) !!}
    </div>
@endif

<!-- Fecha Recepcion Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('fecha_pesaje', 'Fecha de pesaje: *') !!}
        {!! Form::text('fecha_pesaje', isset($formularioLiquidacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_pesaje)) : date('d/m/Y') , ['class' => 'form-control datepicker','id'=>'fecha_pesaje', 'required', 'autocomplete' => 'off']) !!}
    </div>



    <div class="form-group col-sm-6">
        {!! Form::label('presentacion', 'Presentación: *') !!}
        {!! Form::select('presentacion', \App\Patrones\Fachada::getEmpaques(), null, ['class' => 'form-control','id' => 'presentacion', 'v-model' =>'presentacion', 'required', 'onchange'=> "cambiarPresentacion()" ]) !!}
    </div>

<div class="form-group col-sm-6">
    {!! Form::label('tipo_material', 'Tipo: *') !!}
    {!! Form::select('tipo_material',  \App\Patrones\Fachada::getTiposMaterial(), null, ['class' => 'form-control','id' => 'tipo_material', 'v-model' =>'tipo_material', 'required']) !!}
</div>

    <div class="form-group col-sm-6">
        {!! Form::label('boletas', 'Cantidad Boletas de Pesaje: *') !!}
        {!! Form::number('boletas', null, ['class' => 'form-control', 'v-model' =>'boletas', 'required', 'min' => 0, 'id'=>"boletas"]) !!}
    </div>

<div class="form-group col-sm-12">
    {!! Form::label('cliente_id', 'Cliente o productor: *') !!}

    @if(!isset($formularioLiquidacion))
        @if(\App\Patrones\Permiso::esComercial())
            <a data-toggle="modal" data-target="#modalCliente" title="Agregar"
               class='btn btn-primary btn-xs pull-right'><i
                    class="glyphicon glyphicon-plus"></i></a>
        @endif
        <a onclick="getClientes()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i
                class="glyphicon glyphicon-refresh"></i></a>
    @endif
    {!! Form::select('cliente_id', \App\Patrones\Fachada::listarCLientes(), null, ['class' => 'form-control select2']) !!}

</div>
<!-- Chofer Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('chofer_id', 'Conductor: *') !!}
    @if(\App\Patrones\Permiso::esOperaciones())
        <a data-toggle="modal" data-target="#modalChofer" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
    @endif
    <a onclick="getChoferes()" href="#" title="Refrescar" style="background-color: #d9dde0"
       class='btn btn-xs pull-right'><i
            class="glyphicon glyphicon-refresh"></i></a>
    {!! Form::select('chofer_id', [null => 'Seleccione...']  + \App\Models\Chofer::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required', 'id' =>'chofer_id']) !!}
</div>

<!-- Vehiculo Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('vehiculo_id', 'Vehículo: *') !!}
    @if(\App\Patrones\Permiso::esOperaciones())
        <a data-toggle="modal" data-target="#modalVehiculo" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
    @endif
    <a onclick="getVehiculos()" href="#" title="Refrescar" style="background-color: #d9dde0"
       class='btn btn-xs pull-right'><i class="glyphicon glyphicon-refresh"></i></a>
    {!! Form::select('vehiculo_id', [null => 'Seleccione...']  + \App\Models\Vehiculo::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required', 'id' =>'vehiculo_id']) !!}
</div>

<!-- Peso Bruto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('peso_bruto', 'Peso bruto húmedo: (KG) *') !!}
{{--        @if(!isset($formularioLiquidacion))--}}
            {!! Form::number('peso_bruto', null, ['class' => 'form-control', 'v-model'=> 'peso_bruto', 'required', 'min'=>0, 'step'=>"any"]) !!}
{{--        @else--}}
{{--            {!! Form::number('peso_bruto', null, ['class' => 'form-control', 'v-model'=> 'peso_bruto', 'required', 'min'=>0, 'step'=>"any", 'readonly']) !!}--}}
{{--        @endif--}}
</div>

<!-- Tara Field -->
<div class="form-group col-sm-6" id="divTara">
    {!! Form::label('tara', 'Tara: (KG)  *') !!}
    {{--    @if(isset($formularioLiquidacion))--}}
    {!! Form::number('tara', null, ['class' => 'form-control', 'v-model' =>'tara', 'required', 'min' => 0, 'step'=>"any", 'id'=>"tara"]) !!}
    {{--    @else--}}
    {{--        {!! Form::number('tara', null, ['class' => 'form-control', 'v-model' =>'tara', 'required', 'min' => 0, 'step'=>"any", 'readonly']) !!}--}}
    {{--    @endif--}}
</div>

<div class="form-group col-sm-6" id="divSacos">
    {!! Form::label('sacos', 'Cantidad Sacos: *') !!}
    {!! Form::number('sacos', null, ['class' => 'form-control', 'v-model' =>'sacos', 'required', 'min' => 0, 'id'=>"sacos"]) !!}
</div>
            <div class="form-group col-sm-6">
                {!! Form::label('ubicacion', 'Ubicación: *') !!}
                {!! Form::select('ubicacion', \App\Patrones\Fachada::getUbicaciones(), null, ['class' => 'form-control', 'required']) !!}

            </div>
            @if(!isset($formularioLiquidacion))
                <div class="form-group col-sm-6">
                    {!! Form::label('moler', '¿Por moler?: *') !!}
                    {!! Form::select('en_molienda', \App\Patrones\Fachada::getEstadoMolienda(), null, ['class' => 'form-control', 'required']) !!}

                </div>
            @endif
<div class="form-group col-sm-12">
    <strong>Peso neto húmedo (KG):</strong>
</div>
<div class="col-sm-12 text-center">
    <h2>@{{ redondear(peso_neto) }}</h2>
    <div class="alert alert-danger text-left" v-if="!es_peso_valido">
        <strong>Error!</strong> El Peso bruto húmedo no puede ser menor a la Tara
    </div>
</div>

@if(isset($formularioLiquidacion) && $formularioLiquidacion->esEscritura && \App\Patrones\Permiso::esComercial())
    <!-- Submit Field -->
    <div class="form-group col-sm-12" v-if="es_peso_valido">
        {!! Form::submit('Guardar pesaje', ['class' => 'btn btn-primary', 'name'=>'btnGuardarPesaje']) !!}
    </div>
@endif

@push('scripts')
    <script>
        $(document).ready(function () {
            cambiarPresentacion();
        });

        function getVehiculos() {
            let url = "{{ url('get_vehiculos') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="vehiculo_id"]').empty();
                    $('select[name="vehiculo_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="vehiculo_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function getChoferes() {
            let url = "{{ url('get_choferes') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="chofer_id"]').empty();
                    $('select[name="chofer_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="chofer_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function getClientes() {
            let url = "{{ url('get_clientes') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="cliente_id"]').empty();
                    $('select[name="cliente_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="cliente_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function cambiarPresentacion() {
            let presentacion = document.getElementById('presentacion').value;
            if (presentacion == 'Ensacado') {
                $("#divTara").hide();
                $("#divSacos").show();
                document.getElementById('tara').removeAttribute('required', '');
                document.getElementById('sacos').setAttribute('required', '');

            } else {
                $("#divTara").show();
                $("#divSacos").hide();
                document.getElementById('sacos').removeAttribute('required', '');
                document.getElementById('tara').setAttribute('required', '');

            }
        }
    </script>
@endpush

