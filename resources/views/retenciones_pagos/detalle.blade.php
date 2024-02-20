@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">Reporte Kardex</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group col-sm-3">
                            <button type="button" class="btn btn-success"
                                    onclick="exportarAExcel()"><i
                                    class="fa fa-file-excel-o"></i>
                                Exportar
                            </button>
                        </div>

                    </div>
                </div>
                    @include('retenciones_pagos.table_detalle')

            </div>
        </div>
        <div class="text-center">
            {{--            {{ $formularios->appends($_GET)->links()  }}--}}
        </div>
    </div>
    <script>

        function exportarAExcel() {
            var htmlExport = jQuery('#kardex-tabla').prop('outerHTML')
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            //other browser not tested on IE 11
            // If Internet Explorer
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))
            {
                jQuery('body').append(" <iframe id=\"iframeExport\" style=\"display:none\"></iframe>");
                iframeExport.document.open("txt/html", "replace");
                iframeExport.document.write(htmlExport);
                iframeExport.document.close();
                iframeExport.focus();
                sa = iframeExport.document.execCommand("SaveAs", true, "retenciones.xls");
            }
            else {
                var link = document.createElement('a');

                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = "retenciones.xls";
                link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
                link.click();
                document.body.removeChild(link);
            }
        }

    </script>

@endsection
