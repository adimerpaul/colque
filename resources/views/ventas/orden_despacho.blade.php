<head>
    <title>{{$venta->codigo_odd}}</title>
</head>
<div style="margin: 2px; display: inline-block;">
    <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" align="right">
</div>
<div style="margin-top: 87px; text-align: center">
    <b style="font-size:20px; color: #042E44"><label>Orden de Despacho</label></b>
</div>
<br>
<b><label>ODD Nº: </label></b><label class="plomo">{{$venta->codigo_odd}}</label>
<br>
<b><label>Fecha de Entrega: </label></b><label class="plomo">{{date('d/m/Y')}}</label>
<br>
<b><label>Código: </label></b><label class="plomo">{{$venta->letra}}</label>
<br>
<b><label>Producto: </label></b><label
    class="plomo">Concentrado de {{ substr($venta->producto, 4) }}</label>

<br><br>

<table border="1">
    <thead style="background-color: #042e44;">
    <tr>
        <th>N°</th>
        <th>Lote</th>
        <th>PBH (Kg)</th>
        <th>PNH (Kg)</th>
        <th>PNS (Kg)</th>
        <th>PDV (Kg)</th>
        @if($venta->letra == 'A' OR $venta->letra == 'C')
            <th>Zn%</th>
        @endif
        @if($venta->letra == 'B' OR $venta->letra == 'C')
            <th>Pb%</th>
        @endif
        @if($venta->letra == 'A' OR $venta->letra == 'B' OR $venta->letra == 'C' OR $venta->letra == 'E')
            <th>Ag DM</th>
        @endif
        @if($venta->letra == 'D')
            <th>Sn%</th>
            <th>Sacos</th>
        @endif
        @if($venta->letra == 'F')
            <th>Sb%</th>
            <th>Au G/T</th>
        @endif
        @if($venta->letra == 'G')
            <th>Cu%</th>
        @endif
    </tr>
    </thead>
    <tbody>
    <?php

    $row = 1;
    ?>
    @forelse($formularios as $formulario)
        <tr>
            <td class="plomo">{{ ($row++)}}</td>
            <td class="plomo">{{ $formulario->lote }}</td>
            <td class="plomo">{{ number_format($formulario->peso_bruto, 2) }}</td>
            <td class="plomo">{{ number_format($formulario->peso_neto, 2) }}</td>
            <td class="plomo">{{ number_format($formulario->peso_neto_seco, 2) }}</td>
            <td class="plomo"></td>
            @if($venta->letra == 'A' OR $venta->letra == 'C')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn'){{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
            @endif
            @if($venta->letra == 'B' OR $venta->letra == 'C')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb') {{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
            @endif
            @if($venta->letra == 'A' OR $venta->letra == 'B' OR $venta->letra == 'C' OR $venta->letra == 'E')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
            @endif

            @if($venta->letra == 'D')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sn'){{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
                <td class="plomo">{{$formulario->sacos}}
                </td>
            @endif
            @if($venta->letra == 'F')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sb') {{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Au') {{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
            @endif
            @if($venta->letra == 'G')
                <td class="plomo">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Cu'){{number_format($lab->promedio,2)}} @endif
                    @endforeach
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No existe información</td>
        </tr>
    @endforelse
    @foreach($ingenios as $ingenio)
        <tr>
            <td class="plomo">{{($row++)}}</td>
            <td class="plomo">
                @if(is_null($ingenio->ingenio_id))
                    {{  $ingenio->nombre }}
                @else
                    {{ 'INGENIO: ' .$ingenio->origen_ingenio->lote }}
                @endif
            </td>
            <td class="plomo">{{ number_format($ingenio->peso_neto_humedo,2, ',', '') }}</td>

            <td class="plomo">{{ number_format($ingenio->peso_neto_seco,2, ',', '') }}</td>
            <td class="plomo"></td>
            @if($venta->letra == 'A' OR $venta->letra == 'C')
                <td  class="plomo">@if(round($ingenio->ley_zn,2)!=0){{ number_format($ingenio->ley_zn,2, ',', '') }}@endif</td>
            @endif
            @if($venta->letra == 'B' OR $venta->letra == 'C')
                <td  class="plomo">@if(round($ingenio->ley_pb,2)!=0){{ number_format($ingenio->ley_pb,2, ',', '') }}@endif</td>
            @endif
            @if($venta->letra == 'A' OR $venta->letra == 'B' OR $venta->letra == 'C' OR $venta->letra == 'E')
                <td class="plomo">{{ number_format($ingenio->ley_ag,2, ',', '') }}</td>
            @endif
            @if($venta->letra == 'D')
                <td class="plomo">{{ number_format($ingenio->ley_sn,2, ',', '') }}</td>
            @endif
            @if($venta->letra == 'F')
                <td class="plomo">{{ number_format($ingenio->ley_sb,2, ',', '') }}</td>
                <td class="plomo">{{ number_format($ingenio->ley_au,2, ',', '') }}</td>
            @endif
            @if($venta->letra == 'G')
                <td class="plomo">{{ number_format($ingenio->ley_cu,2, ',', '') }}</td>
            @endif
        </tr>
    @endforeach
    </tbody>

</table>
<div style="text-align:right">
    <b><label>PNH Total: </label></b><label
        class="plomo">{{ number_format(($formularios->sum('peso_neto')+$ingenios->sum('peso_neto_humedo')), 2) }} Kg</label>
    <br>
    <b><label>PNS Total: </label></b><label
        class="plomo">{{ number_format(($formularios->sum('peso_neto_seco')+$ingenios->sum('peso_neto_seco')), 2) }} Kg</label>
</div>
</br>
<table style="width: 70%; margin-top: -30px">
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
        <td><b><label>H2O: </label></b><label
                class="plomo">{{number_format(
    ((($formularios->sum('humedad_kilo')+ $ingenios->sum('humedad_kg')) /
                ($formularios->sum('peso_neto')+$ingenios->sum('peso_neto_humedo'))) * 100), 2)
    }} %</label>
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
        <td><b><label>Otros: </label></b></td>
    </tr>

    </tbody>
</table>

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
