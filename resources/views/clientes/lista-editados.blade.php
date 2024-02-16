@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Edición de clientes</h1>
        <h1 class="pull-right">

        </h1>
        <br>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="clientes-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Valor antiguo</th>
                            <th>Valor nuevo</th>
                            <th>Usuario edición</th>
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
                                <td>{{ date('d/m/Y H:i', strtotime($cliente->updated_at)) }}</td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->valores_antiguos }}</td>
                                <td>{{ $cliente->valores_nuevos }}</td>
                                <td>{{ $cliente->nombre_completo }}</td>
                                <td>
                                    <a href="{{ route('clientes.edit', [$cliente->id]) }}" class='btn btn-default btn-xs'><i
                                            class="glyphicon glyphicon-edit"></i></a>
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

