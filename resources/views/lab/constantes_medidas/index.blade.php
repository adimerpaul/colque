@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Constantes</h1>
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
                @include('lab.constantes_medidas.table')
            </div>
        </div>
        <div class="text-center">
            {{ $constantes->appends($_GET)->links() }}
        </div>
    </div>
    {!! Form::open(['route' => 'constantes-medidas.store', 'id' => 'formularioModal']) !!}
    @include("lab.constantes_medidas.modal_registro")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-constante-medida', 'id' => 'formularioModalEdit']) !!}
    @include("lab.constantes_medidas.modal_edicion")
    {!! Form::close() !!}
@endsection


@push('scripts')

    <script>

        $('#modalEdicion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var valor = button.data('txtvalor')
            var tipo = button.data('txttipo')

            var modal = $(this)
            modal.find('.modal-body #idConstante').val(id);
            modal.find('.modal-body #valor').val(valor);
            modal.find('.modal-body #tipo').val(tipo);
        })

    </script>

@endpush


