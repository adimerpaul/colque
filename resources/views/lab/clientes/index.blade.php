@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Clientes </h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="{{ route('clientes-lab.create') }}">Agregar nuevo</a>
        </h1>
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
                        {!! Form::open(['route' => 'clientes-lab.index', 'method' => 'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Nombre, Nit, Celular']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="clientes-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nit</th>
                            <th>Nombre</th>
                            <th>Celular</th>
                            <th>Dirección</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $page = ($clientes->currentPage() - 1) * $clientes->perPage();
                        $row = 1;
                        ?>
                        @foreach($clientes as $cliente)
                            <tr>
                                <td class="text-muted">{{ $page + ($row++) }}</td>
                                <td>{{ $cliente->carnet}}</td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->celular }}</td>
                                <td>{{ $cliente->direccion }}</td>

                                <td>

                                    <div class='btn-group'>
                                        <div class='btn-group'>
                                            <a href="{{ route('clientes-lab.edit', [$cliente->id]) }}"
                                               class='btn btn-default btn-xs'><i
                                                    class="glyphicon glyphicon-edit"></i></a>

                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="text-center">
            {{ $clientes->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

