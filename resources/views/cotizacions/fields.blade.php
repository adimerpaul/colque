<!-- Fecha Field -->
<div class="form-group col-sm-2">
    {!! Form::label('fecha', 'Fecha: *') !!}
    {!! Form::text('fecha', isset($cotizacion) ? date('d/m/Y', strtotime($cotizacion->fecha)) : date("d/m/Y"), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
</div>

<!-- Diaria Field -->
<div class="form-group col-sm-2">
    {!! Form::label('monto', 'Cotización: *') !!}
    {!! Form::number('monto', null, ['class' => 'form-control', 'step'=>'.001', 'required']) !!}
</div>

<!-- Oficial Field -->
<div class="form-group col-sm-2">
    {!! Form::label('unidad', 'Unidad: *') !!}
    @if(isset($id))
        @if($id==1 || $id==7)
            {!! Form::text('unidad', \App\Patrones\UnidadCotizacion::OT, ['class' => 'form-control', 'step'=>'.01', 'required', 'readonly']) !!}
        @else
            {!! Form::text('unidad', \App\Patrones\UnidadCotizacion::TM, ['class' => 'form-control', 'step'=>'.01', 'required', 'readonly']) !!}
        @endif
    @elseif(isset($cotizacion->mineral_id))
        @if($cotizacion->mineral_id==1 || $cotizacion->mineral_id==7)
            {!! Form::text('unidad', \App\Patrones\UnidadCotizacion::OT, ['class' => 'form-control', 'step'=>'.01', 'required', 'readonly']) !!}
        @else
            {!! Form::text('unidad', \App\Patrones\UnidadCotizacion::TM, ['class' => 'form-control', 'step'=>'.01', 'required', 'readonly']) !!}
        @endif
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-6" style="margin-top: 25px">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    @if(isset($cotizacion))
        <a href="{{ route('cotizacions.lista', $cotizacion->mineral_id) }}" class="btn btn-default">Cancelar</a>
    @else
        <a href="{{ route('cotizacions.lista', $id) }}" class="btn btn-default">Cancelar</a>
    @endif
</div>

{!! Form::hidden('mineral_id', isset($cotizacion) ? null : $id, ['class' => 'form-control']) !!}

