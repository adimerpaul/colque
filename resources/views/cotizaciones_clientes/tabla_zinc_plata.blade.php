<head>
    <title>Escala de precios Plomo Plata</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> ESCALA DE PRECIOS ZN-AG</h2>
        <table style="width: 100%; margin-top: 10px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44" colspan="4"><b>1. COTIZACIÓN</b></td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44"><b>ELEMENTO</b></td>
                <td style="color: #042e44"><b>COT. DIARIA</b></td>
                <td style="color: #042e44"><b>COT. OFICIAL</b></td>
                <td style="color: #042e44"><b>TIPO DE CAMBIO</b></td>
            </tr>

            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44"><b>Zinc (Zn)</b></td>
                <td>{{$diariaZn}}</td>
                <td>{{$oficialZn->monto . ' USD/' . $oficialZn->unidad}}</td>
                <td><b style="color: #042e44">Oficial:</b> 6.96</td>
            </tr>

            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44"><b>Plata (Ag)</b></td>
                <td>{{$diariaAg}}</td>
                <td>{{$oficialAg->monto . ' USD/' . $oficialAg->unidad}}</td>
                <td><b style="color: #042e44">Comercial:</b> 6.86</td>
            </tr>
            <tr><td></td></tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td><b style="color: #042e44">Fecha: </b>{{date('d/m/y')}}</td>
                <td style="color: #042e44"></td>
                <td style="color: #042e44"></td>
                <td><b style="color: #042e44">Transporte: </b>30</td>
            </tr>

        </table>
        <br>
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44" colspan="11"><b>2. TABLA DE PRECIOS</b></td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="color: #042e44; text-align: center" colspan="10"><b>CONCENTRADO DE ZINC Y PLATA</b></td>
            </tr>
            <tr style="font-size: 11px; background-color: #042e44; color:white">
                <td style="width: 80px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEY (Ag) </strong><br>
                    <strong>LEY (Zn)</strong></td>
                <td style="text-align: center;"><strong>2.00</strong></td>
                <td style="text-align: center;"><strong>3.00</strong></td>
                <td style="text-align: center;"><strong>4.00</strong></td>
                <td style="text-align: center;"><strong>5.00</strong></td>
                <td style="text-align: center;"><strong>6.00</strong></td>
                <td style="text-align: center;"><strong>7.00</strong></td>
                <td style="text-align: center;"><strong>8.00</strong></td>
                <td style="text-align: center;"><strong>9.00</strong></td>
                <td style="text-align: center;"><strong>10.00</strong></td>
            </tr>
            <tbody id="tabla">
            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>35.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn35 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn35 }}</td>
            </tr>
            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>40.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn40 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn40 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>45.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn45 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn45 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>50.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn50 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn50 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>55.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn55 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn55 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>60.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn60 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn60 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>65.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag2zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag3zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag4zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag5zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag6zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag7zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag8zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag9zn65 }}</td>
                <td style="padding-left: 6px"> {{ $ag10zn65 }}</td>
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

