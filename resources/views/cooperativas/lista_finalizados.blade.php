@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Registros de productores</h1>
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
                            <th>Razón social</th>
                            <th>Nit</th>
                            <th>Nim</th>
                            <th>Usuario registro</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $page = ($cooperativas->currentPage() - 1) * $cooperativas->perPage();
                        $row = 1;
                        ?>
                        @foreach($cooperativas as $cooperativa)
                            <tr>
                                <td class="text-muted">{{ $page + ($row++) }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($cooperativa->updated_at)) }}</td>
                                <td>{{ $cooperativa->razon_social }}</td>
                                <td>{{ $cooperativa->nit }}</td>
                                <td>{{ $cooperativa->nro_nim}}</td>

                                <td>{{ $cooperativa->nombre_completo }}</td>
                                <td>
                                    <a href="{{ route('cooperativas.edit', [$cooperativa->id]) }}" class='btn btn-default btn-xs'><i
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
            {{ $cooperativas->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

