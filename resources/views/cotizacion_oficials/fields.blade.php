<!-- Fecha Field -->
<div class="form-group col-sm-12">
    {!! Form::label('fecha', 'Fecha: *') !!}
    {!! Form::text('fecha', isset($cotizacionOficial) ? date('d/m/Y', strtotime($cotizacionOficial->fecha)) : date("d/m/Y"), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
</div>


<!-- Oficial Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unidad', 'Unidad: *') !!}
    {!! Form::select('unidad', [null => 'Seleccione...'] + \App\Patrones\Fachada::unidadesCotizacion(), isset($cotizacionOficial) ? null : (
    $mineral->ultima_cotizacion_oficial ? $mineral->ultima_cotizacion_oficial->unidad: null), ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Diaria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('monto', 'Cotización oficial: *') !!}
    {!! Form::number('monto', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Diaria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('alicuota_exportacion', 'Alicuota exportación: *') !!}
    {!! Form::number('alicuota_exportacion', isset($cotizacionOficial) ? null : (
    $mineral->ultima_cotizacion_oficial ? $mineral->ultima_cotizacion_oficial->alicuota_exportacion : null), ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Diaria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('alicuota_interna', 'Alicuota ventas internas: *') !!}
    {!! Form::number('alicuota_interna', isset($cotizacionOficial) ? null : (
    $mineral->ultima_cotizacion_oficial ? $mineral->ultima_cotizacion_oficial->alicuota_interna : null), ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>


{!! Form::hidden('mineral_id', isset($cotizacionOficial) ? null : $id, ['class' => 'form-control']) !!}


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    @if(isset($cotizacionOficial))
        <a href="{{ route('cotizacionOficials.index', ['id' => $cotizacionOficial->mineral_id]) }}" class="btn btn-default">Cancelar</a>
    @else
        <a href="{{ route('cotizacionOficials.index', ['id' => $id]) }}" class="btn btn-default">Cancelar</a>
    @endif
</div>
