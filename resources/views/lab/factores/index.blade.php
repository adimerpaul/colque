@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Factores Volumétricos </h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="#" data-target="#modalRegistro"
               data-toggle="modal">Agregar nuevo</a>
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


                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="clientes-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $page = ($factores->currentPage() - 1) * $factores->perPage();
                        $row = 1;
                        ?>
                        @foreach($factores as $factor)
                            <tr>
                                <td class="text-muted">{{ $page + ($row++) }}</td>
                                <td>{{ date('d/m/Y', strtotime($factor->created_at))}}</td>
                                <td>{{ $factor->valor }}</td>

                                <td>

                                    <div class='btn-group'>
                                        <div class='btn-group'>
                                            <a href="#" data-target="#modalEdicion"
                                               data-txtid="{{$factor->id}}"
                                               data-txtvalor="{{$factor->valor}}"
                                               data-toggle="modal"
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
            {{ $factores->appends($_GET)->links()  }}
        </div>
    </div>
    {!! Form::open(['route' => 'factores-volumetricos.store', 'id' => 'formularioModal']) !!}
    @include("lab.factores.modal_registro")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-factor-volumetrico', 'id' => 'formularioModalEdit']) !!}
    @include("lab.factores.modal_edicion")
    {!! Form::close() !!}
@endsection

@push('scripts')

    <script>

        $('#modalEdicion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var valor = button.data('txtvalor')

            var modal = $(this)
            modal.find('.modal-body #idFactor').val(id);
            modal.find('.modal-body #valor').val(valor);
        })

    </script>

@endpush
