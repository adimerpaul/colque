@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Modificar comprador
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($comprador, ['route' => ['compradores.update', $comprador->id], 'method' => 'patch']) !!}

                       @include('compradores.fields')

                   {!! Form::close() !!}

               </div>

               @if(\App\Patrones\Permiso::esAdmin() and !$comprador->es_aprobado)
                   {!! Form::model($comprador, ['route' => ['aprobar-cliente', $comprador->id], 'method' => 'patch']) !!}
                   {!! Form::hidden('tipo', \App\Patrones\TipoCliente::COMPRADOR, ['required']) !!}

                   {!! Form::button('Aprobar', ['type' => 'submit', 'class' => 'btn btn-success btn-lm']) !!}
                   {!! Form::close() !!}
               @endif
           </div>
       </div>
   </div>
@endsection
