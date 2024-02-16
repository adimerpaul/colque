@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Liquidaciones</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="{{ route('cajas.comprobante') }}">Comprobantes</a>
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
                        {!! Form::open(['route' => 'cajas.index', 'method'=>'get']) !!}
                        <div class="form-group col-sm-3">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Nro de lote, cliente, productor, producto']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            @if($esCancelado)
                                {!! Form::label('Fecha', 'Fecha Canc. Ini.:') !!}
                            @else
                                {!! Form::label('Fecha', 'Fecha Liq. Ini.:') !!}
                            @endif
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 3 months')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            @if($esCancelado)
                                {!! Form::label('Fecha', 'Fecha Canc. Fin.:') !!}
                            @else
                                {!! Form::label('Fecha', 'Fecha Liq. Fin.:') !!}
                            @endif
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('txtEstado', 'Estado:') !!}
                            {!! Form::select('txtEstado', \App\Patrones\Fachada::getEstadosCaja(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                            @if($esCancelado)
                                <button type="button" class="btn btn-success"
                                        onclick="exportarAExcel()"><i
                                        class="fa fa-file-excel-o"></i>
                                    Exportar
                                </button>
                            @endif

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('cajas.table')
            </div>
        </div>
        <div class="text-center">
            {{ $formularioLiquidacions->appends($_GET)->links()  }}
        </div>
    </div>
    <script type="text/javascript">
        function exportarAExcel() {
            var inicio = document.getElementById("fecha_inicial").value;
            var fin = document.getElementById("fecha_final").value;

            var nombreArchivo = 'reporteLiquidaciones' + "{{$formularioLiquidacions->currentPage()}}"+'_' + inicio + '_AL_' + fin + '.xls';
            var htmlExport = jQuery('#formularioLiquidacions-table').prop('outerHTML')
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



