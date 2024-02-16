<!-- Letra Field -->
<div class="form-group col-sm-4">
    {!! Form::label('letra', 'Código: *') !!}
    {!! Form::text('letra', null, ['class' => 'form-control','minlength' => 1,'maxlength' => 2, 'required']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-8">
    {!! Form::label('nombre', 'Nombre del producto: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control','minlength' => 3,'maxlength' => 15, 'required']) !!}
</div>

<!-- Tratamiento Field -->
<div class="form-group col-sm-4">
    {!! Form::label('costo_tratamiento', 'Costo de tratamiento: *') !!}
    {!! Form::text('costo_tratamiento', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- pesaje Field -->
<div class="form-group col-sm-4">
    {!! Form::label('costo_pesaje', 'Costo de pesaje: *') !!}
    {!! Form::text('costo_pesaje', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- comision Field -->
<div class="form-group col-sm-4">
    {!! Form::label('costo_comision', 'Costo de comisión: *') !!}
    {!! Form::text('costo_comision', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('productos.index') }}" class="btn btn-default">Cancelar</a>
</div>
