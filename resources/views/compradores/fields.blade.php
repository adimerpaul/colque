<!-- Nim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nit', 'Nit: *') !!}
    {!! Form::text('nit', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>
<!-- Nim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nro_nim', 'Nro Nim: *') !!}
    {!! Form::text('nro_nim', null, ['class' => 'form-control', 'maxlength' => '11']) !!}
</div>

<!-- Razon Social Field -->
<div class="form-group col-sm-12">
    {!! Form::label('razon_social', 'Razon Social: *') !!}
    {!! Form::text('razon_social', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-12">
    {!! Form::label('direccion', 'Dirección: ') !!}
    {!! Form::text('direccion', null, ['class' => 'form-control', 'maxlength' => '150']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('compradores.index') }}" class="btn btn-default">Cancelar</a>
</div>
