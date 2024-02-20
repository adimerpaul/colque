<!-- Nit Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nit', 'Nit / Ci: *') !!}
    {!! Form::number('nit', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'autocomplete' => 'off']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100', 'required', 'autocomplete' => 'off']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('proveedores-lab.index') }}" class="btn btn-default">Cancelar</a>
</div>
