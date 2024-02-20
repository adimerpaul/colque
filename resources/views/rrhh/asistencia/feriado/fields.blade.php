<!--Fecha feriado-->
<div class="form-group col-sm-4">
    {!! Form::label('fecha', 'Fecha :*') !!}
    {!! Form::date('fecha', null, ['class' => 'form-control', 'required']) !!}

</div>

<!-- Descripcion-->
<div class="form-group col-sm-4">
    {!! Form::label('motivo', 'Motivo del Feriado:*') !!}
    {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '300', 'required']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('estado', 'Estado:*') !!}
    {!! Form::select('es_turno', [true => 'Dia medio', false => 'Dia Completo'], $tuModelo->estado ?? false, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-12">
    <br>
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('feriados') }}" class="btn btn-default">Cancelar</a>
</div>



