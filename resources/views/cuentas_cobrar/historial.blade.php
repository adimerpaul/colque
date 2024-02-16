@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Historial de cuenta por cobrar</h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">

                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-striped" id="cuentas-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                            <th>Observación</th>
                            <th>Usuario</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($historial as $cuenta)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>{{ date('d/m/y', strtotime($cuenta->created_at)) }}</td>
                                <td>{!! $cuenta->accion !!}</td>
                                <td>{!! $cuenta->observacion !!}</td>
                                <td>
                                    @if($cuenta->users)
                                        {{ $cuenta->users->personal->nombre_completo}}
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>


            </div>
        </div>

    </div>


@endsection
