<div>
<div class="row">
    <div class="col-sm-12">
        {!! Form::open(['route' => 'cuentas-cobrar-pendientes', 'method'=>'get']) !!}

        <div class="form-group col-sm-4">
            {!! Form::label('txtBuscar', 'Buscar por:') !!}
            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Buscar por glosa']) !!}
        </div>

        <div class="form-group col-sm-3">
            {!! Form::label('txtEstado', 'Tipo:') !!}
            {!! Form::select('txtEstado', ['%' => 'Todos'] + \App\Patrones\Fachada::getClasesCuentas(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-2" style="margin-top: 25px">
            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                Buscar
            </button>

        </div>
        <div class="form-group col-sm-3" style="margin-top: 25px">

            @if(\App\Patrones\Permiso::esComercial())
                <a class="btn btn-primary pull-right" style="margin-left: 2px"
                   href="{{ route('cuentas-cobrar-total') }}">
                    Historial de cuentas</a>
            @endif
            <button type="button" class="btn btn-success pull-right"
                    onclick="exportarAExcel()"><i
                    class="fa fa-file-excel-o"></i>
                Exportar
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="cuentas-table">
        <thead>
        <tr>
            <th colspan="7" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE DEUDAS
                <br>
                <b id="estadoDeuda"></b>
                <br>
            </th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Cliente</th>
            <th style=" border: 1px solid black;">Productor</th>
            <th style=" border: 1px solid black;">Glosa</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
            @if(\App\Patrones\Permiso::esComercial())

                <th style=" border: 1px solid black;"></th>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($cuentas->currentPage() - 1) * $cuentas->perPage();
        $row = 1;
        ?>
        @foreach($cuentas as $cuenta)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                {{--                                <td style=" border: 1px solid black;">{{ (($cuenta->id_inicio)) }}</td>--}}
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($cuenta->created_at)) }}</td>
                @if($cuenta->origen_type=='App\Models\FormularioLiquidacion')
                    <td style=" border: 1px solid black;">{!! $cuenta->origen->cliente->nombre !!}
                        <br><small class='text-muted'>{!! $cuenta->origen->cliente->nit !!}</small></td>
                    <td style=" border: 1px solid black;">{!! $cuenta->origen->cliente->cooperativa->razon_social !!}
                        <br><small class='text-muted'>En Lote: {!! $cuenta->origen->lote !!}</small>
                    </td>
                @else
                    <td style=" border: 1px solid black;">{!! $cuenta->origen->nombre !!}<br><small
                            class='text-muted'>{!! $cuenta->origen->nit !!}</small></td>
                    <td style=" border: 1px solid black;">{!! $cuenta->origen->cooperativa->razon_social !!}</td>
                @endif
                <td style=" border: 1px solid black;">{!! $cuenta->motivo !!}</td>
                <td style=" border: 1px solid black;">{{ number_format($cuenta->monto, 2) }}</td>
                @if(\App\Patrones\Permiso::esComercial())

                    <td style=" border: 1px solid black;">
                        @if($cuenta->origen_type=='App\Models\Cliente')
                            <div class='btn-group'>
                                <a class='btn btn-info btn-md' href="#"  data-toggle="modal" href="#"
                                   data-txtid="{{$cuenta->id}}"
                                   data-target="#modalCuenta">
                                    <i class="glyphicon glyphicon-usd"></i> Transferir
                                </a>
                            </div>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</div>
