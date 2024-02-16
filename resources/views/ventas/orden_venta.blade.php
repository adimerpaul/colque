<head>
    <title>{{$venta->codigo_odv}}</title>
</head>
<div style="display: inline-block;">
    <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" align="right">
</div>
<div style="margin-top: 87px; text-align: center">
    <b style="font-size:20px"><label>Orden de Venta</label></b>
</div>
<br>
<b><label>ODV Nº: </label></b><label class="plomo">{{$venta->numero_lote}}</label>
<br>
<b><label>Lote Vendedor: </label></b><label class="plomo">{{$venta->lote}}</label>
<br>

<b><label>Fecha de Entrega: </label></b><label class="plomo">{{date('d/m/Y')}}</label>
<br><br>

<table style="margin-top: -10px">
    <thead>
    <tr>
        <th style="background-color: #042e44; ">
            Vendedor
        </th>
        <th style="width: 25%;"></th>

        <th style="background-color: #042e44;">
            Comprador
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><b><label>Razón social: </label></b><label
                class="plomo">{{Auth::user()->personal->empresa->razon_social}}</label>
        </td>
        <td></td>

        <td>
            <b><label>Razón social: </label></b><label
                class="plomo">@if($venta->comprador) {{$venta->comprador->razon_social}}@endif</label>
        </td>

    </tr>
    <tr>
        <td><b><label>NIT: </label></b><label
                class="plomo">{{Auth::user()->personal->empresa->identificacion_tributaria}}</label>
        </td>
        <td></td>

        <td>
            <b><label>NIT: </label></b><label
                class="plomo">@if($venta->comprador){{$venta->comprador->nit}}@endif</label>
        </td>
    </tr>
    <tr>
        <td><b><label>Dirección: </label></b><label
                class="plomo">{{Auth::user()->personal->empresa->direccion}}</label>
        </td>
        <td></td>

        <td>
            <b><label>Dirección: </label></b><label
                class="plomo">@if($venta->comprador){{$venta->comprador->direccion}}@endif</label>
        </td>

    </tr>
    <tr>
        <td>
            <b><label>Teléfono: </label></b><label
                class="plomo">{{Auth::user()->personal->empresa->telefono}}</label>
        </td>
        <td></td>
        <th style="background-color: #042e44;">
            Transporte
        </th>
    </tr>
    <tr>
        <td>
            <b><label>Correo: </label></b><label
                class="plomo">{{Auth::user()->personal->empresa->email}}</label>
        </td>
        <td></td>
        <td>
            <b><label>Tipo: </label></b><label class="plomo">{{$venta->tipo_transporte}}</label>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>
            <b><label>Trayecto: </label></b><label class="plomo">{{$venta->trayecto}}</label>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>
            <b><label>Tranca: </label></b><label
                class="plomo">{{$venta->tranca}}</label>
        </td>
    </tr>
    </tbody>
</table>
<table border="1">
    <thead style="background-color: #042e44;">
    <tr>
        <th>Código</th>
        <th>Producto</th>
        @if($venta->letra==='C' OR $venta->letra === 'A')
            <th>Zn%</th>
        @endif
        @if($venta->letra==='A' OR $venta->letra === 'B' OR $venta->letra === 'C' OR $venta->letra === 'E')
            <th>Ag DM</th>
        @endif

        @if($venta->letra==='B' OR $venta->letra === 'C')
            <th>Pb%</th>
        @endif

        @if($venta->letra==='D')
            <th>Sn%</th>
        @endif
        @if($venta->letra==='F')
            <th>Sb%</th>
            <th>Au G/T</th>

        @endif
        @if($venta->letra==='G')
            <th>Cu%</th>
        @endif

    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="plomo">{{ $venta->letra }}</td>
        <td class="plomo">@if($venta->letra!='C') Concentrado de @endif{{ substr($venta->producto, 4) }}</td>
        @if($venta->letra==='D')
            <td class="plomo">{{ number_format($venta->suma_ley_sn, 2) }}</td>
        @endif
        @if($venta->letra==='C' OR $venta->letra === 'A')
            <td class="plomo">{{ number_format($venta->suma_ley_zn, 2) }}</td>
        @endif

        @if($venta->letra==='A' OR $venta->letra === 'B' OR $venta->letra === 'C' OR $venta->letra === 'E')
            <td class="plomo">{{ number_format(($venta->suma_ley_ag/100), 2) }}</td>
        @endif

        @if($venta->letra==='B' OR $venta->letra === 'C')
            <td class="plomo">{{ number_format($venta->suma_ley_pb, 2) }}</td>
        @endif
        @if($venta->letra==='F')
            <td class="plomo">{{ number_format($venta->suma_ley_sb, 2) }}</td>
            <td class="plomo">{{ number_format($venta->suma_ley_au, 2) }}</td>
        @endif
        @if($venta->letra==='G')
            <td class="plomo">{{ number_format($venta->suma_ley_cu, 2) }}</td>
        @endif
    </tr>

    </tbody>
