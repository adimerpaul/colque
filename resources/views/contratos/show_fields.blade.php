<div class="form-group">
    {!! Form::label('producto', 'Producto:') !!}
    <p>{{ $contrato->producto->nombre }}</p>
</div>

<!-- porcentaje_arsenico Field -->
<div class="form-group">
    {!! Form::label('porcentaje_arsenico', 'Porcentaje arsenico:') !!}
    <p>{{ $contrato->porcentaje_arsenico }}</p>
</div>

<!-- porcentaje_antimonio Field -->
<div class="form-group">
    {!! Form::label('porcentaje_antimonio', 'Porcentaje antimonio:') !!}
    <p>{{ $contrato->porcentaje_antimonio }}</p>
</div>

<!-- porcentaje_bismuto Field -->
<div class="form-group">
    {!! Form::label('porcentaje_bismuto', 'Porcentaje bismuto:') !!}
    <p>{{ $contrato->porcentaje_bismuto }}</p>
</div>

<!-- porcentaje_estanio Field -->
<div class="form-group">
    {!! Form::label('porcentaje_estanio', 'Porcentaje estaño:') !!}
    <p>{{ $contrato->porcentaje_estanio }}</p>
</div>

<!-- porcentaje_hierro Field -->
<div class="form-group">
    {!! Form::label('porcentaje_hierro', 'Porcentaje hierro:') !!}
    <p>{{ $contrato->porcentaje_hierro }}</p>
</div>

<!-- porcentaje_silico Field -->
<div class="form-group">
    {!! Form::label('porcentaje_silico', 'Porcentaje silico:') !!}
    <p>{{ $contrato->porcentaje_silico }}</p>
</div>

<!-- porcentaje_zinc Field -->
<div class="form-group">
    {!! Form::label('porcentaje_zinc', 'Porcentaje zinc:') !!}
    <p>{{ $contrato->porcentaje_zinc }}</p>
</div>

<!-- deduccion_elemento Field -->
<div class="form-group">
    @if($contrato->producto_id==1)
        {!! Form::label('deduccion_elemento', 'Deducción zinc:') !!}
    @else
        {!! Form::label('deduccion_elemento', 'Deducción plomo:') !!}
    @endif
    <p>{{ $contrato->deduccion_elemento }}</p>
</div>

<!-- deduccion_plata Field -->
<div class="form-group">
    {!! Form::label('deduccion_plata', 'Deducción plata:') !!}
    <p>{{ $contrato->deduccion_plata }}</p>
</div>

<!-- porcentaje_pagable_elemento Field -->
<div class="form-group">
    @if($contrato->producto_id==1)
        {!! Form::label('porcentaje_pagable_elemento', 'Porcentaje pagable zinc:') !!}
    @else
        {!! Form::label('porcentaje_pagable_elemento', 'Porcentaje pagable plomo:') !!}
        @endif
    <p>{{ $contrato->porcentaje_pagable_elemento }}</p>
</div>

<!-- porcentaje_pagable_plata Field -->
<div class="form-group">
    {!! Form::label('porcentaje_pagable_plata', 'Porcentaje pagable plata:') !!}
    <p>{{ $contrato->porcentaje_pagable_plata }}</p>
</div>

<!-- maquila Field -->
<div class="form-group">
    {!! Form::label('maquila', 'Maquila:') !!}
    <p>{{ $contrato->maquila }}</p>
</div>

<!-- base Field -->
<div class="form-group">
    {!! Form::label('base', 'Base:') !!}
    <p>{{ $contrato->base }}</p>
</div>

<!-- escalador Field -->
<div class="form-group">
    {!! Form::label('escalador', 'Escalador:') !!}
    <p>{{ $contrato->escalador }}</p>
</div>

<!-- deduccion_refinacion_onza Field -->
<div class="form-group">
    {!! Form::label('deduccion_refinacion_onza', 'Deducción Refinación onza:') !!}
    <p>{{ $contrato->deduccion_refinacion_onza }}</p>
</div>

<!-- refinacion_libra_elemento Field -->
<div class="form-group">
    {!! Form::label('refinacion_libra_elemento', 'Refinación libra elemento:') !!}
    <p>{{ $contrato->refinacion_libra_elemento }}</p>
</div>

<!-- laboratorio Field -->
<div class="form-group">
    {!! Form::label('laboratorio', 'Laboratorio:') !!}
    <p>{{ $contrato->laboratorio }}</p>
</div>

<!-- molienda Field -->
<div class="form-group">
    {!! Form::label('molienda', 'Molienda:') !!}
    <p>{{ $contrato->molienda }}</p>
</div>

<!-- manipuleo Field -->
<div class="form-group">
    {!! Form::label('manipuleo', 'Manipuleo:') !!}
    <p>{{ $contrato->manipuleo }}</p>
</div>

<!-- margen_administrativo Field -->
<div class="form-group">
    {!! Form::label('margen_administrativo', 'Margen administrativo:') !!}
    <p>{{ $contrato->margen_administrativo }}</p>
</div>

<!-- transporte Field -->
<div class="form-group">
    {!! Form::label('transporte', 'Transporte a puerto:') !!}
    <p>{{ $contrato->transporte }}</p>
</div>

<!-- roll_back Field -->
<div class="form-group">
    {!! Form::label('roll_back', 'Roll back:') !!}
    <p>{{ $contrato->roll_back }}</p>
</div>
