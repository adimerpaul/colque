{!! Form::open(['route' => 'bajas-activos.store']) !!}

    {!! Form::hidden('activo_fijo_id',  $activoFijo->id, ['class' => 'form-control']) !!}

    <!-- Cantidad-->
   
    <div class="form-group col-sm-12">
            {!! Form::label('cantidad_actual', 'Activo : *') !!}
            {!! Form::select('detalle_activo_id', \App\Patrones\Fachada::getDetallesActivos($activoFijo->id), null, ['class' => 'form-control', 'placeholder' => 'Seleccione...', 'required']) !!}
    </div>

    <!-- Cantidad a dar de baja-->
    <div class="form-group col-sm-6">
            {!! Form::label('cantidad', 'Cantidad a Retirar : *') !!}
            {!! Form::number('cantidad', null, ['class' => 'form-control', 'min' => '1', 'required','max' => '999', 'maxlength' => '3', 'oninput'=>'maxLengthCheck(this)']) !!}
    </div>
    
    <!-- Motivo-->
    <div class="form-group col-sm-6">
            {!! Form::label('motivo', 'Motivo: *') !!}
            {!! Form::text('motivo', null, ['class' => 'form-control', 'required', 'maxlength' => '500']) !!}
    </div>
    <!--guardar y cancelar-->
    <div class="form-group col-sm-12">
        <br>
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('activos-fijos.index') }}" class="btn btn-default">Cancelar</a>
    </div>

{!! Form::close() !!}


<!--tabla de actualizacion de bajas del activo-->
<div class="form-group col-sm-12">
    <h3 style="text-align: center">Historial de Bajas</h3>

    @include('activos.activos_fijos.bajas_table')
</div>



