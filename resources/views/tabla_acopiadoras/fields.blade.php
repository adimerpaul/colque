<!-- Nombre Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre', 'Nombre de la tabla: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => 255, 'required', 'autocomplete'=>"off"]) !!}
</div>

<!-- Gestion Field -->
<div class="form-group col-sm-2">
    {!! Form::label('gestion', 'Gestión: *') !!}
    {!! Form::number('gestion', null, ['class' => 'form-control', 'max' => 9999, 'min' => 2000, 'required', 'autocomplete'=>"off"]) !!}
</div>

<!-- Cotizacion Inicial Field -->
<div class="form-group col-sm-2">
    {!! Form::label('cotizacion_inicial', 'Cotización Inicial: *') !!}
    {!! Form::number('cotizacion_inicial', null, ['class' => 'form-control', 'required', 'min' => 0, 'step' => 'any', 'autocomplete'=>"off"]) !!}
</div>

<!-- Cotizacion Final Field -->
<div class="form-group col-sm-2">
    {!! Form::label('cotizacion_final', 'Cotización Final: *') !!}
    {!! Form::number('cotizacion_final', null, ['class' => 'form-control', 'required', 'min' => 0, 'step' => 'any', 'autocomplete'=>"off"]) !!}
</div>

<!-- Margen Field -->
<div class="form-group col-sm-2">
    {!! Form::label('margen', 'Margen: *') !!}
    {!! Form::number('margen', null, ['class' => 'form-control', 'required', 'min' => -10, 'max' => 10, 'step' => 'any', 'autocomplete'=>"off"]) !!}
</div>

<div class="form-group col-sm-12">
    <h4>Leyes y Costos por Tonelada [$us./Ton]</h4>
    <hr>
</div>

@php
    $values = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80]
@endphp

@foreach($values as $f)
    @php
        $ley = "l_" . $f . "_incremental";
        $inicial = "l_" . $f . "_inicial";
    @endphp

    @if($f === 0 || $f === 50)
        <div class="form-group col-sm-2 text-right">
            <div style="margin-top: 30px"><strong>Valor Incrementable *</strong></div>
            <br>
            <div><strong>Valor Inicial *</strong></div>
        </div>
    @endif
    <div class="form-group col-sm-1">
        {!! Form::label($ley, "Ley $f%:") !!}
        {!! Form::number($ley, null, ['class' => 'form-control', 'min' => 0, 'step' => 'any']) !!}

        {!! Form::number($inicial, null, ['class' => 'form-control', 'style'=>'margin-top:5px', 'min' => 0, 'step' => 'any']) !!}
    </div>
@endforeach

<!-- Submit Field -->
<div class="form-group col-sm-12 text-right">
    <hr>
    {!! Form::submit('Generar tabla de cotizaciones', ['class' => 'btn btn-primary', 'onclick' => "return confirm('Seguro que quieres generar o reemplazar la tabla de la gestión actual?')"]) !!}
    <a href="{{ route('tablaAcopiadoras.index') }}" class="btn btn-default">Cancelar</a>
    @if(isset($tablaAcopiadora))
        <a href="{{ route('tablaAcopiadoras.show', [$tablaAcopiadora->id]) }}" class="btn btn-warning">Ver tabla</a>
    @endif
</div>
