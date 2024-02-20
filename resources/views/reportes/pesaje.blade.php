<head>
    <title>{{$formularioLiquidacion->lote}}</title>
</head>

<table style="width: 100%; margin-top: 1px; font-family: Arial, Helvetica, sans-serif; text-align: center">
    <tr >
        <td >
            <b style="color: #042E44; font-size: 7px" > BOLETA DE PESAJE</b>
        </td>
    </tr>
</table>

<table style="width: 100%; margin-top: -2px; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 6px;" >
        <td style="width: 20%;"><b>PRODUCTOR:</b></td>
        <td style="width: 80%; border: #CFD8DC;">{{substr($formularioLiquidacion->cliente->cooperativa->razon_social, 0, 38)}}</td>
    </tr>
    <tr style="font-size: 6px;" >
        <td style="width: 20%;"><b>CLIENTE:</b></td>
        <td style="width: 80%;">{{substr($formularioLiquidacion->cliente->nombre, 0, 38) }}</td>
    </tr>
</table>

<table style="width: 80%; margin-top: 1px; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 4px;" >
        <td style="width: 60%; text-align: center; ">LOTE</td>
        <td style="width: 20%; text-align: center">SACOS</td>
    </tr>
    <tr style="font-size: 8px; padding-top: -5px; margin-top: -5px; padding: -5px" >
        <td style="width: 60%; text-align: center;"><b>{{substr( $formularioLiquidacion->lote,0, -3)}}</b></td>
        <td style="width: 20%; text-align: center"><b>{{$formularioLiquidacion->sacos }}</b></td>
    </tr>

</table>
<table style="width: 100%; margin-top: -2px; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 4px;" >
        <td style="width: 70%; text-align: center" colspan="2">CANTIDAD Y TARA</td>
    </tr>
    <tr style="font-size: 6px;" >
        <td style="width: 55%; text-align: right">PESO BRUTO HÚMEDO:</td>
        <td style="width: 45%; text-align: right">{{number_format($formularioLiquidacion->peso_bruto, 2) }} KG</td>
    </tr>
    <tr style="font-size: 6px; margin-top: -5px" >
        <td style="width: 55%; text-align: right">TARA:</td>
        <td style="width: 45%; text-align: right">{{number_format($formularioLiquidacion->tara, 2) }} KG</td>
    </tr>
    <tr style="font-size: 6px; margin-top: -16px" >
        <td style="width: 55%; text-align: right; margin-top: 5px"><b>PESO NETO HÚMEDO:</b></td>
        <td style="width: 45%; text-align: right; margin-top: 5px"><b>{{number_format($formularioLiquidacion->peso_neto, 2) }} KG</b></td>
    </tr>
    <tr style="font-size: 5px;" >
        <td style="width: 70%;text-align: center" colspan="2">{{'Colquechaca Mining '. date('d/m/Y H:i')}}</td>
    </tr>
</table>



<style>
    @page {
        margin: 2px 2px 0px 2px !important;
    }

    table {
        border-collapse:collapse;
        border-spacing: 0 1em;

    }


</style>

