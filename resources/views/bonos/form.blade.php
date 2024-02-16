<div class="row" v-if="formulario.es_escritura">
    {!! Form::open(['route' => 'bonos.store', 'method' => 'POST', 'id'=>'frmBono', 'v-on:submit.prevent' => 'saveBono']) !!}

    <div class="form-group col-sm-4">
        {!! Form::label('monto', 'Monto (BOB): *') !!}
        {!! Form::number('monto', null, ['class' => 'form-control', 'maxlength' => '100', 'required', 'step'=>'0.001', 'min' =>'0',]) !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('clase', 'Causa: *') !!}
        {!! Form::select('clase', \App\Patrones\Fachada::getClasesDevoluciones(), null,
                            ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('clase', 'Tipo: *') !!}
        {!! Form::select('tipo_motivo', \App\Patrones\Fachada::getTiposMotivosDevoluciones(), null,
                            ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group col-sm-8">
        {!! Form::label('motivo', 'Glosa: (en caso de retiro, especificar si es laboratorio o anticipo)*') !!}
        {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '100', 'required']) !!}
    </div>

    {!! Form::hidden('formulario_liquidacion_id', $formularioLiquidacion->id, ['class' => 'form-control', 'maxlength' => '100', 'required']) !!}

    <!-- Submit Field -->
    <div class="form-group col-sm-4" style="margin-top: 25px">
        {!! Form::submit('Guardar devolución', ['class' => 'btn btn-primary', 'id'=>'btnGuardarBono']) !!}
    </div>

    {!! Form::close() !!}
</div>
