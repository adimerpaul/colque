<div class="form-group col-sm-6">
    {!! Form::label('fecha_inicio', 'Fecha Inicio: *') !!}
    {!! Form::date('fecha_inicio', date("Y-m-d"), ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('hora_inicio', 'Hora Inicio: *') !!}
    {!! Form::time('hora_inicio', "08:00", ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('fecha_fin', 'Fecha Fin: *') !!}
    {!! Form::date('fecha_fin', date("Y-m-d"), ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('hora_fin', 'Hora Fin: *') !!}
    {!! Form::time('hora_fin', "16:30", ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('tipo', 'Tipo: *') !!}
    {!! Form::select('tipo', [null => 'Seleccione...'] + \App\Patrones\Fachada::TiposPermisos($personal->id), null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('motivo', 'Motivo: ') !!}
    {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength'=>'200']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::submit('Solicitar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('mis-permisos') }}" class="btn btn-default">Volver</a>
</div>


