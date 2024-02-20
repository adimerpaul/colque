<div class="form-group col-sm-12">
    <hr>
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Cuenta o Email: *') !!}
    {!! Form::email('email', isset($personal) ? $personal->user->email : null, ['class' => 'form-control', 'required', 'minlength' => 5,'maxlength' => 255]) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password: *') !!}
    {!! Form::password('password', ['class' => 'form-control', 'required', 'minlength' => 5,'maxlength' => 255]) !!}
</div>

<!-- Rol Field -->
<div class="form-group col-sm-12">
    {!! Form::label('rol', 'Rol: *') !!}
    {!! Form::select('rol', \App\Patrones\Fachada::getRoles(), isset($personal) ? $personal->user->rol : null, ['class' => 'form-control', 'required']) !!}
</div>
