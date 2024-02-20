@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Mineral
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($material, ['route' => ['materials.update', $material->id], 'method' => 'patch']) !!}

                        @include('materials.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
