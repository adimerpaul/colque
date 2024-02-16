@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Ingresos/Egresos de <strong>{{$insumos[0]->insumo->nombre}}</strong> </h1>

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


                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="clientes-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Cantidad </th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $page = ($insumos->currentPage() - 1) * $insumos->perPage();
                        $row = 1;
                        ?>
                        @foreach($insumos as $inventario)
                            <tr>
                                <td class="text-muted">{{ $page + ($row++) }}</td>
                                <td>{{ $inventario->insumo->nombre }}</td>
                                <td>{{ date('d/m/Y', strtotime($inventario->fecha)) }}</td>
                                <td>{{ $inventario->cantidad }}</td>
                                <td>{{ $inventario->tipo }}</td>

                                <td>

                                    {!! Form::open(['route' => ['insumos.destroy', $inventario->id], 'method' => 'delete']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                                    {!! Form::close() !!}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="text-center">
            {{ $insumos->appends($_GET)->links()  }}
        </div>
    </div>

@endsection

