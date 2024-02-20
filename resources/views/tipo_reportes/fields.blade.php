<!-- Nombre Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre', 'Nombre *:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
</div>

<!-- Descripcion Field -->
<div class="form-group col-sm-8">
    {!! Form::label('descripcion', 'Descripción *:') !!}
    {!! Form::text('descripcion', null, ['class' => 'form-control', 'maxlength' => '300']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('tipoReportes.index') }}" class="btn btn-default">Cancelar</a>
</div>
