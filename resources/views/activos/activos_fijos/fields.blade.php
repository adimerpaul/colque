<!-- Tipo del activo-->
<div class="form-group col-sm-4">
    {!! Form::label('tipo', 'Tipo :*') !!}
    {!! Form::select('tipo_id', [null => 'Seleccione...'] +\App\Patrones\Fachada::getTiposActivos(), nulL, ['class' => 'form-control', 'required', 'id' =>'tipo_id',
        'onchange'=>'getProximoCodigo()'])!!}
</div>

<!-- codigo del activo-->
<div class="form-group col-sm-2">

    {!! Form::label('codigo', 'Código:') !!}
    {!! Form::text('prefijo', 'CMC-', ['class' => 'form-control', 'maxlength' => '10', 'disabled']) !!}

</div>
<div class="form-group col-sm-2">
    {!! Form::label('codigo', '&nbsp;') !!}
    @if(str_contains(Request::url(),'create'))
        {!! Form::number('codigo', null, ['class' => 'form-control', 'id' => 'codigo', 'min' => '1000', 'max' => '20000', 'maxlength' => '5', 'oninput'=>'maxLengthCheck(this)', 'required']) !!}
    @else
        {!! Form::number('codigo_numero', null, ['class' => 'form-control', 'id' => 'codigo', 'min' => '1000', 'max' => '20000', 'maxlength' => '5', 'oninput'=>'maxLengthCheck(this)', 'required']) !!}

    @endif

</div>
<!-- Responsable del activo-->
<div class="form-group col-sm-4">
    {!! Form::label('personal_id', 'Responsable :*') !!}
    {!! Form::select('personal_id', [null => 'Seleccione...'] + \App\Patrones\Fachada::getPersonal(), nulL, ['class' => 'form-control', 'required']) !!}
</div>
<!-- Descripcion-->
<div class="form-group col-sm-12">
        {!! Form::label('nombre', 'Descripción :*') !!}
        {!! Form::text('descripcion', null, ['class' => 'form-control', 'required', 'maxlength' => '500']) !!}
</div>


<!-- Area de trabajo-->
<div class="form-group col-sm-4">
    {!! Form::label('area_trabajo', 'Área de trabajo :*') !!}
    {!! Form::select('area_trabajo', \App\Patrones\Fachada::getOficinasMovimiento(), nulL, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Estado del activo-->
<div class="form-group col-sm-4">
    {!! Form::label('estado', 'Estado:*') !!}
    {!! Form::select('estado', ['Bueno' => 'Bueno', 'Malo' => 'Malo', 'Regular' => 'Regular', 'Otro' => 'Otro'], null, ['class' => 'form-control', 'required']) !!}
</div>
<!-- Cantidad-->
@if(str_contains(Request::url(),'create'))
<div class="form-group col-sm-4">
    {!! Form::label('cantidad', 'Cantidad :*') !!}
    {!! Form::number('cantidad', null, ['class' => 'form-control', 'min' => '1', 'required','max' => '999', 'maxlength' => '3', 'oninput'=>'maxLengthCheck(this)']) !!}
</div>
@endif
<!-- Unidad de medida-->
<div class="form-group col-sm-4">
    {!! Form::label('unidad_medida', 'Unidad de medida :*') !!}
    {!! Form::select('unidad_medida', \App\Patrones\Fachada::getValorUnitario(), nulL, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Fecha de adquisicion-->
<div class="form-group col-sm-4">
    {!! Form::label('fecha_adquisicion', 'Fecha de adquisición :*') !!}
    {!! Form::date('fecha_adquisicion', isset($activo_fijo) ? $activo_fijo->fecha_adquisicion : date('Y-m-d'), ['class' => 'form-control', 'id' => 'fecha_adquisicion', 'required', 'autocomplete' => 'off', 'required']) !!}

</div>


<!-- valor unitario-->
@if(str_contains(Request::url(),'create'))
<div class="form-group col-sm-4">
    {!! Form::label('valor_unitario', 'Valor Unitario :*') !!}
    {!! Form::number('valor_unitario', null, ['class' => 'form-control', 'maxlength' => '8', 'step' => '0.01','min' => '0', 'required']) !!}
</div>
@endif
<!-- numero de factura-->
@if(str_contains(Request::url(),'create'))
<div class="form-group col-sm-4">
    {!! Form::label('factura', 'N° factura:') !!}
    {!! Form::number('factura', null, ['class' => 'form-control', 'maxlength' => '20','min' => '1', 'oninput'=>'maxLengthCheck(this)']) !!}
</div>
@endif

<!-- Observacion-->
<div class="form-group col-sm-8">
    {!! Form::label('observacion', 'Observación:') !!}
    {!! Form::text('observacion', null, ['class' => 'form-control', 'maxlength' => '200', 'maxlength' => '300']) !!}
</div>


<div class="form-group col-sm-12">
    <br>
    @if(\App\Patrones\Permiso::esActivos())   {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!} @endif
    <a href="{{ route('activos-fijos.index') }}" class="btn btn-default">Cancelar</a>
</div>



