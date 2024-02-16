<head>
    <title>Egresos Caja</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logoLab.png'}}" style="width: 150px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> REPORTE DE EGRESOS DE CAJA</h2>
        <table style="width: 100%; margin-top: 10px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 18%"><b>FECHA INICIAL:</b></td>
                <td style="width: 65%">{{date('d/m/y', strtotime($fecha_inicial))}}</td>
                <td style="width: 15%"><b>FECHA FINAL:</b></td>
                <td style="width: 13%; text-align: right">{{date('d/m/y', strtotime($fecha_final))}}</td>
            </tr>

        </table>
        <br>
        {{----}}

        {{----}}
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">

            <tr style="font-size: 11px; text-align: center; background-color: #ECEFF1">
                <td><strong>#</strong></td>
                <td><strong>MONTO</strong></td>
                <td><strong>MÉTODO</strong></td>
                <td><strong>MONTO</strong></td>

            </tr>
            @foreach($pagos as $pago)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{  $loop->iteration }}</td>
                    <td style="padding-left: 6px"> {{ $pago->glosa }}</td>
                    <td style="padding-left: 6px"> {{ $pago->metodo }}</td>
                    <td style="padding-left: 6px"> {{ number_format($pago->sumatoria, 2) }}</td>
                </tr>

                </tbody>

            @endforeach

        </table>

        {{----}}




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

