<!-- Tipo del activo-->
<div class="form-group col-sm-12">
    {!! Form::label('nombre', 'Nombre :*') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'oninput' => 'this.value = this.value.toUpperCase()']) !!}

</div>


<div class="form-group col-sm-12">
    <br>
     {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
     <a href="{{ route('tipos-activos.index') }}" class="btn btn-default">Cancelar</a>
</div>



