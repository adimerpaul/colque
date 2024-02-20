<head>
    <title>INFORME</title>
</head>
<div style="width: 30%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">
    <p><b style="font-size: 18px;">LOTES: CM{{$lote1}}E - CM{{$lote2}}E </b>
    </p>


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">

        <h2 style="margin-top: -12px; color: #042E44"> INFORME DE ENSAYOS</h2>
        <table style="width: 100%; margin-top: -5px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CLIENTE:</b></td>
                <td style="width: 45%">COOP. MIN. CHOCAYA ANIMAS R. L.</td>
                <td style="width: 20%"><b>FECHA INFORME</b></td>
                <td style="width: 15%; text-align: right">{{date('d/m/Y')}}</td>
            </tr>

        </table>
        <br>
        {{----}}

        {{----}}
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="5" style=" text-align: center"> RESULTADOS DE ENSAYO</th>
            </tr>

            </thead>
            <tr style="font-size: 11px; text-align: center; background-color: #ECEFF1">
                <td><strong>LOTE</strong></td>
                <td><strong>ELEMENTO</strong></td>

                <td style="width: 60%"><strong>MÉTODO</strong></td>
                <td><strong>RESULTADO</strong></td>
                <td><strong>UNIDAD</strong></td>
            </tr>
            @foreach($laboratorios as $lab)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{  $lab->formularioLiquidacion->lote }}</td>
                    <td style="padding-left: 6px"> {{ is_null($lab->mineral_id)? 'Humedad': $lab->mineral->nombre }}</td>

                    <td style="padding-left: 6px"> {{ is_null($lab->mineral_id)? 'DETERMINACIÓN DE HUMEDAD (LO-PE-04)': 'DETERMINACIÓN DE ESTAÑO POR VOLUMETRIA (LO-PE-03)' }}
                    </td>
                    <td style="padding-left: 6px"> {{ $lab->valor }}</td>
                    <td style="padding-left: 6px"> {{ $lab->unidad }}</td>
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
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="5" style=" text-align: center">OBSERVACIONES</th>
            </tr>

            </thead>
            <tr style="font-size: 11px; text-align: center">
                <td colspan="5"><br><br><br><br></td>

            </tr>
        </table>

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


</style>

