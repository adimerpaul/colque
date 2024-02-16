@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo Cambio
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipoCambio, ['route' => ['tipoCambios.update', $tipoCambio->id], 'method' => 'patch']) !!}

                   <!-- Fecha Field -->
                       <div class="form-group col-sm-4">
                           {!! Form::label('fecha', 'Fecha: *') !!}
                           {!! Form::text('fecha', date('d/m/Y', strtotime($tipoCambio->fecha)), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
                       </div>

                       @include('tipo_cambios.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
