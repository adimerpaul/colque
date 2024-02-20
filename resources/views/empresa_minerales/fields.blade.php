{!! Form::open(['route' => 'empresaMinerales.store']) !!}

<!-- Nombre Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::select('mineral_id', \App\Patrones\Fachada::listarMinerales(), null, ['class' => 'form-control select2', 'required']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('diaria', 'Diaria: *') !!}
    {!! Form::select('diaria', \App\Patrones\Fachada::unidadesLeyes(), null, ['class' => 'form-control select2', 'required']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('oficial', 'Oficial: *') !!}
    {!! Form::select('oficial', \App\Patrones\Fachada::unidadesLeyes(), null, ['class' => 'm', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
</div>


{!! Form::close() !!}
