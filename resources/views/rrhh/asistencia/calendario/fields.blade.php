{!! Form::open(['route' => 'calendario.crear']) !!}

    
    <!-- Fecha anual calendario -->
    <div class="form-group col-sm-6">
        {!! Form::label('fecha', 'Año: *') !!}
        {!! Form::select('fecha', ['' => 'Seleccione'] + array_combine(range(date('Y'), date('Y') + 30), range(date('Y'), date('Y') + 30)), null, ['class' => 'form-control', 'required']) !!}

    </div>
    <!--guardar y cancelar-->
    <div class="form-group col-sm-">
        <br>
        {!! Form::submit('Crear', ['class' => 'btn btn-primary','onclick' => "return confirm('Estas de seguro de crear elemento?')"]) !!}
        <a href="{{ route('calendario.index') }}" class="btn btn-default">Cancelar</a>
    </div>

{!! Form::close() !!}


<!--tabla de actualizacion historial-->
<div class="form-group col-sm-12">
    <h3 style="text-align: center">Años creados</h3>
    @include('rrhh.asistencia.calendario.table')
</div>



