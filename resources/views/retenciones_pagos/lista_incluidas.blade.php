@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Retenciones incluidas</h1>
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
                    <table class="table table-striped" id="retenciones-table">
                        <thead>
                        <tr>
                            <th>#</th>

                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Productor</th>
                            <th>Glosa</th>
                            <th>Monto BOB</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $page = ($retenciones->currentPage() - 1) * $retenciones->perPage();
                        $row = 1;
                        ?>
                        @foreach($retenciones as $retencion)
                            <tr>
                                <td class="text-muted">{{ $page + ($row++) }}</td>

                                <td>{{ date('d/m/y', strtotime($retencion->created_at)) }}</td>
                                <td>{!! $retencion->nombre !!}</td>

                                <td>{!! $retencion->cooperativa->razon_social !!}</td>
                                <td>{!! $retencion->motivo !!}</td>
                                <td>{{ number_format($retencion->monto, 2) }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: end">
                                <strong> TOTAL BOB: {{ number_format($retenciones->sum('monto'), 2) }}</strong>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
                @if(!$pago->es_aprobado AND \App\Patrones\Permiso::esAdmin())
                    <div class="form-group col-sm-12">

                    {!! Form::open(['route' => 'aprobar-retencion-caja']) !!}

                    {!! Form::hidden('id', $pago->id, ['class' => 'form-control']) !!}

                    <!-- Submit Field -->
                        {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}

                        {!! Form::close() !!}

                    </div>
                @endif
            </div>

        </div>
        <div class="text-center">
            {{ $retenciones->appends($_GET)->links()  }}
        </div>

    </div>
@endsection
