<!-- Fecha Field -->
<div class="form-group col-sm-12">
    {!! Form::label('actual', 'Password actual: *') !!}
    {!! Form::password('clave', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('actual', 'Password nuevo: * (Mínimo 8 caracteres (1 mayúscula, 1 minúscula y 1 nùmero))') !!}
    {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('password_confirmation', 'Repita su password: *') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-6" style="margin-top: 25px">
    {!! Form::submit('Cambiar', ['class' => 'btn btn-primary']) !!}

</div>


