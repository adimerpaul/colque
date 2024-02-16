<!-- Tipo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('tipo', 'Tipo: *') !!}
    {!! Form::select('tipo', \App\Patrones\Fachada::tiposPagos(), null, ['class' => 'form-control select2', 'required']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-8">
    {!! Form::label('nombre', 'Nombre:') !!}
    <a data-toggle="modal" data-target="#modalCatalogo" title="Agregar"
       class='btn btn-primary btn-xs pull-right'><i
            class="glyphicon glyphicon-plus"></i></a>
    {!! Form::select('nombre', \App\Patrones\Fachada::listarCatalogosDescuentos(), null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- EnFuncion Field -->
<div class="form-group col-sm-4" id="div_funcion">
    {!! Form::label('en_funcion', 'En Función a: *') !!}

    {!! Form::select('en_funcion', \App\Patrones\Fachada::enFunciones(), null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Unidad Field -->
<div class="form-group col-sm-4">
    {!! Form::label('unidad', 'Unidad: *') !!}
    {!! Form::select('unidad', \App\Patrones\Fachada::unidadesPagos(), null, ['class' => 'form-control select2', 'required', 'id' =>'unidad']) !!}
</div>

<!-- Razon Social Field -->
<div class="form-group col-sm-4">
    {!! Form::label('valor', 'Valor: *') !!}
    {!! Form::number('valor', null, ['class' => 'form-control', 'required', 'step'=>'.01']) !!}
</div>

{!! Form::hidden('cooperativa_id', isset($descuento) ? null : $id, ['class' => 'form-control']) !!}


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    @if(isset($descuento))
        <a href="{{ route('descuentosBonificaciones.lista', ['id' => $descuento->cooperativa_id]) }}" class="btn btn-default">Cancelar</a>
    @else

        <a href="{{ route('descuentosBonificaciones.lista', ['id' => $id]) }}" class="btn btn-default">Cancelar</a>
    @endif
</div>
