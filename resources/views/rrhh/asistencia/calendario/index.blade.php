@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Calendario</h1>
        <h1 class="pull-right">
                <a class="btn btn-primary pull-right"
                   style="margin-top:-30px;margin-bottom: 5px"
                   href="{{ route('feriados') }}" title="Feriados">Feriados</a>
        </h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                    @include('rrhh.asistencia.calendario.fields')
               </div>

           </div>

       </div>
   </div>
@endsection
