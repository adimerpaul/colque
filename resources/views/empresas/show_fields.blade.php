<!-- Identificacion tributaria Field -->
<div class="form-group">
    {!! Form::label('identificacion_tributaria', 'Identificación tributaria:') !!}
    <p>{{ $empresa->identificacion_tributaria }}</p>
</div>

<!-- Razon Social Field -->
<div class="form-group">
    {!! Form::label('razon_social', 'Razón Social o Denominación:') !!}
    <p>{{ $empresa->razon_social }}</p>
</div>

<!-- Direccion Field -->
<div class="form-group">
    {!! Form::label('direccion', 'Direcciónn:') !!}
    <p>{{ $empresa->direccion }}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $empresa->email }}</p>
</div>

<!-- Telefono Field -->
<div class="form-group">
    {!! Form::label('telefono', 'Teléfono:') !!}
    <p>{{ $empresa->telefono }}</p>
</div>

<!-- Celular Field -->
<div class="form-group">
    {!! Form::label('celular', 'Celular:') !!}
    <p>{{ $empresa->celular }}</p>
</div>

<!-- Alta Field -->
<div class="form-group">
    {!! Form::label('alta', 'Alta:') !!}
    <br>
    @if($empresa->alta)
        <span class="label label-success">Alta</span>
    @else
        <span class="label label-danger">Baja</span>
    @endif
</div>

<!-- Logo Field -->
<div class="form-group">
    {!! Form::label('logo', 'Logotipo:') !!}
    <p>{{ $empresa->logo }}</p>
</div>

<!-- Cantidad Usuario Field -->
<div class="form-group">
    {!! Form::label('cantidad_usuario', 'Cantidad Usuarios permitidos:') !!}
    <p>{{ $empresa->cantidad_usuario }}</p>
</div>

