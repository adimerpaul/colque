
<!-- Dolar Compra Field -->
<div class="form-group col-sm-4">
    {!! Form::label('dolar_compra', 'Comercial: *') !!}
    {!! Form::number('dolar_compra', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Dolar Venta Field -->
<div class="form-group col-sm-4">
    {!! Form::label('dolar_venta', 'Oficial: *') !!}
    {!! Form::number('dolar_venta', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('tipoCambios.index') }}" class="btn btn-default">Volver</a>
</div>
