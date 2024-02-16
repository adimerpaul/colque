<div style="margin: 2px; display: inline-block;">
    <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" align="right">
</div>
<div style="margin-top: 87px; text-align: center">
    <b style="font-size:20px; color: #042E44"><label>Reporte de Cancelación de Compras</label></b>
</div>
<br>

<b><label>Fecha Inicial: </label></b><label class="plomo">{{date('d/m/Y', strtotime($inicio))}}</label>
<br>
<b><label>Fecha Final: </label></b><label class="plomo">{{date('d/m/Y', strtotime($fin))}}</label>
<br>
<br>

<table border="1">
    <thead style="background-color: #042e44;">
    <tr>
        <th>#</th>
        <th>Número <br> de Lote</th>
        <th>Código <br> de Caja</th>
        <th>Fecha de <br> Cancelación</th>
        <th>Cliente <br>Productor</th>
        <th>Producto</th>
        <th>Saldo BOB</th>
    </tr>
    </thead>
    <tbody>
    @forelse($formularios as $formulario)
        <tr>
            <td class="plomo">{{$loop->iteration}}</td>
            <td class="plomo"> {{ $formulario->lote }}</td>
            <td class="plomo">{{ $formulario->codigo_caja }}</td>
            <td class="plomo">{{ date('d/m/y', strtotime($formulario->fecha_cancelacion)) }}</td>
            <td class="plomo">
                @if($formulario->cliente_id)
                    {!! $formulario->cliente->infoCliente !!}
                @endif
            </td>
            <td class="plomo">{{ $formulario->producto }}</td>
            <td class="plomo">{{ number_format($formulario->saldo_favor, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No existe información</td>
        </tr>
    @endforelse
    </tbody>

</table>
<div style="text-align:right">
    <b><label>Total Pagado: </label></b><label
        class="plomo">{{ number_format($formularios->sum('saldo_favor'), 2) }} BOB</label>
</div>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        color: #042E44;
        margin-bottom: 20px;
        font-family: Arial, Helvetica, sans-serif;
    }

    td {
        padding: 3px;
        padding-left: 5px;
        color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
    }

    th {
        padding: 3px;
        padding-left: 5px;
        color: white;
        font-size: 14px;
    }

    b {
        font-size: 14px;
    }

    img {
        width: 180px;
        height: 85px;
        margin-top: -10px
    }

    label {
        color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
    }

    .plomo {
        color: #647C84;
        border-color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }
</style>
