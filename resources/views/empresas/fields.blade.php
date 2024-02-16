<div class="col-sm-6">
    <!-- Registro Nacional Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('identificacion_tributaria', 'Identificación tributaria:') !!}
        {!! Form::number('identificacion_tributaria', null, ['class' => 'form-control','maxlength' => 15,'maxlength' => 15]) !!}
    </div>

    <!-- Razon Social Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('razon_social', 'Razon Social o Denominación:') !!}
        {!! Form::text('razon_social', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
    </div>

    <!-- Direccion Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('direccion', 'Direccion:') !!}
        {!! Form::text('direccion', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
    </div>

    <!-- Telefono Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('telefono', 'Telefono:') !!}
        {!! Form::text('telefono', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
    </div>

    <!-- Celular Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('celular', 'Celular:') !!}
        {!! Form::text('celular', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
    </div>

    <!-- Cantidad Usuario Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('cantidad_usuario', 'Cantidad Usuario:') !!}
        {!! Form::number('cantidad_usuario', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Alta Field -->
    <div class="form-group col-sm-6 text-center" style="margin-top: 25px">
        {!! Form::label('alta', 'Alta:') !!}
        <label>
            {!! Form::hidden('alta', 0) !!}
            {!! Form::checkbox('alta', '1', null) !!}
        </label>
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('empresas.index') }}" class="btn btn-default">Cancelar</a>
    </div>

</div>

<div class="col-sm-3">
    <div class="thumbnail">
        @if(isset($empresa) && isset($empresa->logo))
            <img id="img_destino" src="{{ asset('/logos/'.$empresa->logo) }}" alt="logo" >
        @else
            <img id="img_destino" src="{{ asset('/user_photos/foto_base.png') }}" alt="logo" style="height: 350px">
        @endif

        <div class="caption text-center">
            <div class="foto_boton file btn btn-lg btn-primary">
                <i class="glyphicon glyphicon-paperclip"></i> Cargar Logo
                <input id="foto_input" class="foto_input" type="file" name="foto_input" accept="image/*"/>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="thumbnail">
        @if(isset($empresa) && isset($empresa->membrete))
            <img id="img_destino_membrete" src="{{ asset('/membretes/'.$empresa->membrete) }}" alt="membrete">
        @else
            <img id="img_destino_membrete" src="{{ asset('/images/membrete.jpg') }}" alt="membrete" style="height: 350px">
        @endif

        <div class="caption text-center">
            <div class="foto_boton file btn btn-lg btn-primary">
                <i class="glyphicon glyphicon-paperclip"></i> Cargar Membrete
                <input id="membrete_input" class="foto_input" type="file" name="membrete_input" accept="image/*"/>
            </div>
        </div>
    </div>
</div>
