<div class="col-sm-12">
    @include('formulario_liquidacions.resumen')
</div>

<div class="form-group col-sm-8">
    {!! Form::label('producto_id', 'Producto: *') !!}

    @if(in_array($formularioLiquidacion->letra, ['A', 'B', 'C']) and \App\Patrones\Permiso::esAdmin())
        <a href="#" class="btn btn-primary btn-xs pull-right"
           data-target="#modalCambioProducto"
           data-txtid="{{$formularioLiquidacion->id}}"
           data-toggle="modal"
        >
            <i class="glyphicon glyphicon-edit"></i>
            Cambiar producto
        </a>

    @endif

    {!! Form::text('producto', $formularioLiquidacion->producto, ['class' => 'form-control', 'required', 'disabled']) !!}
</div>


<div class="form-group col-sm-2">
    {!! Form::label('created_at', 'F. recepción: *') !!}
    {!! Form::text('created_at', isset($formularioLiquidacion->created_at) ? date( 'd/m/Y', strtotime($formularioLiquidacion->created_at)) :  null, ['class' => 'form-control datepicker', 'disabled']) !!}
</div>
<div class="form-group col-sm-2">
    {!! Form::label('fecha_liquidacion', 'F. liquidación: *') !!}
    <a data-toggle="modal" data-target="#modalFechaLiquidacion" title="Información"
       class='btn btn-primary btn-xs pull-right'><i
            class="glyphicon glyphicon-info-sign"></i></a>
    @if(\App\Patrones\Permiso::esAdmin())
        {!! Form::text('fecha_liquidacion', isset($formularioLiquidacion->fecha_liquidacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_liquidacion)) :  date('d/m/Y'), ['class' => 'form-control datepicker', 'id'=>'fecha_liquidacion']) !!}
    @else
        {!! Form::text('fecha_liquidacion', isset($formularioLiquidacion->fecha_liquidacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_liquidacion)) :  date('d/m/Y'), ['class' => 'form-control datepicker', 'id'=>'fecha_liquidacion', 'disabled']) !!}

    @endif
</div>


<div style="{{ $formularioLiquidacion->onlyRead }}">
    <!-- Numero Lote Field -->
    <div class="form-group col-sm-2">
        {!! Form::label('sigla', 'Sigla: *') !!}
        {!! Form::text('sigla', null, ['class' => 'form-control form-control-lg','minlength' => 2,'maxlength' => 5, 'required', 'readonly']) !!}
    </div>

    <div class="form-group col-sm-2">
        {!! Form::label('numero_lote', 'Nro: *') !!}
        {!! Form::number('numero_lote', null, ['class' => 'form-control form-control-lg','min' => 1, 'max' => 99999, 'required', 'readonly']) !!}
    </div>

    <div class="form-group col-sm-2">
        {!! Form::label('letra', 'Código: *') !!}
        {!! Form::text('letra', null, ['class' => 'form-control form-control-lg','minlength' => 1,'maxlength' => 1, 'required', 'readonly']) !!}
    </div>

    <div class="form-group col-sm-2">
        {!! Form::label('anio', 'Año: *') !!}
        {!! Form::number('anio', null, ['class' => 'form-control form-control-lg','min' => 2000, 'max' => 9999, 'required', 'readonly']) !!}
    </div>


    <!-- Fecha Recepcion Field -->
    <div class="form-group col-sm-2">
        {!! Form::label('fecha_cotizacion', 'F. de cotización: *') !!}
        <a data-toggle="modal" data-target="#modalFechaCotizacion" title="Información"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-info-sign"></i></a>
        {!! Form::text('fecha_cotizacion', isset($formularioLiquidacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_cotizacion)) : date('d/m/Y') , ['class' => 'form-control datepicker' , 'required', 'autocomplete' => 'off', 'id' => 'fecha_recepcion', 'disabled']) !!}
    </div>

    <div class="form-group col-sm-2">
        @if($formularioLiquidacion->letra=='E')
            {!! Form::label('cot_prom', '¿Cotización promedio?') !!}
            <br>
            @if($formularioLiquidacion->con_cotizacion_promedio)
                <input type="checkbox" @click="cambiarCheckPromedio()" checked name="con_cotizacion_promedio" id="con_cotizacion_promedio">
            @else
                <input type="checkbox" @click="cambiarCheckPromedio()" name="con_cotizacion_promedio" id="con_cotizacion_promedio">

            @endif
        @else
            {!! Form::label('comision', '¿Comisión externa?') !!}
            <br>
            @if($formularioLiquidacion->comision_externa)
                <input type="checkbox" checked name="comision_externa" id="comision_externa">
            @else
                <input type="checkbox" name="comision_externa" id="comision_externa">
            @endif
        @endif
    </div>
    <div id="divManual">
        <div class="form-group col-sm-8">
        </div>

        <div class="form-group col-sm-2">
            {!! Form::label('cot_manual', '¿Cotización manual?') !!}
            <br>
            @if($formularioLiquidacion->es_cotizacion_manual)
                <input type="checkbox" @click="cambiarCheckManual()" checked name="es_cotizacion_manual" id="es_cotizacion_manual">
            @else
                <input type="checkbox" @click="cambiarCheckManual()" name="es_cotizacion_manual" id="es_cotizacion_manual">

            @endif
        </div>
        <div class="form-group col-sm-2" id="divValorManual">
            {!! Form::label('anio', 'Cot. Manual: *') !!}
            {!! Form::number('cotizacion_manual', null, ['class' => 'form-control form-control-lg', 'step'=>'0.0001']) !!}
        </div>
    </div>
    <!-- Cliente Id Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('cliente_id_read', 'Cliente o productor: *') !!}
        <br>
        <div class="well" style="padding: 2px">
            {!!  $formularioLiquidacion->cliente->info !!}
        </div>
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('valor_por_tonelada', 'Observación:' )!!}

        {!! Form::text('observacion', null, ['class' => 'form-control form-control-lg','minlength' => 1,'maxlength' => 200, 'v-model' =>'formulario.observacion']) !!}
    </div>
    {{--    <!-- Observacion Field -->--}}
    {{--    <div class="form-group col-sm-12">--}}
    {{--        {!! Form::label('observacion', 'Observación:') !!}--}}
    {{--        {!! Form::text('observacion', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}--}}
    {{--    </div>--}}
</div>

@include('formulario_liquidacions.descuento_bonificacion')

