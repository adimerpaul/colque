<head>
    <title>Escala de precios Estaño</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> ESCALA DE PRECIOS ESTAÑO</h2>
        <table style="width: 100%; margin-top: 10px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 18%; color: #042e44"><b>FECHA:</b></td>
                <td style="width: 65%">{{date('d/m/Y')}}</td>
                <td style="width: 15%; color: #042e44"><b>COTIZACIÓN:</b></td>
                <td style="width: 13%; text-align: right">{{$diaria}}</td>
            </tr>

        </table>
        <br>
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">

            <tr style="font-size: 11px; text-align: center; background-color: #042e44; color:white">
                <td><strong>LEY</strong></td>
                <td><strong>10.00%</strong></td>
                <td><strong>15.00%</strong></td>
                <td><strong>20.00%</strong></td>
                <td><strong>25.00%</strong></td>
                <td><strong>30.00%</strong></td>
                <td><strong>35.00%</strong></td>
                <td><strong>40.00%</strong></td>
                <td><strong>50.00%</strong></td>
                <td><strong>60.00%</strong></td>
                <td><strong>70.00%</strong></td>
            </tr>
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>USD/TON</strong></td>
                    <td style="padding-left: 6px"> {{ $valor10 }}</td>
                    <td style="padding-left: 6px"> {{ $valor15 }}</td>
                    <td style="padding-left: 6px"> {{ $valor20 }}</td>
                    <td style="padding-left: 6px"> {{ $valor25 }}</td>
                    <td style="padding-left: 6px"> {{ $valor30 }}</td>
                    <td style="padding-left: 6px"> {{ $valor35 }}</td>
                    <td style="padding-left: 6px"> {{ $valor40 }}</td>
                    <td style="padding-left: 6px"> {{ $valor50 }}</td>
                    <td style="padding-left: 6px"> {{ $valor60 }}</td>
                    <td style="padding-left: 6px"> {{ $valor70 }}</td>
                </tr>

                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>BOB/1K</strong></td>
                    <td style="padding-left: 6px"> {{ round((($valor10/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor15/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor20/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor25/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor30/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor35/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor40/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor50/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor60/1000)*6.86), 2) }}</td>
                    <td style="padding-left: 6px"> {{ round((($valor70/1000)*6.86), 2) }}</td>
                </tr>

                </tbody>


        </table>


    </div>

</div>

<style>
    @page {
        margin: 40px 55px 50px 35px !important;
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


</style>

