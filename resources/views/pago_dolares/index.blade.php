@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Pagos / Cobros en dólares</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esCaja())
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('pagos-dolares.create') }}">Registrar</a>
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
                        {!! Form::open(['route' => 'pagos-dolares.index', 'method'=>'get']) !!}
{{--                        <div class="form-group col-sm-4">--}}
{{--                            {!! Form::label('txtBuscar', 'Buscar por:') !!}--}}
{{--                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Lote, cliente, productor, producto, monto, comprobante']) !!}--}}
{{--                        </div>--}}
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 weeks')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>

                        <div class="form-group col-sm-4" style="margin-top: 25px">
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
                @include('pago_dolares.table')
            </div>
        </div>
        <div class="text-center">
            {{ $pagos->appends($_GET)->links()  }}
        </div>
    </div>
    <script>
        document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicial)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";

        function exportarAExcel() {
            var nombreArchivo = 'reporteDolares' + "{{$pagos->currentPage()}}" + "_{{ $fechaInicial }}" + '_AL_' + "{{ $fechaFinal}}" + '.xls';
            var htmlExport = jQuery('#anticipos-table').prop('outerHTML')
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