</table>

<table border="1">
    <thead style="background-color: #042e44;">
    <tr>
        <th>Placa</th>
        <th>Conductor</th>
        <th>ID</th>
        <th>PBH (Kg)</th>
        <th>Tara (Kg)</th>
        <th>PNH (Kg)</th>
        @if($venta->letra!='D')
            <th>H2O (%)</th>
            <th>PNS (Kg)</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @forelse($pesajes as $pesaje)
        <tr>
            <td class="plomo">{{ $pesaje->vehiculo->placa }}</td>
            <td class="plomo">{{ $pesaje->chofer->nombre }}</td>
            <td class="plomo">{{ $pesaje->chofer->licencia }}</td>
            <td class="plomo">{{ number_format($pesaje->peso_bruto_humedo, 2) }}</td>
            <td class="plomo">{{ number_format($pesaje->tara, 2) }}</td>
            <td class="plomo">{{ number_format($pesaje->peso_neto_humedo, 2) }}</td>
            @if($venta->letra!='D')
                <td class="plomo">{{ number_format($pesaje->venta->humedad_compras, 2) }}</td>
                <td class="plomo">{{ number_format($pesaje->peso_neto_seco, 2) }}</td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">No existe información</td>
        </tr>
    @endforelse
    </tbody>

</table>
<div style="text-align:right">
    <b><label>PBH Total: </label></b><label
        class="plomo">{{ number_format($pesajes->sum('peso_bruto_humedo'), 2) }} Kg</label>
    <br>
    <b><label>PNH Total: </label></b><label
        class="plomo">{{ number_format($pesajes->sum('peso_neto_humedo'), 2) }} Kg</label>
    <br>
    @if($venta->letra!=='D')  <b><label>PNS Total: </label></b><label
        class="plomo">{{ number_format($pesajes->sum('peso_neto_seco'), 2) }} Kg</label>
    @endif
</div>
</br>

<table style="width: 75%; margin-top: -30px">
    <thead>
    <tr>
        <th style="background-color: #042e44; ">
            Notas e Instrucciones
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><b><label>Empaque: </label></b><label
                class="plomo">{{$venta->empaque}}</label>
        </td>
    </tr>
    <tr>
        <td><b><label>Municipio: </label></b><label
                class="plomo">{{$venta->municipio}}</label>
        </td>
    </tr>
    <tr>
        <td><b><label>Contacto: </label></b><label
                class="plomo">{{Auth::user()->personal->nombre_completo}}</label>
        </td>
    </tr>
    <tr>
        <td><b><label>Celular: </label></b><label
                class="plomo">{{Auth::user()->personal->celular}}</label>
        </td>
    </tr>
    <tr>
        <td><b><label>Otros: </label></b><label
                class="plomo">{{Auth::user()->email}}</label>
        </td>
    </tr>

    </tbody>
</table>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        color: #042E44;
        margin-top: 15px;
        font-family: Arial, Helvetica, sans-serif;
    }

    td {
        padding: 3px;
        padding-left: 5px;
        color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
    }

    th {
        padding: 3px;
        padding-left: 5px;
        color: white;
        font-size: 13px;
    }

    b {
        font-size: 13px;
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
        font-size: 13px;
    }
</style>
