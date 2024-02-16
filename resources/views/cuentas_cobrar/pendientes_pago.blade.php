@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pendientes de pago</h1>
        <h1 class="pull-right">

        </h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>


        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1-1"
                       data-toggle="tab">
                        <i class="fa fa-money"></i>
                        Cuentas Por Cobrar </a>
                </li>
                <li><a href="#tab_2-2" data-toggle="tab"><i class="fa fa-usd"></i> Anticipos</a></li>

            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    <div style="padding: 15px">
                        @include('cuentas_cobrar.table_pendientes_cuentas')
                    </div>
                    <div class="text-center">
                        {{ $cuentas->appends($_GET)->links()  }}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    <div style="padding: 15px">
                        @include('cuentas_cobrar.table_pendientes_anticipos')
                    </div>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>



        {!! Form::open(['route' => 'cuentas-cobrar.agregar-pendiente']) !!}
        @include("cuentas_cobrar.modal_transferencia")
        {!! Form::close() !!}
    </div>

    <script>
        document.getElementById("estadoDeuda").innerHTML = "TIPO: {{ (is_null($estado)  or $estado=='%') ? 'TODOS' : strtoupper($estado)}}";

        $('#modalCuenta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')

            var modal = $(this)
            modal.find('.modal-body #idCuenta').val(id);
        })

        function exportarAExcel() {
            var nombreArchivo = 'reporteDeudores.xls';
            var htmlExport = jQuery('#cuentas-table').prop('outerHTML')
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            //other browser not tested on IE 11
            // If Internet Explorer
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                jQuery('body').append(" <iframe id=\"iframeExport\" style=\"display:none\"></iframe>");
                iframeExport.document.open("txt/html", "replace");
                iframeExport.document.write(htmlExport);
                iframeExport.document.close();
                iframeExport.focus();
                sa = iframeExport.document.execCommand("SaveAs", true, nombreArchivo);
            } else {
                var link = document.createElement('a');

                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = nombreArchivo;
                link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
                link.click();
                document.body.removeChild(link);
            }
        }

    </script>
@endsection
