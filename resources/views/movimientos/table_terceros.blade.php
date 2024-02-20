<div class="box-body">
    <div class="row">
        <div class="col-sm-12">
            {!! Form::open(['route' => 'movimientos.index', 'method'=>'get']) !!}
            <div class="form-group col-sm-3">
                {!! Form::label('txtBuscar', 'Buscar por:') !!}
                {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Código, glosa, proveedor']) !!}
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
                {!! Form::label('txtTipo', 'Tipo:') !!}
                {!! Form::select('txtTipo', \App\Patrones\Fachada::getTiposMovimientos(),isset($_GET['txtTipo']) ? $_GET['txtTipo'] : null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-sm-3" style="margin-top: 25px">
                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                    Buscar
                </button>
                <a title="Exportar a PDF" type="button" class="btn btn-info" target="_blank"
                   href="{{ route('movimientos.reporte') }}">
                    <i class="glyphicon glyphicon-print"></i> Reporte
                </a>

            </div>
            {!! Form::close() !!}
        </div>
    </div>

    @include('movimientos.table')
</div>
<div class="text-center">
    {{ $pagos->appends($_GET)->links()  }}
</div>

<script type="text/javascript">
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
