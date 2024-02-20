<head>
    <title>Proforma</title>
</head>
<div style="width: 30%; float:right; text-align: right">
    @if(Auth::user())
        <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 170px; height: 75px;">
    @else
        <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">

    @endif
</div>
<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <p style="font-size: 19px;"><b>LOTE: PROFORMA</b></p>

    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <h2 style="margin-top: -10px; color: #042E44"> LIQUIDACIÓN DE MINERALES</h2>
        <table style="width: 100%; margin-top: -5px">
            <tr style="font-size: 13px; vertical-align: top;">
                <td style="width: 15%"><b>PRODUCTOR:</b></td>
                <td style="width: 60%">{{$nombre}}</td>
                <td style="width: 10%"><b>FECHA:</b></td>
                <td style="width: 10%; text-align: right"> {{ date( 'd/m/Y') }}</td>
            </tr>
        </table>
        <br>
        @include('cotizaciones_clientes.resumen')
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #CFD8DC; border-collapse: collapse; width: 100%; margin-top: 2px">
            <thead>
            <tr style="font-size: 13px; background-color: #CFD8DC">
                <th colspan="6" style=" text-align: center"> 5. VALORIZACIÓN</th>
            </tr>
            <tr style="font-size: 13px; background-color: #ECEFF1">
                <th colspan="5" style=" text-align: right">TOTAL VALOR BRUTO VENTA BOB:</th>
                <th style="text-align: right">
                    {{number_format($sumaBrutoVenta, 2)}}
                </th>
            </tr>
            </thead>
            <tr style="font-size: 11px; text-align: center">
                <td>ELEMENTO</td>
                <td>LEYES
                <td>PESO FINO KG</td>
                <td>COT. OFICIAL</td>
                <td>VALOR BRUTO VENTA</td>
                <td></td>
            </tr>
            @foreach($regalias as $row)
                <tbody id="tabla">
                <tr style="font-size: 12px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px">{{ $row['simbolo'] }}</td>
                    <td style="padding-left: 6px">{{ number_format($row['ley'], 2) }}</td>
                    <td style="padding-left: 6px">{{ number_format($row['peso_fino'], 2) }}</td>
                    <td style="padding-left: 6px">{{ number_format($row['cotizacion_oficial'] , 2) }}</td>
                    <td style="padding-left: 6px" id="valorBruto"
                        class="valorBruto">{{ number_format($row['valor_bruto_venta'], 2) }} BOB
                    </td>
                    <td></td>
                </tr>
                </tbody>
            @endforeach
        </table>
        <br>

        <table class="table table-bordered"
               style="border:1px solid; font-size: 12px;border: #CFD8DC; border-collapse: collapse; width: 100%; margin-top: -22px">
            <thead>
            <tr style="font-size: 12px; font-weight: bold; text-align: left; background-color: #ECEFF1">
                <td colspan="3" style=" text-align: right">VALOR POR TONELADA USD:</td>
                <td style=" text-align: right">{{number_format($valorPorTonelada,2)}}</td>

            </tr>
            <tr style="font-size: 12px; background-color: #CFD8DC;">
                <th colspan="3" style=" text-align: right">TOTAL VALOR NETO VENTA BOB:</th>
                <th style=" text-align: right">{{number_format($valorNetoVenta,2)}}</th>
            </tr>
            <tr style="font-size: 12px; background-color: #CFD8DC;">
                <th colspan="3" style=" text-align: right">RETENCIONES DE LEY Y DEDUCCIONES INSTITUCIONALES BOB:</th>
                <th style=" text-align: right">{{ number_format(($totalRegalias + $totalRetenciones), 2) }}</th>
            </tr>
            </thead>
            <tr style="font-size: 12px;" >
                <td colspan="4" style="padding-left: 3px"><b>RETENCIONES DE LEY</b></td>
            </tr>
            <tr style="font-size: 11px; font-weight: normal; text-align: left ">
                <td style="width: 55%; padding-left: 3px">REGALÍA MINERA</td>
                <td></td>
                <td class="text-right" style="width: 27%; text-align: right">{{ number_format($totalRegalias,2) }} BOB
                </td>
                <td></td>
            </tr>
            <tr style="font-size: 11px; font-weight: normal; text-align: left ">
                <td style="width: 55%; padding-left: 3px">TOTAL RETENCIONES Y DESCUENTOS</td>
                <td class="text-right"
                    style="width: 15%">{{ number_format($retenciones, 2) }} %
                </td>
                <td class="text-right" style="width: 27%; text-align: right">{{ number_format($totalRetenciones,2) }}
                    BOB
                </td>
                <td></td>
            </tr>
            <tr style="font-size: 12px; background-color: #CFD8DC;">
                <th colspan="3" style=" text-align: right">LIQUIDO PAGABLE BOB:</th>
                <th style=" text-align: right">{{ number_format($total, 2) }}</th>
            </tr>
        </table>

        <br>
        <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>

        <br><br><br><br>
        @if(Auth::user())

            <table style="width: 100%; text-align: center">
                <tr style="font-size: 14px; ">
                    <td style="width: 5%"></td>
                    <td class="center"><strong>CLIENTE<br>{{$nombre}}</strong></td>
                    <td class="center"><strong>RESPONSABLE COMERCIAL<br>{{ auth()->user()->personal->nombre_completo }}
                        </strong></td>
                </tr>
            </table>
        @endif

    </div>
    <div class="divBorrador">

    </div>
</div>
<style>
    @page {
        margin: 40px 55px 30px 35px !important;
    }

    th {
        text-align: start;
        padding-left: 6px;

    }

    .margen {
        padding-left: 20px;

    }

    #parent {
        position: relative
    }

    .divBorrador {
        background-image: url("https://i.ibb.co/7rxBbNn/borrador-proforma.png");
        /*background-repeat: no-repeat;*/
        width: 100%;
        height: 100%;
        position: absolute;
        top: 10px
    }

</style>

