@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Crear asistencia</h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
               {!! Form::open(['route' => 'crear.asistencias-manual']) !!}

                    <!-- Nombre del personal -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('personal_id', 'Personal a Registrar:*') !!}
                        {!! Form::select('personal_id', [null => 'Seleccione...'] + \App\Patrones\Fachada::getPersonal(), nulL, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <!-- Fecha de ingreso-->
                    <div class="form-group col-sm-8">
                            {!! Form::label('fecha_inicio', 'Fecha de Asistencia : *') !!}
                            {!! Form::date('fecha_inicio', \Carbon\Carbon::now(), ['class' => 'form-control', 'required']) !!}

                    </div>
                    
                    <!-- Hora ingreso del reemplazo-->
                    <div class="form-group col-sm-4">
                        {!! Form::label('hora_seleccion', 'Selecciona la hora: *') !!}
                        <div class="form-check">
                            {!! Form::label('ambos_label', 'Ambos') !!}
                            {!! Form::checkbox('ambos', 'ambos', true, ['class' => 'form-check-input', 'id' => 'ambos_checkbox', 'onchange' => 'validateAmbos()']) !!}

                            {!! Form::label('ingreso_label', 'Ingreso') !!}
                            {!! Form::checkbox('ingreso', 'ingreso', false, ['class' => 'form-check-input', 'id' => 'ingreso_checkbox','onchange' => 'validatehorarioIngreso()']) !!}

                            {!! Form::label('salida_label', 'Salida') !!}
                            {!! Form::checkbox('salida', 'salida', false, ['class' => 'form-check-input', 'id' => 'salida_checkbox', 'onchange' => 'validatehorarioSalida()']) !!}
                        </div>
                    </div>
                    <!-- Hora ingreso del reemplazo-->
                    <div class="form-group col-sm-4">
                        {!! Form::label('hora_inicio', 'Hora de Ingreso : *') !!}
                        {!! Form::time('hora_inicio', "08:00", ['class' => 'form-control', 'id' => 'hora_inicio']) !!}
                    </div>
                    <!-- Hora de salida del reemplazo-->
                    <div class="form-group col-sm-4">
                        {!! Form::label('hora_fin', 'Hora de Salida: *') !!}
                        {!! Form::time('hora_fin', "16:30", ['class' => 'form-control', 'id' => 'hora_fin']) !!}
                    </div>
                    <!--Motivo-->
                    <div class="form-group col-sm-12">
                        {!! Form::label('motivo', 'Motivo de reemplazo:*') !!}
                        {!! Form::text('motivo', null, ['class' => 'form-control','maxlength' => '300','required']) !!}
                    </div>
                    <!--guardar y cancelar-->
                    <div class="form-group col-sm-12">
                        <br>
                        {!! Form::submit('Solicitar', ['class' => 'btn btn-primary','onclick' => "return confirm('¿Estas de solicitar la Asistencia?')"]) !!}
                        <a href="{{ route('empresas.show', '1') }}" class="btn btn-default">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
               </div>

           </div>

       </div>
   </div>
@push('scripts')
    <script>
      $(document).ready(function () {
            $('input[type="checkbox"]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                }
            });
        });


    function validatehorarioIngreso() {
        var ingreso = document.getElementById('ingreso_checkbox').checked;
        var horaInicio = document.getElementById('hora_inicio');
        var horaSalida = document.getElementById('hora_fin');
        if (ingreso) {
            horaInicio.removeAttribute('disabled');
            horaInicio.setAttribute('required', 'required');
            horaSalida.setAttribute('disabled', 'disabled');
            horaSalida.removeAttribute('required');
        } else {
            horaInicio.setAttribute('disabled', 'disabled');
            horaInicio.removeAttribute('required');
        }
    }
    function validatehorarioSalida() {
        var salida = document.getElementById('salida_checkbox').checked;
        var horaInicio = document.getElementById('hora_inicio');
        var horaSalida = document.getElementById('hora_fin');
        
        if (salida) {
            horaSalida.removeAttribute('disabled');
            horaSalida.setAttribute('required', 'required');
            horaInicio.setAttribute('disabled', 'disabled');
            horaInicio.removeAttribute('required');
        } else {
            horaSalida.setAttribute('disabled', 'disabled');
            horaSalida.removeAttribute('required');
        }
    }
    function validateAmbos() {
        var ambos = document.getElementById('ambos_checkbox');
        var horaInicio = document.getElementById('hora_inicio');
        var horaSalida = document.getElementById('hora_fin');
        if (ambos.checked) {
            horaSalida.removeAttribute('disabled');
            horaSalida.setAttribute('required', 'required');
            horaInicio.removeAttribute('disabled');
            horaInicio.setAttribute('required', 'required');
        } else {
            horaSalida.setAttribute('disabled', 'disabled');
            horaSalida.removeAttribute('required');
            horaInicio.setAttribute('disabled', 'disabled');
            horaInicio.removeAttribute('required');
        }
    }
  
   
</script>

@endpush

@endsection
