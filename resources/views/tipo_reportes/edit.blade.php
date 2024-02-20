@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo de reporte
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipoReporte, ['route' => ['tipoReportes.update', $tipoReporte->id], 'method' => 'patch']) !!}

                       @include('tipo_reportes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
