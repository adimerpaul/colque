<head>
    <title>{{$ensayo->codigo_lab}}</title>
</head>
<div style="width: 30%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logo.png'}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">
    <p><b style="font-size: 18px;">IDE: {{$ensayo->codigo_lab}}</b>
    </p>


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">

        <h2 style="margin-top: -12px; color: #042E44"> INFORME DE ENSAYO</h2>
        <table style="width: 100%; margin-top: -5px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CLIENTE:</b></td>
                <td style="width: 45%">COLQUECHACA MINING</td>
                <td style="width: 20%"><b>FECHA RECEPCIÓN:</b></td>
                <td style="width: 15%; text-align: right">{{date( 'd/m/Y H:i', strtotime($ensayo->created_at))}} </td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CÓDIGO CLIENTE:</b></td>
                <td style="width: 45%">370883022</td>
                <td style="width: 20%"><b>FECHA MUESTREO:</b></td>
                <td style="width: 15%; text-align: right">{{isset($ensayo->formularioLiquidacion) ? date( 'd/m/Y H:i', strtotime($ensayo->formularioLiquidacion->created_at)): null}}  </td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>REF. CLIENTE:</b></td>
                <td style="width: 45%">{{$ensayo->formularioLiquidacion->lote}}</td>
                <td style="width: 20%"><b>FECHA ANÁLISIS:</b></td>
                <td style="width: 15%; text-align: right"> {{isset($ensayo->fecha_analisis) ? date( 'd/m/Y H:i', strtotime($ensayo->fecha_analisis)) :  null }}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>REF. LABORATORIO:</b></td>
                <td style="width: 45%">{{$ensayo->codigo_lab}}</td>
                <td style="width: 20%"><b>FECHA INFORME:</b></td>
                <td style="width: 15%; text-align: right"> {{isset($ensayo->fecha_finalizacion) ? date( 'd/m/Y H:i', strtotime($ensayo->fecha_finalizacion)) :  null }}</td>
            </tr>

        </table>
        <br>
        {{----}}
        <table class="table table-bordered"
               style="border:1px solid; font-size: 11px; border: #ECEFF1; border-collapse: collapse; width: 100%; ">
            <thead>
            <tr style="background-color: #ECEFF1; border: #ECEFF1; border:1px solid; text-align: center">
                <th style="text-align: center">1. CARACTERÍSTICAS DE MUESTRA</th>
                <th style="text-align: center">2. SERVICIO</th>
                <th style="text-align: center">3. PREPARACIÒN</th>
            </tr>

            </thead>
            <tbody>
            <tr style="font-size: 11px; vertical-align: top; ">
                <td style="width: 30%; border: #ECEFF1; border:1px solid; padding-left: 3px">
                    <p style="margin-top: 2px"><strong>SOBRE SELLADO: </strong> <label
                            style="float: right; margin-right: 3px"> {{ $ensayo->sobre_sellado ? 'SI' : 'NO' }}
                        </label><br></p>
                    <p style="margin-top: -8px">
                        <strong>MATERIAL SECO: </strong> <label
                            style="float: right; margin-right: 3px"> {{ $ensayo->es_seco ? 'SI' : 'NO' }}
                        </label><br></p>
                    <p style="margin-top: -8px"><strong>MATERIAL PULVERIZADO: </strong> <label
                            style="float: right; margin-right: 3px"> {{ $ensayo->es_pulverizado ? 'SI' : 'NO' }}
                        </label><br></p>
                    <p style="margin-top: -8px"><strong>MUESTRA GEOLÓGICA:&nbsp;&nbsp;
                        </strong>
                        <label
                            style="float: right; margin-right: 3px"> {{ $ensayo->es_geologica ? 'SI' : 'NO' }}
                        </label>

                        <br>
                    </p>

                </td>
                <td style="width: 25%; border: #ECEFF1; border:1px solid; padding-left: 3px; text-align: center">

                    <div style="margin-top: 2px">
                        <label>{{ $ensayo->servicio }}</label>
                    </div>
                </td>

                <td style="width: 25%; text-align: center">

                    <div style="margin-top: 2px">

                        <label>{{ $ensayo->preparacion }}</label>
                    </div>
                </td>


            </tr>
            </tbody>

        </table>
        {{----}}
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="5" style=" text-align: center"> 4. RESULTADOS DE ENSAYO</th>
            </tr>

            </thead>
            <tr style="font-size: 11px; text-align: center; background-color: #ECEFF1">
                <td><strong>ELEMENTO</strong></td>
                <td><strong>SÍMBOLO</strong></td>
                <td style="width: 60%"><strong>MÉTODO</strong></td>
                <td><strong>RESULTADO</strong></td>
                <td><strong>UNIDAD</strong></td>
            </tr>
            @foreach($laboratorios as $lab)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{ is_null($lab->mineral_id)? 'Humedad': $lab->mineral->nombre }}</td>
                    <td style="padding-left: 6px"> {{ is_null($lab->mineral_id)? 'H2O': $lab->mineral->simbolo }}</td>
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
                <th colspan="5" style=" text-align: center"> 5. OBSERVACIONES</th>
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

