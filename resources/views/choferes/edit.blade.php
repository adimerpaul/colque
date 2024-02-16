@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Modificar conductor
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($chofer, ['route' => ['choferes.update', $chofer->id], 'method' => 'patch']) !!}

                       @include('choferes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
