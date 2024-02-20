<!-- Placa Field -->
<div class="form-group col-sm-4">
    {!! Form::label('placa', 'Placa *:') !!}
    {!! Form::text('placa', null, ['class' => 'form-control', 'maxlength' => '7']) !!}
</div>

<!-- Marca Field -->
<div class="form-group col-sm-4">
    {!! Form::label('marca', 'Marca *:') !!}
    {!! Form::text('marca', null, ['class' => 'form-control', 'maxlength' => '30']) !!}
</div>

<!-- Color Field -->
<div class="form-group col-sm-4">
    {!! Form::label('color', 'Color Cabina *:') !!}
    {!! Form::text('color', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('vehiculos.index') }}" class="btn btn-default">Cancelar</a>
</div>
