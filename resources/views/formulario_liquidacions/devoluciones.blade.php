@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Devoluciones de lote </h1>
        <h1 class="pull-right">
        </h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">

                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="devoluciones-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Causa</th>
                            <th>Glosa</th>
                            <th>Monto (BOB)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($devoluciones as $devolucion)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration}}</td>
                                <td>{{ date('d/m/Y', strtotime($devolucion->fecha)) }}</td>
                                <td>{!! $devolucion->causa !!}</td>
                                <td>{!! $devolucion->motivo !!}</td>
                                <td>{{ number_format($devolucion->monto, 2) }}</td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    </div>
@endsection
