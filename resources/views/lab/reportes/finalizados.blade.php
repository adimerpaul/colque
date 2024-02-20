@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Muestras Finalizadas</h1>

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
                        {!! Form::open(['route' => 'get-finalizados-lab', 'method'=>'get']) !!}

                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d'), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>

                        <div class="form-group col-sm-2">
                            {!! Form::label('elemento_id', 'Elemento: *') !!}
                            {!! Form::select('elemento_id', ['%' => 'Todos...'] +  \App\Models\Lab\Elemento::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray(), isset($_GET['elemento_id']) ? $_GET['elemento_id'] : null, ['class' => 'form-control', 'required','id' => 'elemento_id' ]) !!}
                        </div>

                        <div class="form-group col-sm-3">
                            {!! Form::label('cliente_id', 'Cliente: *') !!}
                            {!! Form::select('cliente_id', ['%' => 'Todos...'] +  \App\Models\Lab\Cliente::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray(), isset($_GET['cliente_id']) ? $_GET['cliente_id'] : null, ['class' => 'form-control', 'required','id' => 'cliente_id' ]) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportarAPdf()" style="background-color: #ae1c17" title="Exportar Pdf"><i class="fa fa-file-pdf-o"></i>

                            </button>
                            <button type="button" class="btn btn-success" onclick="exportarAExcel()" title="Exportar Excel"><i class="fa fa-file-excel-o"></i>

                            </button>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('lab.reportes.table_finalizados')
            </div>
        </div>
        <div class="text-center">
            {{ $ensayos->appends($_GET)->links()  }}
        </div>
    </div>
    <script>
        function exportarAPdf() {
            var inicio = document.getElementById("fecha_inicial").value;
            var fin = document.getElementById("fecha_final").value;
            var elemento = document.getElementById("elemento_id").value;
            var cliente = document.getElementById("cliente_id").value;

            if (document.getElementById("elemento_id").value=='%')
                elemento='todo';
            if (document.getElementById("cliente_id").value=='%')
               cliente='todo';

            if (inicio == '' || fin == '') {
                alert('Primero elija las fechas');
            } else {
                window.open("/lab/get-finalizados-pdf/" + inicio + '/' + fin + '/' + elemento + '/' + cliente);
            }
        }

        function exportarAExcel() {
            var nombreArchivo = 'reporteMuestrasFinalizadas' + "_{{ $fecha_inicial }}" + '_AL_' + "{{ $fecha_final}}" + '.xls';
            var htmlExport = jQuery('#muestras-table').prop('outerHTML')
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