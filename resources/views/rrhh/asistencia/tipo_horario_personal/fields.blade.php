{!! Form::open(['route' => 'tipo-horario-personal.crear']) !!}

    {!! Form::hidden('personal_id',  $personal->id, ['class' => 'form-control']) !!}
    
    <!-- tipo horario id -->
    <div class="form-group col-sm-12">
        {!! Form::label('tipo_horario_id', 'Tipo de Horario: *') !!}
        {!! Form::select('tipo_horario_id', \App\Patrones\Fachada::getTipoHorario(), isset($_GET['tipo_id']) ? $_GET['tipo_id'] : null, ['class' => 'form-control', 'required']) !!}
    </div>

    <!-- fecha inicial-->
   
    <div class="form-group col-sm-3">
            {!! Form::label('fecha_inicial', 'Fecha Inicial : *') !!}
            {!! Form::date('fecha_inicial',null, ['class' => 'form-control', 'required']) !!}
    </div>

    <!-- fecha final-->
    <div class="form-group col-sm-3">
            {!! Form::label('fecha_fin', 'Fecha Final : *') !!}
            {!! Form::date('fecha_fin',null, ['class' => 'form-control', 'required']) !!}

    </div>
    
    <!--guardar y cancelar-->
    <div class="form-group col-sm-12">
        <br>
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('empresas.show', '1') }}" class="btn btn-default">Cancelar</a>
        

    </div>

{!! Form::close() !!}


<!--tabla de actualizacion historial-->
<div class="form-group col-sm-12">
    <h3 style="text-align: center">Historial</h3>
    @include('rrhh.asistencia.tipo_horario_personal.table')
</div>



