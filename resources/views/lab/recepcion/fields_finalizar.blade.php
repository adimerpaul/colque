{!! Form::model($pedido, ['route' => ['recepcion-lab.update', $pedido->id], 'method' => 'patch']) !!}

<div class="form-group col-sm-12">
    {!! Form::label('clienteId', 'Cliente: *') !!}
    <a data-toggle="modal" data-target="#modalClienteRegistro" title="Agregar"
       class='btn btn-primary btn-xs pull-right'><i
            class="glyphicon glyphicon-plus"></i></a>

    {!! Form::select('cliente_id', \App\Models\Lab\Cliente::orderBy('nombre')->get()->pluck('info_cliente', 'id')->toArray(), null
, ['class' => 'form-control', 'required', 'id' =>'cliente_id', 'v-model' =>'pedido.cliente_id']) !!}

</div>
<div class="form-group col-sm-12">
    {!! Form::label('caracteristicas', 'Caracteristicas: *') !!}

    {!! Form::select('caracteristicas', [null => 'Seleccione...'] + \App\Patrones\Fachada::getCaracteristicasLaboratorio(), null,
    ['class' => 'form-control', 'required'])!!}


</div>
@if($pedido->estado==\App\Patrones\EstadoLaboratorio::Recepcionado)
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-primary btn-lg">Guardar</button>

    </div>
@endif

{!! Form::close() !!}
@include('lab.recepcion.table_ensayo')
