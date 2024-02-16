@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Accidentes</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="#" data-target="#modalRegistro"
               data-toggle="modal">Agregar nuevo</a>
        </h1>
        <br>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'accidentes.index', 'method'=>'get']) !!}

                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>

                        <div class="form-group col-sm-6" style="margin-top: 25px">
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
                @include('lab.accidentes.table')
            </div>
        </div>
        <div class="text-center">
            {{ $accidentes->appends($_GET)->links() }}
        </div>
    </div>
    {!! Form::open(['route' => 'accidentes.store', 'id' => 'formularioModal']) !!}
    @include("lab.accidentes.modal_registro")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-accidente', 'id' => 'formularioModalEdit']) !!}
    @include("lab.accidentes.modal_edicion")
    {!! Form::close() !!}
@endsection


@push('scripts')

    <script>

        $('#modalEdicion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var descripcion = button.data('txtdescripcion')
            var tipo = button.data('txttipo')
            var fecha = button.data('txtfecha')
            var hora = button.data('txthora')

            var modal = $(this)
            modal.find('.modal-body #idAccidente').val(id);
            modal.find('.modal-body #descripcion').val(descripcion);
            modal.find('.modal-body #tipo').val(tipo);
            modal.find('.modal-body #fecha').val(fecha);
            modal.find('.modal-body #hora').val(hora);
        })

        function exportarAPdf() {
            var inicio = document.getElementById("fecha_inicial").value;
            var fin = document.getElementById("fecha_final").value;

            if (inicio == '' || fin == '') {
                alert('Primero elija las fechas');
            } else {
                window.open("/lab/get-accidentes-pdf/" + inicio + '/' + fin);
            }
        }

        function exportarAExcel() {
            var nombreArchivo = 'reporteAccidentes'+ "_{{ $fecha_inicial }}" + '_AL_' + "{{ $fecha_final}}" + '.xls';
            var htmlExport = jQuery('#egresos-table').prop('outerHTML')
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

@endpush


