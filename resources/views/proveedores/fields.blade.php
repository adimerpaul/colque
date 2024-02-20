<!-- Nit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nit', 'Nit/Ci: *') !!}
    {!! Form::text('nit', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
</div>

<!-- Empresa Field -->
<div class="form-group col-sm-12">
    {!! Form::label('empresa', 'Empresa: *') !!}
    {!! Form::text('empresa', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('proveedores.index') }}" class="btn btn-default">Cancelar</a>
</div>
