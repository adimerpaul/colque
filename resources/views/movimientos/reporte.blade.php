@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Reporte de Caja</h1>
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
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'movimientos.reporte', 'method'=>'get']) !!}
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha Inicio:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d'), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha Fin:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('tipo', 'Tipo: *') !!}
                            {!! Form::select('tipo', [null => 'Todos...'] +  \App\Patrones\Fachada::getTiposMovimientos(), isset($_GET['tipo']) ? $_GET['tipo'] : null, ['class' => 'form-control' ]) !!}
                        </div>

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
                @if($tipo==='Ingreso')
                    @include('movimientos.reporte.table_ingresos')
                @elseif($tipo==='Egreso')
                    @include('movimientos.reporte.table_egresos')
                @else
                    @include('movimientos.reporte.table_reporte')
                @endif

            </div>
        </div>
        <div class="text-center">
            {{ $pagos->appends($_GET)->links()  }}
        </div>
    </div>

    <script>
        var nombreArchivo='reporteCaja';
        if("{{$tipo}}"=='Ingreso'){
            nombreArchivo='reporteIngresosCaja'+"{{$pagos->currentPage()}}"+"_{{ $fechaInicial }}" +'_AL_'+ "{{ $fechaFinal}}"+'.xls';
        }
        else if("{{$tipo}}"=='Egreso'){
            nombreArchivo='reporteEgresosCaja'+"{{$pagos->currentPage()}}"+"_{{ $fechaInicial }}" +'_AL_'+ "{{ $fechaFinal}}"+'.xls';
        }

        else{
            nombreArchivo='reporteGeneralCaja'+"{{$pagos->currentPage()}}"+"_{{ $fechaInicial }}" +'_AL_'+ "{{ $fechaFinal}}"+'.xls';
        }
        function exportarAExcel() {
            var htmlExport = jQuery('#movimientos-tabla').prop('outerHTML')
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
                sa = iframeExport.document.execCommand("SaveAs", true, nombreArchivo);
            }
            else {
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

