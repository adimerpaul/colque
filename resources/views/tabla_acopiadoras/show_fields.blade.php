<!-- Fecha Field -->
<div class="form-group col-sm-4">
    {!! Form::label('fecha', 'Fecha de registro:') !!}
    {{ date('d/m/Y', strtotime($ta->fecha)) }}
</div>

<!-- Gestion Field -->
<div class="form-group col-sm-2">
    {!! Form::label('gestion', 'Gestión:') !!}
    {{ $ta->gestion }}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre', 'Nombre de la tabla:') !!}
    {{ $ta->nombre }}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-2">
    {!! Form::label('margen', 'Margen:') !!}
    {{ $ta->margen }}
</div>
