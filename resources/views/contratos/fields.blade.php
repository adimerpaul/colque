<div class="col-sm-12 text-center">
    <strong>REGISTRAR TÉRMINOS DEL CONTRATO</strong>
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-4">
    {!! Form::label('producto', 'Producto *:') !!}
    {!! Form::select('producto_id', [null => 'Seleccione...']  + \App\Models\Producto::where('nombre', 'like', '%Plata')->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required', 'id' =>'producto_id']) !!}
</div>

<div class="col-sm-12 text-center">
    <strong>IMPUREZAS</strong>
</div>
<!-- porcentaje_arsenico Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_arsenico', 'Porcentaje arsenico *:') !!}
    {!! Form::text('porcentaje_arsenico', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_antimonio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_antimonio', 'Porcentaje antimonio *:') !!}
    {!! Form::text('porcentaje_antimonio', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_bismuto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_bismuto', 'Porcentaje bismuto *:') !!}
    {!! Form::text('porcentaje_bismuto', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_estanio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_estanio', 'Porcentaje estaño *:') !!}
    {!! Form::text('porcentaje_estanio', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_hierro Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_hierro', 'Porcentaje hierro *:') !!}
    {!! Form::text('porcentaje_hierro', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_silico Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_silico', 'Porcentaje silico *:') !!}
    {!! Form::text('porcentaje_silico', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_zinc Field -->
<div class="form-group col-sm-4">
    {!! Form::label('porcentaje_zinc', 'Porcentaje zinc *:') !!}
    {!! Form::text('porcentaje_zinc', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>
<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>DEDUCCIONES UNITARIAS</strong>
</div>
<!-- deduccion_elemento Field -->
<div class="form-group col-sm-6">
    @if($contrato->producto_id==1)
        {!! Form::label('deduccion_elemento', 'Deducción zinc *:') !!}
    @else
        {!! Form::label('deduccion_elemento', 'Deducción plomo *:') !!}
    @endif
    {!! Form::text('deduccion_elemento', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- deduccion_plata Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deduccion_plata', 'Deducción plata *:') !!}
    {!! Form::text('deduccion_plata', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>MINERALES PAGABLES</strong>
</div>
<!-- porcentaje_pagable_elemento Field -->
<div class="form-group col-sm-6">
    @if($contrato->producto_id==1)
        {!! Form::label('porcentaje_pagable_elemento', 'Porcentaje pagable zinc *:') !!}
    @else
        {!! Form::label('porcentaje_pagable_elemento', 'Porcentaje pagable plomo *:') !!}
    @endif
    {!! Form::text('porcentaje_pagable_elemento', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- porcentaje_pagable_plata Field -->
<div class="form-group col-sm-6">
    {!! Form::label('porcentaje_pagable_plata', 'Porcentaje pagable plata *:') !!}
    {!! Form::text('porcentaje_pagable_plata', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>MAQUILA + ESCALADOR</strong>
</div>
<!-- maquila Field -->

    <div class="form-group col-sm-4">
        {!! Form::label('maquila', 'Maquila *:') !!}
        {!! Form::text('maquila', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
    </div>

    <!-- base Field -->
    <div class="form-group col-sm-4">
        {!! Form::label('base', 'Base *:') !!}
        {!! Form::text('base', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
    </div>

<!-- escalador Field -->
<div class="form-group col-sm-4">
    {!! Form::label('escalador', 'Escalador *:') !!}
    {!! Form::text('escalador', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>GASTOS DE REFINACIÓN POR ONZA</strong>
</div>
<!-- deduccion_refinacion_onza Field -->
<div class="form-group col-sm-4">
    {!! Form::label('deduccion_refinacion_onza', 'Deducción Refinación onza*:') !!}
    {!! Form::text('deduccion_refinacion_onza', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>GASTOS DE REFINACIÓN POR LIBRA</strong>
</div>
<!-- refinacion_libra_elemento Field -->
<div class="form-group col-sm-4">
    {!! Form::label('refinacion_libra_elemento', 'Refinación libra elemento *:') !!}
    {!! Form::text('refinacion_libra_elemento', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>OTROS GASTOS</strong>
</div>

<!-- laboratorio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('laboratorio_interno', 'Laboratorio Interno *:') !!}
    {!! Form::text('laboratorio_interno', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('laboratorio_exportacion', 'Laboratorio Exportacion *:') !!}
    {!! Form::text('laboratorio_exportacion', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>



<!-- margen_administrativo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('transporte_interno', 'Transporte Interno *:') !!}
    {!! Form::text('transporte_interno', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- transporte Field -->
<div class="form-group col-sm-4">
    {!! Form::label('transporte_exportacion', 'Transporte Exportación *:') !!}
    {!! Form::text('transporte_exportacion', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>



<!-- manipuleo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('publicidad', 'Publicidad *:') !!}
    {!! Form::text('publicidad', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- molienda Field -->
<div class="form-group col-sm-4">
    {!! Form::label('molienda', 'Molienda *:') !!}
    {!! Form::text('molienda', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>



    <!-- roll_back Field -->
    <div class="form-group col-sm-4">
        {!! Form::label('roll_back', 'Roll back *:') !!}
        {!! Form::text('roll_back', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
    </div>


<div class="col-sm-12">
    <hr>
</div>

<div class="col-sm-12 text-center">
    <strong>BONIFICACIONES</strong>
</div>

<!-- laboratorio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('bono_cliente', 'Bono Cliente *:') !!}
    {!! Form::text('bono_cliente', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- molienda Field -->
<div class="form-group col-sm-4">
    {!! Form::label('bono_productor', 'Bono Productor *:') !!}
    {!! Form::text('bono_productor', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- manipuleo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('bono_equipamiento', 'Bono Equipamiento *:') !!}
    {!! Form::text('bono_equipamiento', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- margen_administrativo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('bono_refrigerio', 'Bono Refrigerio *:') !!}
    {!! Form::text('bono_refrigerio', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>


<!-- transporte Field -->
<div class="form-group col-sm-4">
    {!! Form::label('bono_epp', 'Bono Epp *:') !!}
    {!! Form::text('bono_epp', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('contratos.index') }}" class="btn btn-default">Cancelar</a>
</div>
