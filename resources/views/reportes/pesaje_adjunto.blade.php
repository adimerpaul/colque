<head>
    <title>{{$formularioLiquidacion->lote}}</title>
</head>
<div style="padding-left: 25px;">

<table style="width: 100%; margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
    <tr >
        <td style="width: 87%; padding-top: 20px">
            <b style="color: #042E44; font-size: 33px"> BOLETA DE PESAJE</b>
        </td>
        <td style="width: 20%; "> <img src="{{ 'logos/logo.png'}}" style="width: 140px; height: 68px; margin-right: 10px; float: right"></td>
    </tr>

</table>

<table style="width: 100%; margin-top: 25px; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 18px;" >
        <td style="width: 20%;"><b>PRODUCTOR:</b></td>
        <td style="width: 60%; border: #CFD8DC;">{{substr($formularioLiquidacion->cliente->cooperativa->razon_social, 0, 38)}}</td>
        <td rowspan="3" style="text-align: center"><img style="width: 12%" src="data:image/png;base64, {!! $qrcode !!}"></td>
    </tr>
    <tr style="font-size: 18px;" >
        <td style="width: 20%;"><b>CLIENTE:</b></td>
        <td style="width: 60%; border: #CFD8DC;">{{substr($formularioLiquidacion->cliente->nombre, 0, 38) }}</td>

    </tr>
    <tr style="font-size: 18px;" >
        <td style="width: 20%;"><b>CI/NIT:</b></td>
        <td style="width: 60%; border: #CFD8DC;">{{$formularioLiquidacion->cliente->nit}}</td>
    </tr>
</table>

<table style="width: 80%; margin-top: -1px; font-family: Arial, Helvetica, sans-serif; border: #CFD8DC; border:1px solid;">
    <tr style="font-size: 15px;" >
        <td style="width: 60%; text-align: center; border: #CFD8DC; border-right:1px solid;">LOTE</td>
        <td style="width: 20%; text-align: center">SACOS</td>
    </tr>
    <tr style="font-size: 24px; padding-top: -5px; margin-top: -5px; padding: -5px" >
        <td style="width: 60%; text-align: center; border: #CFD8DC; border-right:1px solid;"><b>{{substr( $formularioLiquidacion->lote,0, -3)}}</b></td>
        <td style="width: 20%; text-align: center"><b>{{$formularioLiquidacion->sacos }}</b></td>
    </tr>

</table>
<table style="width: 100%; margin-top: 1px; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 18px;" >
        <td style="width: 70%; text-align: center" colspan="2">CANTIDAD Y TARA</td>
    </tr>
    <tr style="font-size: 21px;" >
        <td style="width: 55%; text-align: right">PESO BRUTO HÚMEDO:</td>
        <td style="width: 45%; text-align: right">{{number_format($formularioLiquidacion->peso_bruto, 2) }} KG</td>
    </tr>
    <tr style="font-size: 21px; margin-top: -5px" >
        <td style="width: 55%; text-align: right">TARA:</td>
        <td style="width: 45%; text-align: right">{{number_format($formularioLiquidacion->tara, 2) }} KG</td>
    </tr>
    <tr style="font-size: 21px; margin-top: -15px" >
        <td style="width: 55%; text-align: right; margin-top: 5px"><b>PESO NETO HÚMEDO:</b></td>
        <td style="width: 45%; text-align: right; margin-top: 5px"><b>{{number_format($formularioLiquidacion->peso_neto, 2) }} KG</b></td>
    </tr>
    <tr style="font-size: 18px;" >
        <td style="width: 70%; border-top: #CFD8DC; border-top:1px solid; text-align: center" colspan="2">{{date( 'd/m/Y H:i', strtotime($formularioLiquidacion->created_at))}}</td>
    </tr>
</table>

</div>

<style>
    @page {
        margin: 40px 55px 30px 35px !important;
    }

    table {
        border-collapse:collapse;
        border-spacing: 0 1em;

    }


</style>

