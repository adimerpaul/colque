@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Movimientos</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esCaja() )
                <a class="btn btn-info pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-left: 2px"
                   href="#" data-target="#modalTransferencia" data-toggle="modal">Transferencia interna</a>
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('movimientos.create') }}">Agregar nuevo</a>
            @endif
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
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'movimientos.index', 'method'=>'get']) !!}
                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Glosa, proveedor, comprobante']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Inicio:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('txtEstado', 'Estado:') !!}
                            {!! Form::select('txtEstado', \App\Patrones\Fachada::getEstadosCajaAnticipos(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-4">
                            {!! Form::label('txtDescripcion', 'Descripción:') !!}
                            {!! Form::select('txtDescripcion', \App\Patrones\Fachada::getCatalogosMovimientos(),isset($_GET['txtDescripcion']) ? $_GET['txtDescripcion'] : null, ['class' => 'form-control']) !!}
                        </div>
                        @if($esCancelado)
                            <div class="form-group col-sm-2">
                                {!! Form::label('txtOficina', 'Oficina:') !!}
                                {!! Form::select('txtOficina', ['%' => 'Todos'] +\App\Patrones\Fachada::getOficinasMovimiento(),isset($_GET['txtOficina']) ? $_GET['txtOficina'] : null, ['class' => 'form-control']) !!}
                            </div>
                        @endif
                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                            <button type="button" class="btn btn-success"
                                    onclick="exportarAExcel()"><i
                                    class="fa fa-file-excel-o"></i>
                                Exportar
                            </button>


                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('movimientos.table')
            </div>
        </div>
        <div class="text-center">
            {{ $pagos->appends($_GET)->links()  }}
        </div>
        {!! Form::open(['route' => 'registrar-transferencia', 'id' => 'formularioModal']) !!}
        @include("movimientos.modal_transferencias")
        {!! Form::close() !!}
    </div>
    <script type="text/javascript">
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
        function exportarAExcel() {
            var inicio = document.getElementById("fecha_inicial").value;
            var fin = document.getElementById("fecha_final").value;

            var nombreArchivo = 'reporteMovimientos' + "{{$pagos->currentPage()}}" +'_'+ inicio + '_AL_' + fin + '.xls';
            var htmlExport = jQuery('#movimientos-table').prop('outerHTML')
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

        function exportarPdf() {
            var inicio = document.getElementById("fecha_inicial").value;
            var fin = document.getElementById("fecha_final").value;

            if (inicio == '' || fin == '') {
                alert('Primero elija las fechas');
            } else {
                window.open("/movimientos/reporte-pdf/" + inicio + '/' + fin);
            }
        }
    </script>
@endsection



