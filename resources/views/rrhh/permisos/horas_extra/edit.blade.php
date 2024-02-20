@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Editar hora extra
        </h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($horasExtra, ['route' => ['horas-extras.update', $horasExtra->id], 'method' => 'patch']) !!}
                        <!--Fecha Inicial-->
                        <div class="form-group col-sm-6">
                            {!! Form::label('inicio', 'Fecha Inicio: ') !!}
                            {!! Form::datetimeLocal('inicio', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        
                        <!--Fecha Final-->
                        <div class="form-group col-sm-6">
                            {!! Form::label('fin', 'Fecha Fin: ') !!}
                            {!! Form::datetimeLocal('fin', null, ['class' => 'form-control', 'required']) !!}
                        </div>

                        <!-- ID personal-->
                        <div class="form-group col-sm-4">
                            {!! Form::label('personal_id', 'Personal :') !!}
                            {!! Form::select('personal_id', [null => 'Seleccione...'] + \App\Patrones\Fachada::getPersonal(), nulL, ['class' => 'form-control', 'required']) !!}

                        </div>
                        <!-- Descripcion-->
                        <div class="form-group col-sm-8">
                            {!! Form::label('descripcion', 'Descripción:') !!}
                            {!! Form::text('descripcion', null, ['class' => 'form-control','maxlength' => '300','required']) !!}
                        </div>
                        
                        <div class="form-group col-sm-12">
                            <br>
                            {!! Form::submit('Solicitar', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('horas-extras.index') }}" class="btn btn-default">Cancelar</a>
                        </div>
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection