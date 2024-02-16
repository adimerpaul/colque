@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">Reporte Contabilidad de Cooperativa <strong>{{$productor->razon_social}}</strong> </h1>
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
                        {!! Form::open(['route' => ['cooperativas.reporte-contabilidad', $idCooperativa], 'method'=>'get']) !!}

                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'F. Liq. Inicio:') !!}
                            {!! Form::date('fecha_inicio', isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'F. Liq. Fin:') !!}
                            {!! Form::date('fecha_fin', isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null, ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-2">
                            {!! Form::label('producto_id', 'Producto: *') !!}
                            {!! Form::select('producto_id', [null => 'Todos...'] + \App\Models\Producto::orderBy('letra')->get()->pluck('info', 'letra')->toArray(), isset($_GET['producto_id']) ? $_GET['producto_id'] : null, ['class' => 'form-control']) !!}
                        </div>


                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        <div class="form-group col-sm-4" style="margin-top: 25px">
                            <button type="button" class="btn btn-success"
                                    onclick="exportarAExcel()"><i
                                    class="fa fa-file-excel-o"></i>
                                Exportar
                            </button>
                            <button type="button" class="btn btn-primary"
                                    onclick="exportarAPdf()"><i
                                    class="fa fa-file-pdf-o"></i>
                                Resumen
                            </button>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>

                @include('cooperativas.reporte_contabilidad.table')


            </div>
        </div>
        <div class="text-center">
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
                sa = iframeExport.document.execCommand("SaveAs", true, "kardex.xls");
            }
            else {
                var link = document.createElement('a');

                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = "kardex.xls";
                link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
                link.click();
                document.body.removeChild(link);
            }
        }

        function exportarAPdf() {
            var url =window.location.href
            url = url.replace('reporte', 'resumen')
            window.open(url, '_blank');
        }

    </script>

@endsection
