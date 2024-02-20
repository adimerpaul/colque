<head>
    <title>Escala de precios Plomo Plata</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> ESCALA DE PRECIOS PB-AG</h2>
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
                <td style="color: #042e44"><b>Plomo (Pb)</b></td>
                <td>{{$diariaPb}}</td>
                <td>{{$oficialPb->monto . ' USD/' . $oficialPb->unidad}}</td>
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
                <td style="color: #042e44; text-align: center" colspan="11"><b>CONCENTRADO DE PLOMO Y PLATA</b></td>
            </tr>
            <tr style="font-size: 11px; background-color: #042e44; color:white">
                <td style="width: 80px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEY (Ag) </strong><br>
                    <strong>LEY (Pb)</strong></td>
                <td style="text-align: center;"><strong>5.00</strong></td>
                <td style="text-align: center;"><strong>10.00</strong></td>
                <td style="text-align: center;"><strong>15.00</strong></td>
                <td style="text-align: center;"><strong>20.00</strong></td>
                <td style="text-align: center;"><strong>25.00</strong></td>
                <td style="text-align: center;"><strong>30.00</strong></td>
                <td style="text-align: center;"><strong>35.00</strong></td>
                <td style="text-align: center;"><strong>40.00</strong></td>
                <td style="text-align: center;"><strong>45.00</strong></td>
                <td style="text-align: center;"><strong>50.00</strong></td>
            </tr>
            <tbody id="tabla">
            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>35.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb35 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb35 }}</td>
            </tr>
            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>40.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb40 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb40 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>45.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb45 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb45 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>50.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb50 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb50 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>55.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb55 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb55 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>60.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb60 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb60 }}</td>
            </tr>

            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>65.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb65 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb65 }}</td>
            </tr>
            <tr style="font-size: 11px; font-weight: normal; text-align: center">
                <td style="padding-left: 6px; background-color: #042e44; color:white"><strong>70.00</strong></td>
                <td style="padding-left: 6px"> {{ $ag5pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag10pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag15pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag20pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag25pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag30pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag35pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag40pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag45pb70 }}</td>
                <td style="padding-left: 6px"> {{ $ag50pb70 }}</td>
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

