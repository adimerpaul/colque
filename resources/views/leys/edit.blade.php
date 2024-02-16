@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Ley
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($ley, ['route' => ['leys.update', $ley->id], 'method' => 'patch']) !!}

                        @include('leys.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection