@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Canjear puntos
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'canjear-puntos']) !!}

                    <div class="form-group col-sm-6">
                        {!! Form::label('descripcion', 'Descripción: *') !!}
                        {!! Form::text('descripcion', null, ['class' => 'form-control', 'maxlength' => '100', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-6" >
                        {!! Form::label('valor', 'Valor :*') !!}
                        {!! Form::number('valor', null, ['class' => 'form-control', 'required', 'maxlength' => '7','step'=>'0.01', 'min' =>'0']) !!}
                    </div>
                    {!! Form::hidden('cliente_id',  $cliente->id, ['class' => 'form-control']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('clientes.lista', ['id' => $cliente->cooperativa_id]) }}" class="btn btn-default">Cancelar</a>
                    </div>

                    {!! Form::close() !!}
                </div>

            </div>
            <div class="box-body">
                @include('clientes.table_puntos')
            </div>
            <div class="text-center">
                {{ $puntos->appends($_GET)->links()  }}
            </div>
        </div>

    </div>
@endsection
