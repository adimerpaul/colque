@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Ensayos</h1>
        <br>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'ensayos-campos', 'method' => 'get']) !!}
                            <div class="form-group col-sm-3">
                                {!! Form::label('fecha', 'Fecha :') !!}
                                {!! Form::date('fecha', isset($_GET['fecha'])?$_GET['fecha']:null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group col-sm-1" style="margin-top: 25px">
                                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-8">
                        @include('lab.ensayos.ensayos_table')
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            {{ $ensayos->appends($_GET)->links() }}
        </div>
    </div>
@endsection

