@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotización Oficial de <strong>{{ $cotizacionOficial->mineral->simbolo .' | '. $cotizacionOficial->mineral->nombre }}</strong>

        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($cotizacionOficial, ['route' => ['cotizacionOficials.update', $cotizacionOficial->id], 'method' => 'patch']) !!}

                        @include('cotizacion_oficials.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
