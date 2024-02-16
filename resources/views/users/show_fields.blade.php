{{--@include('personals.show')--}}

<!-- Email Field -->
<div class="form-group">
    <hr>
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Rol Field -->
<div class="form-group">
    {!! Form::label('rol', 'Rol:') !!}
    <p>{{ $user->rol }}</p>
</div>

<div class="form-group">
    {!! Form::label('nombre', 'Nombre Completo:') !!}
    <p>{{ $user->personal->nombre_completo }}</p>
</div>

<div class="form-group">
    {!! Form::label('ci', 'Documento de identidad:') !!}
    <p>{{ $user->personal->ci .' '. $user->personal->expedido}}</p>
</div>

<div class="form-group">
    {!! Form::label('celular', 'Celular:') !!}
    <p>{{ $user->personal->celular }}</p>
</div>

<!-- Ultimo Cambio Password Field -->
<div class="form-group">
    {!! Form::label('ultimo_cambio_password', 'Último Cambio de Password:') !!}
    <p>{{ date('d/m/Y', strtotime($user->ultimo_cambio_password))}}</p>
</div>
