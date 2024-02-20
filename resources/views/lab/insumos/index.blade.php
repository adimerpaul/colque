@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Insumos </h1>
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
                    <div class="form-group col-sm-6" style="margin-top: 25px">

                        <button type="button" class="btn btn-danger" onclick="exportarAPdf()" style="background-color: #ae1c17" title="Exportar Pdf"><i class="fa fa-file-pdf-o"></i>

                        </button>
                        <button type="button" class="btn btn-success" onclick="exportarAExcel()" title="Exportar Excel"><i class="fa fa-file-excel-o"></i>

                        </button>

                    </div>
                </div>
                <div class="table-responsive">
                    @include('lab.insumos.table')
                </div>

            </div>
        </div>
        <div class="text-center">
            {{ $insumos->appends($_GET)->links()  }}
        </div>
    </div>
    {!! Form::open(['route' => 'insumos.store', 'id' => 'formularioModal']) !!}
    @include("lab.insumos.modal_registro")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-insumo', 'id' => 'formularioModalEdit']) !!}
    @include("lab.insumos.modal_edicion")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-inventario-lab', 'id' => 'formularioModalCantidad']) !!}
    @include("lab.insumos.modal_cantidad_actual")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'actualizar-inventario-lab', 'id' => 'formularioModalIngreso']) !!}
    @include("lab.insumos.modal_ingreso")
    {!! Form::close() !!}
@endsection

@push('scripts')

    <script>
        function exportarAPdf() {
                window.open("/lab/get-insumos-pdf");
        }

        function exportarAExcel() {
            var nombreArchivo = 'reporteStockInsumos.xls';
            var htmlExport = jQuery('#insumos-table').prop('outerHTML')
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

        $('#modalEdicion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')
            var unidad = button.data('txtunidad')
            var cantidad = button.data('txtcantidad')

            var modal = $(this)
            modal.find('.modal-body #idInsumo').val(id);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #unidad').val(unidad);
            modal.find('.modal-body #cantidad_minima').val(cantidad);
        })

        $('#modalIngreso').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')

            var modal = $(this)
            modal.find('.modal-body #idInsumo').val(id);
            modal.find('.modal-body #nombre').val(nombre);
        })

        $('#modalCantidadActual').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')

            var modal = $(this)
            modal.find('.modal-body #idInsumo').val(id);
            modal.find('.modal-body #nombre').val(nombre);
        })

    </script>

@endpush
