@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Permisos Registrados del Personal</b>
        </h1>
        <h1 class="pull-right">
            
                <a class="btn btn-primary pull-right"
                   style="margin-top: -30px;margin-bottom: 5px"
                   href="{{ route('tipospermisos.create') }}"
                   title="Crear o asignar permisos para el personal">Asignar Permisos</a>
        </h1> 
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::open(['route' => 'mostrarpermisos.general','method'=>'get'])!!}
                        <div class="form-group col-sm-3">
                                {!! Form::label('personal_id', 'Personal:') !!}
                                {!! Form::select('personal_id', ['%' => 'Todos']+\App\Patrones\Fachada::getPersonal(),isset($_GET['personal_id']) ? $_GET['personal_id'] : null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('inicio', 'Fecha Inicial:') !!}
                            {!! Form::date('inicio', old('inicio', isset($_GET['inicio']) ? $_GET['inicio'] : date('Y-m-d', strtotime('-1 month', strtotime(isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d'))))), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('fin', 'Fecha Final:') !!}
                            {!! Form::date('fin', old('fin', isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d')), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-4">
                            {!! Form::label('tipo', 'Tipo:') !!}
                            {!! Form::select('tipo', ['%' => 'Todos']+ \App\Patrones\Fachada::getTiposPermisos(),isset($_GET['tipo']) ? $_GET['tipo'] : null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-1" style="margin-top: 24px">
                            <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar datos"></button>
                        </div>
                    {!! Form::close() !!}

                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success"
                                onclick="exportarAExcel()"><i
                                class="fa fa-file-excel-o"></i>
                            Exportar
                        </button>
                        <br>
                        <br>
                    </div>
                </div>
                @include('rrhh.permisos.permisos_generales.table')
            </div>
        </div>
    </div>

    <script>
        document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicial)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";

        function exportarAExcel() {
            var nombreArchivo = 'reportePermisos' + "{{$permisos->currentPage()}}" + "_{{ $fechaInicial }}" + '_AL_' + "{{ $fechaFinal}}" + '.xls';
            var htmlExport = jQuery('#permisos-table').prop('outerHTML')
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
