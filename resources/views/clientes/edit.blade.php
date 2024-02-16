@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cliente
        </h1>
   </section>
   <div class="content">
       <div class="clearfix"></div>

       @include('flash::message')

       <div class="clearfix"></div>
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">

                   {!! Form::model($cliente, ['route' => ['clientes.update', $cliente->id], 'method' => 'patch', 'files'=>true]) !!}

                       @include('clientes.fields')

                   {!! Form::close() !!}

               </div>
               @if(\App\Patrones\Permiso::esAdmin() and !$cliente->es_aprobado)
                   {!! Form::model($cliente, ['route' => ['aprobar-cliente', $cliente->id], 'method' => 'patch']) !!}
                   {!! Form::hidden('tipo', \App\Patrones\TipoCliente::CLIENTE, ['required']) !!}

                   {!! Form::button('Aprobar', ['type' => 'submit', 'class' => 'btn btn-success btn-lm']) !!}
                   {!! Form::close() !!}
               @endif
           </div>
       </div>
   </div>
@endsection
