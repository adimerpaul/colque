<head>
    <title>Temperaturas Humedades</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logoLab.png'}}" style="width: 150px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> REPORTE DE TEMPERATURAS Y HUMEDADES</h2>
        <table style="width: 100%; margin-top: 10px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 18%"><b>FECHA INICIAL:</b></td>
                <td style="width: 65%">{{date('d/m/y', strtotime($fecha_inicial))}}</td>
                <td style="width: 15%"><b>FECHA FINAL:</b></td>
                <td style="width: 13%; text-align: right">{{date('d/m/y', strtotime($fecha_final))}}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 15%"><b>AMBIENTE:</b></td>
                <td style="width: 65%">{{$ambiente}}</td>
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
                <td><strong>FECHA Y HORA</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>RANGO</strong></td>
                <td><strong>REPORTADO</strong></td>

            </tr>
            @foreach($temperaturas as $temperatura)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{  $loop->iteration }}</td>
                    <td style="padding-left: 6px"> {{ date('d/m/Y H:i', strtotime($temperatura->created_at)) }}</td>
                    <td style="padding-left: 6px"> {{ $temperatura->tipo }}</td>
                    <td style="padding-left: 6px"> {{ $temperatura->rangoMedicion->info }}</td>
                    <td style="padding-left: 6px"> {{ $temperatura->valor }}</td>
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

<?php
