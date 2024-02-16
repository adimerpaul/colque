{!! Form::open(['method' => 'PUT', 'id'=>'frmComprador', 'v-on:submit.prevent' => 'actualizar']) !!}
<div class="col-sm-12 text-center" style="margin-top: -20px">
    <strong>GENERAL</strong>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('empaque', 'Empaque: *') !!}
    {!! Form::select('empaque', \App\Patrones\Fachada::getEmpaques(), null, ['class' => 'form-control form-control-lg', 'v-model' =>'venta.empaque', 'required']) !!}
</div>



<div class="col-sm-12 text-center">
    <hr>
    <strong>COMPRADOR</strong>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('comprador_id', 'Empresa: *') !!}
    <a data-toggle="modal" data-target="#modalComprador" title="Agregar"
       class='btn btn-primary btn-xs pull-right'><i
            class="glyphicon glyphicon-plus"></i></a>
    <a onclick="getCompradores()" href="#" title="Refrescar" style="background-color: #d9dde0"
       class='btn btn-xs pull-right'><i
            class="glyphicon glyphicon-refresh"></i></a>

    {!! Form::select('comprador_id', \App\Models\Comprador::get()->pluck('info', 'id')->toArray(), null
, ['class' => 'form-control', 'required', 'id' =>'comprador_id', 'v-model' =>'venta.comprador_id']) !!}

</div>

<div class="form-group col-sm-6">
    {!! Form::label('lote_comprador', 'Lote: *') !!}
    {!! Form::text('lote_comprador', null, ['class' => 'form-control', 'required', 'maxlength' =>'20', 'v-model' =>'venta.lote_comprador']) !!}
</div>

<div class="col-sm-12 text-center">
    <hr>
    <strong>TRANSPORTE</strong>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('tipo_transporte', 'Tipo: *') !!}
    {!! Form::text('tipo_transporte', null, ['class' => 'form-control', 'maxlength' =>'50', 'v-model' =>'venta.tipo_transporte']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('trayecto', 'Trayecto: *') !!}
    {!! Form::text('trayecto', null, ['class' => 'form-control', 'maxlength' =>'50', 'v-model' =>'venta.trayecto']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('tranca', 'Tranca: *') !!}
    {!! Form::text('tranca', null, ['class' => 'form-control', 'maxlength' =>'50', 'v-model' =>'venta.tranca']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('municipio', 'Municipio: *') !!}
    {!! Form::text('municipio', null, ['class' => 'form-control', 'maxlength' =>'200', 'v-model' =>'venta.municipio']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if($venta->estado==\App\Patrones\EstadoVenta::EnProceso)
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'btnGuardar']) !!}
    @endif
    <hr>
</div>
{!! Form::close() !!}
