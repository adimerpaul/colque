<!-- Simbolo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('simbolo', 'Símbolo: *') !!}
    {!! Form::text('simbolo', null, ['class' => 'form-control','minlength' => 2,'maxlength' => 10, 'required']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control','minlength' => 3,'maxlength' => 20, 'required']) !!}
</div>

<!-- Unidad Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unidad_laboratorio', 'Unidad por defecto para laboratorio: *') !!}
    {!! Form::text('unidad_laboratorio', null, ['class' => 'form-control','minlength' => 3,'maxlength' => 10, 'required']) !!}
</div>

<!-- Unidad Field -->
<div class="form-group col-sm-6">
    {!! Form::label('margen_error', 'Margen de error para laboratorio: *') !!}
    {!! Form::number('margen_error', null, ['class' => 'form-control','min' => 0,'max' => 5, 'required']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('materials.index') }}" class="btn btn-default">Cancelar</a>
</div>
