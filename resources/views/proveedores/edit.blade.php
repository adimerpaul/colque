@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Modificar proveedor
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($proveedor, ['route' => ['proveedores.update', $proveedor->id], 'method' => 'patch']) !!}

                    @include('proveedores.fields')

                    {!! Form::close() !!}


                </div>
                @if(\App\Patrones\Permiso::esAdmin() and !$proveedor->es_aprobado)
                    {!! Form::model($proveedor, ['route' => ['aprobar-cliente', $proveedor->id], 'method' => 'patch']) !!}
                    {!! Form::hidden('tipo', \App\Patrones\TipoCliente::PROVEEDOR, ['required']) !!}

                    {!! Form::button('Aprobar', ['type' => 'submit', 'class' => 'btn btn-success btn-lm']) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
@endsection
