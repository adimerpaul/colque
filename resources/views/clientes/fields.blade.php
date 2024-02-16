<!-- Nit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nit', 'CI: *') !!}
    {!! Form::text('nit', null, ['class' => 'form-control', 'maxlength' => '15', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('celular', 'Celular: *') !!}
    {!! Form::number('celular', null, ['class' => 'form-control', 'maxlength' => '15', 'min' => 0, 'required']) !!}
</div>
<!-- Nombre Field -->
<div class="form-group col-sm-8">
    {!! Form::label('nombre', 'Nombre: *') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100', 'required']) !!}
</div>

@if($id==2)
    <div class="form-group col-sm-4">
        {!! Form::label('esasociado', '¿Es asociado?: *') !!}<br>
        @if(isset($cliente))
            @if(($cliente->es_asociado))
                <input type="checkbox" checked name="es_asociado" id="es_asociado">
            @else
                <input type="checkbox" name="es_asociado" id="es_asociado">
            @endif
        @else
            <input type="checkbox" checked name="es_asociado" id="es_asociado">
        @endif
    </div>
@endif

<div class="col-sm-6">
    <div class="thumbnail">
        @if(isset($cliente) && isset($cliente->firma))
            <img id="img_destino" src="{{ asset('/firmas/'.$cliente->firma) }}" alt="firma" >
        @else
            <img id="img_destino" src="{{ asset('/firmas/blanco.png') }}" alt="firma" style="height: 150px; border: 4px solid #555;">
        @endif

        <div class="caption text-center">
            <div class="foto_boton file btn btn-lg btn-primary">
                <i class="glyphicon glyphicon-paperclip"></i> Cargar firma
                <input id="foto_input" class="foto_input" type="file" name="foto_input" accept="image/*"/>
            </div>
        </div>
    </div>
</div>
{!! Form::hidden('cooperativa_id', isset($cliente) ? null : $id, ['class' => 'form-control']) !!}




<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    @if(isset($cliente))
        <a href="{{ route('clientes.lista', ['id' => $cliente->cooperativa_id]) }}" class="btn btn-default">Cancelar</a>
    @else

        <a href="{{ route('clientes.lista', ['id' => $id]) }}" class="btn btn-default">Cancelar</a>
    @endif
</div>
