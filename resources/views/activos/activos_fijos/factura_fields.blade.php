{!! Form::open(['route' => 'facturas.store']) !!}

    {!! Form::hidden('activo_fijo_id',  $activoFijo->id, ['class' => 'form-control']) !!}

    <!-- Cantidad-->
    <div class="form-group col-sm-4">
            {!! Form::label('cantidad', 'Cantidad : *') !!}
            {!! Form::number('cantidad', null, ['class' => 'form-control', 'min' => '1', 'required','max' => '999', 'maxlength' => '3', 'oninput'=>'maxLengthCheck(this)']) !!}

    </div>

    <!-- factura-->
    <div class="form-group col-sm-4">
            {!! Form::label('factura', 'N° factura:') !!}
            {!! Form::text('factura', null, ['class' => 'form-control', 'maxlength' => '200', 'maxlength' => '300']) !!}
    </div>
   <!-- valor unitario-->
    <div class="form-group col-sm-4">
        {!! Form::label('valor_unitario', 'Valor Unitario BOB:*') !!}
        {!! Form::number('valor_unitario', null, ['class' => 'form-control', 'maxlength' => '8', 'step' => '0.01','min' => '0', 'required', 'oninput'=>'maxLengthCheck(this)']) !!}
    </div>

    <!-- descripcion-->
    <div class="form-group col-sm-12">
            {!! Form::label('descripcion', 'Descripción:*') !!}
            {!! Form::text('descripcion', null, ['class' => 'form-control', 'maxlength' => '200', 'maxlength' => '300']) !!}
    </div>
    <!--guardar y cancelar-->
    <div class="form-group col-sm-12">
        <br>
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('activos-fijos.index') }}" class="btn btn-default">Cancelar</a>
    </div>

{!! Form::close() !!}


<!--tabla de actualizacion del activo-->
<div class="form-group col-sm-12">
    <h3 style="text-align: center">Detalle</h3>

    @include('activos.activos_fijos.factura_table')
</div>



