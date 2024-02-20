@extends('layouts.app')

@section('content')
    <section class="content-header">

        <h1 class="pull-left">Registro de pago/cobro</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="{{ route('proveedores.index') }}">Proveedores</a>

        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1-1">
                        <i class="fa fa-money"></i>
                        Movimientos </a>
                </li>
                <li><a data-toggle="tab" href="#tab_2-2"><i class="fa fa-dollar"></i> Análisis de laboratorio</a>
                </li>

                <li><a data-toggle="tab" href="#tab_3-3"><i class="fa fa-balance-scale"></i> Pesaje</a>
                </li>

            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    <div class="row">
                        <br>
                        @include('movimientos.total')
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_2-2">
                    <div class="row">
                        @if(\App\Patrones\Permiso::esCaja() )
                            @include('movimientos.laboratorio')
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="tab_3-3">
                    <div class="row">
                        @if(\App\Patrones\Permiso::esCaja() )
                            @include('movimientos.pesaje')
                        @endif
                    </div>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>

    </div>
    @include("proveedores.modal")

@endsection

@push('scripts')
    <script>
        function getProveedores() {
            let url = "{{ url('get_proveedores') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="proveedor_id"]').empty();
                    $('select[name="proveedor_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="proveedor_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }
    </script>
@endpush
