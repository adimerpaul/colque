
<!-- Nombre Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
</div>
<!-- Licencia Field -->
<div class="form-group col-sm-6">
    {!! Form::label('licencia', 'Licencia: *') !!}
    {!! Form::text('licencia', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>
<!-- Celular Field -->
<div class="form-group col-sm-6">
    {!! Form::label('celular', 'Celular: *') !!}
    {!! Form::number('celular', null, ['class' => 'form-control', 'maxlength' => '8']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('choferes.index') }}" class="btn btn-default">Cancelar</a>
</div>
