@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Activos Fijos</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esActivos())
                <a class="btn btn-primary pull-right"
                   style="margin-top: -10px;margin-bottom: 5px margin-right: 3px"
                   href="{{ route('activos-fijos.create') }}">Agregar nuevo</a>
                <a class="btn btn-success pull-right"
                   style="margin-top: -10px;margin-bottom: 5px; margin-right: 3px"
                   href="{{ route('tipos-activos.index') }}">Tipos</a>

            @endif
        </h1>
    </section>

    <section class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class=¨card¨>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            {!! Form::open(['route' => 'activos-fijos.index', 'method' => 'get']) !!}

                            <div class="form-group col-sm-6">
                                {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Código, Descripción']) !!}
                            </div>
                            <div class="form-group col-sm-2">
                                {!! Form::label('txtEstado', 'Estado :') !!}
                                {!! Form::select('txtEstado', \App\Patrones\Fachada::getTiposEstados(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group col-sm-3">
                                {!! Form::label('personal_id', 'Responsable :') !!}
                                {!! Form::select('personal_id', ['%' => 'Todos']+\App\Patrones\Fachada::getPersonal(),isset($_GET['personal_id']) ? $_GET['personal_id'] : null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="form-group col-sm-4">
                                {!! Form::label('tipo_id', 'Tipo :') !!}
                                {!! Form::select('tipo_id', ['%' => 'Todos']+\App\Patrones\Fachada::getTiposActivos(),isset($_GET['tipo_id']) ? $_GET['tipo_id'] : null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="form-group col-sm-5" style="margin-top: 25px">
                                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                    Buscar
                                </button>
                                <button type="button" class="btn btn-success"
                                        onclick="exportarAExcel()"><i
                                        class="fa fa-file-excel-o"></i>
                                    Exportar
                                </button>
                                @if(\App\Patrones\Permiso::esActivos())
                                <a class="btn btn-primary"
                                   href="#"
                                   data-target="#modalActa"
                                   data-toggle="modal">Acta Entrega</a>
                                    @endif
                            </div>

                            {!! Form::close() !!}
                            @if($mensaje = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{$mensaje}}
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('activos.activos_fijos.table')
                    </div>
                </div>
                <div class="text-center">
                    {{ $activosFijos->appends($_GET)->links() }}
                </div>
            </div>
        </div>
        @include("activos.activos_fijos.modal_acta")
    </section>
    <script>
        function generarActa(){
            let personalId = document.getElementById('personalId').value;
            let fechaInicial = document.getElementById('fechaInicial').value;
            let fechaFinal = document.getElementById('fechaFinal').value;
            window.open('/imprimir-acta-activo/'+personalId+'/'+fechaInicial+'/'+fechaFinal ,  '_blank')
        }

        function exportarAExcel() {
            var htmlExport = jQuery('#activos-tabla').prop('outerHTML')
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
                sa = iframeExport.document.execCommand("SaveAs", true, "activosFijos.xls");
            }
            else {
                var link = document.createElement('a');

                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = "activosFijos.xls";
                link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
                link.click();
                document.body.removeChild(link);
            }
        }

    </script>
@endsection
