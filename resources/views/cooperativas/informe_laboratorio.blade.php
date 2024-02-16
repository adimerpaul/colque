<head>
    <title>INFORME</title>
</head>
<div style="width: 30%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logoLab.png'}}" style="width: 165px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
        <br><br>
        <h2 style="margin-top: 1px; color: #042E44"> INFORME DE ENSAYOS</h2>
        <table style="width: 100%; margin-top: 10px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 18%"><b>CLIENTE:</b></td>
                <td style="width: 65%">{{$cooperativa->razon_social}}</td>
                <td style="width: 15%"><b>ELEMENTO:</b></td>
                <td style="width: 13%; text-align: right">{{$elemento}}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 18%"><b>FECHA INICIAL:</b></td>
                <td style="width: 65%">{{date('d/m/y', strtotime($inicio))}}</td>
                <td style="width: 15%"><b>FECHA FINAL:</b></td>
                <td style="width: 13%; text-align: right">{{date('d/m/y', strtotime($fin))}}</td>
            </tr>



        </table>
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="3" style=" text-align: center"> RESULTADOS DE ENSAYO</th>
            </tr>
            <tr style="font-size: 11px; text-align: center; background-color: #ECEFF1">
                <td><strong>#</strong></td>
                <td><strong>LOTE</strong></td>
                <td><strong>RESULTADO</strong></td>
            </tr>
            </thead>

            @foreach($laboratorios as $lab)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{  $loop->iteration }}</td>
                    <td style="padding-left: 6px"> {{  $lab->formularioLiquidacion->lote }}</td>
                    <td style="padding-left: 6px"> {{ $lab->valor. ' '. $lab->unidad }}</td>
                </tr>

                </tbody>

            @endforeach
            @for($i = 0; $i < (6 - $laboratorios->count()); $i++)
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            @endfor
        </table>

        {{----}}
        <br>
       <br>

        {{--        --}}
        <table
            style="width: 100%; text-align: center; margin-top: -2px; border:1px solid; border: #ECEFF1; border-collapse: collapse;">
            <tr style="font-size: 11px; ">
                <td style="width: 20%">
                    <img style="width: 70%" src="data:image/png;base64, {!! $qrcode !!}">
                </td>
                <td class="center"
                    style="max-width: 70%; border:1px solid; border: #ECEFF1; border-collapse: collapse;">
                    <div style="height:140px; width: 100%; overflow:hidden">
                        <br>
                        <div style=" float:right; color: transparent; height:80px; width: 100%">
                            <img src="{{ 'firmas/1687893472.PNG'}}" alt="."
                                 style="height: 100%; width: 25%">
                        </div>
                        <br><br><br><br>
                        JEFE DE LABORATORIO <br>BRENDA SHIRLEY CALLEJAS HUANCA
                    </div>

                </td>
            </tr>
        </table>

        <div style="text-align: center">
            <br>
            <label style="font-size: 14px;"> <strong> PARA VERIFICAR LA AUTENTICIDAD DEL INFORME DE ENSAYO, ESCANEE
                    EL
                    CÓDIGO QR.</strong></label>
            <p style="font-size: 11px;">LOS VALORES DEL PRESENTE INFORME DE ENSAYO SON RESULTADOS DE ANÁLISIS
                QUÍMICOS EFECTUADOS SEGÚN NORMAS TÉCNICAS ADECUADAS Y SE REFIEREN ÚNICAMENTE A LAS MUESTRAS
                ENSAYADAS DE ACUERDO A LAS CONDICIONES DE TRABAJO DEL LABORATORIO: CL-P-01, TODA INFORMACIÓN
                ADICIONAL ES PROPORCIONADA POR EL CLIENTE. LAS MUESTRAS SERÁN RESGUARDADAS POR UN LAPSO DE TRES
                MESES. NO SE DEBE REPRODUCIR EL INFORME DE ENSAYO, EXCEPTO EN SU TOTALIDAD, SIN LA APROBACIÓN DE
                COLQUECHACA LAB. PARA VERIFICAR LA AUTENTICIDAD DEL DOCUMENTO ESCANEE EL CÓDIGO QR.</p>
            <label style="font-size: 11px;"> <strong> TODO TRABAJO ACEPTADO, ESTA SUJETO A NUESTROS TÉRMINOS Y
                    CONDICIONES</strong></label>
        </div>
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

