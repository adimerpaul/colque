<div class="row" v-if="venta.es_escritura">
    {!! Form::open(['method' => 'POST', 'id'=>'frmAnticipo', 'v-on:submit.prevent' => 'saveAnticipo']) !!}


    <div class="form-group col-sm-6">
        {!! Form::label('monto', 'Monto (BOB): *') !!}
        {!! Form::number('monto', null, ['class' => 'form-control', 'maxlength' => '100', 'v-model' => 'monto', 'required', 'step'=>'0.001', 'min' =>'0',]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('motivo', 'Motivo: ') !!}
        {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '100', 'v-model' => 'motivo']) !!}
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar anticipo', ['class' => 'btn btn-primary', 'id'=>'btnGuardarAnticipo']) !!}
    </div>

    {!! Form::close() !!}
</div>
