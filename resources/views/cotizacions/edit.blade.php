@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotización de <strong>{{ $cotizacion->mineral->simbolo .' | '. $cotizacion->mineral->nombre }}</strong>
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($cotizacion, ['route' => ['cotizacions.update', $cotizacion->id], 'method' => 'patch']) !!}

                        @include('cotizacions.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
