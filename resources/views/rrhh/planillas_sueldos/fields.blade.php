<!--Fecha feriado-->
<div class="form-group col-sm-4">
    {!! Form::label('fechaMesAnio', 'Fecha :*') !!}
    {!! Form::month('fechaMesAnio', isset($_GET['fechaMesAnio']) ? $_GET['fechaMesAnio'] : date('Y-m'), ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('eventuales', 'Eventuales') !!}
    {!! Form::checkbox('eventuales', 1, false, ['class' => 'form-check-input']) !!}
</div>
<!-- incremento salarial-->
<div class="form-group col-sm-4">
    {!! Form::label('incremento', 'Incremento salarial (%)') !!}
    {!! Form::number('incremento', 0.00, ['class' => 'form-control', 'min' => '0', 'max' => '100', 'step' => '0.01', 'required']) !!}
</div>

<!-- SUELDO MINIMO NACIONAL-->
<div class="form-group col-sm-4">
    {!! Form::label('sueldominimo', 'Sueldo Mínimo') !!}
    {!! Form::number('sueldominimo', 2362.50, ['class' => 'form-control', 'min' => '2362.50', 'step' => '0.01', 'required']) !!}
</div>

<!-- RC IVA-->
<div class="form-group col-sm-4">
    {!! Form::label('rc_iva', 'RC IVA (%)') !!}
    {!! Form::number('rc_iva', 0.13, ['class' => 'form-control', 'min'=> '0.13', 'max' => '100','step' => '0.01', 'required']) !!}
</div>


<!-- Tipo de cambio-->
<div class="form-group col-sm-4">
    {!! Form::label('cambio', 'Tipo de cambio') !!}
    {!! Form::number('cambio', 6.96, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
</div>






<div class="form-group col-sm-12">
    <br>
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <!-- <a href="{{ route('feriados') }}" class="btn btn-default">Cancelar</a> -->
</div>



