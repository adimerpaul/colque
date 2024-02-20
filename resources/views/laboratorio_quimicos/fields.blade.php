<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control','minlength' => 2,'maxlength' => 100, 'required']) !!}
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('direccion', 'Dirección: ') !!}
    {!! Form::text('direccion', null, ['class' => 'form-control','maxlength' => 100]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('laboratorioQuimicos.index') }}" class="btn btn-default">Cancelar</a>
</div>
