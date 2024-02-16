@if($formularioLiquidacion->es_escritura)
<div class="row">
    {!! Form::open(['method' => 'POST', 'id'=>'frmAnticipo', 'v-on:submit.prevent' => 'saveAnticipo']) !!}

    <div class="form-group col-sm-6">
        {!! Form::label('fecha', 'Fecha: *') !!}
        {!! Form::date('fecha', null, ['class' => 'form-control', 'v-model' => 'fecha', 'required']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('monto', 'Monto (BOB): *') !!}
        {!! Form::number('monto', null, ['class' => 'form-control', 'maxlength' => '100', 'v-model' => 'monto', 'required', 'step'=>'0.001', 'min' =>'0',]) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('motivo', 'Motivo: *') !!}
        {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '100', 'v-model' => 'motivo']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('cliente_pago', 'Cliente o productor: *') !!}
        {!! Form::select('cliente_pago', \App\Patrones\Fachada::listarCLientes(), $formularioLiquidacion->cliente_id, ['class' => 'form-control select2', 'id' => 'cliente_pago', 'required']) !!}

    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar anticipo', ['class' => 'btn btn-primary', 'id'=>'btnGuardarAnticipo']) !!}
    </div>

    {!! Form::close() !!}
</div>
@endif
