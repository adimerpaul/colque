<!-- Ci Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ci', 'Ci: *') !!}
    {!! Form::number('ci', null, ['class' => 'form-control', 'required', 'min' => 0]) !!}
</div>

<!-- Ci Add Field -->
<div class="form-group col-sm-3">
    {!! Form::label('ci_add', 'Extensión:') !!}
    {!! Form::text('ci_add', null, ['class' => 'form-control','maxlength' => 2]) !!}
</div>

<!-- Expedido Field -->
<div class="form-group col-sm-3">
    {!! Form::label('expedido', 'Expedido: *') !!}
    {!! Form::select('expedido', \App\Patrones\Fachada::getDepartamentos(), null, ['class' => 'form-control', 'required', 'maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-9">
    {!! Form::label('nombre_completo', 'Nombre Completo: *') !!}
    {!! Form::text('nombre_completo', null, ['class' => 'form-control','minlength' => 3,'maxlength' => 30, 'required']) !!}
</div>

<!-- Celular Field -->
<div class="form-group col-sm-3">
    {!! Form::label('celular', 'Celular:') !!}
    {!! Form::number('celular', null, ['class' => 'form-control','min' => 0]) !!}
</div>

{!! Form::hidden('empresa_id', $empresa->id, ['class' => 'form-control']) !!}

@include('users.fields')

<div class="col-sm-6">
    <div class="thumbnail">
        @if(isset($personal) && isset($personal->firma))
            <img id="img_destino" src="{{ asset('/firmas/'.$personal->firma) }}" alt="firma" >
        @else
            <img id="img_destino" src="{{ asset('/firmas/blanco.png') }}" alt="firma" style="height: 150px">
        @endif

        <div class="caption text-center">
            <div class="foto_boton file btn btn-lg btn-primary">
                <i class="glyphicon glyphicon-paperclip"></i> Cargar firma
                <input id="foto_input" class="foto_input" type="file" name="foto_input" accept="image/*"/>
            </div>
        </div>
    </div>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('empresas.show', $empresa->id) }}" class="btn btn-default"><< Volver</a>
</div>
