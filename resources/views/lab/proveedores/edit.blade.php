@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1>
            Proveedor
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

                    {!! Form::model($proveedor, ['route' => ['proveedores-lab.update', $proveedor->id], 'method' => 'patch']) !!}

                    @include('lab.proveedores.fields')

                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>
@endsection
