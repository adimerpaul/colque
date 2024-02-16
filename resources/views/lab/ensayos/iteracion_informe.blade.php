<head>
    <title>{{$pedido->codigo}}</title>
</head>
<div style="width: 30%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logoLab.png'}}" style="width: 150px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">
    <p><b style="font-size: 18px;">IDE: {{$pedido->codigo}}  </b>
    </p>


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">

        <h2 style="margin-top: -12px; color: #042E44"> INFORME DE ENSAYO</h2>
        <table style="width: 100%; margin-top: -5px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CLIENTE:</b></td>
                <td style="width: 45%">{{$pedido->cliente->nombre}}</td>
                <td style="width: 20%"><b>FECHA RECEPCIÓN:</b></td>
                <td style="width: 15%; text-align: right">{{date( 'd/m/Y H:i', strtotime($pedido->fecha_aceptacion))}} </td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CÓDIGO CLIENTE:</b></td>
                <td style="width: 45%">{{$pedido->cliente->nit}}</td>
                <td style="width: 20%"><b>FECHA ANÁLISIS:</b></td>
                <td style="width: 15%; text-align: right">{{ is_null($pedido->fecha_finalizacion)? date( 'd/m/Y H:i', strtotime($ensayos[0]->updated_at))  : date( 'd/m/Y H:i', strtotime($pedido->fecha_finalizacion))}}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CARACTERÍSTICAS:</b></td>
                <td style="width: 45%">{{$pedido->caracteristicas}}</td>
                <td style="width: 20%"><b>FECHA INFORME:</b></td>
                <td style="width: 15%; text-align: right"> {{  date( 'd/m/Y H:i')}}</td>
            </tr>


        </table>
        <br>
        {{----}}

        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="4" style=" text-align: center"> RESULTADOS DE ENSAYO</th>
            </tr>

            </thead>
            <tr style="font-size: 11px; text-align: center; background-color: #ECEFF1">
                <td><strong>LOTE</strong></td>
                <td><strong>ELEMENTO</strong></td>
                <td><strong>SÍMBOLO</strong></td>
                <td><strong>RESULTADO</strong></td>
            </tr>
            @for($j = 0; $j < 30; $j++)
                <tbody id="tabla">
                @if((($i - 1) * 30 +$j)<$ensayos->count())
                    <tr style="font-size: 11px; font-weight: normal; text-align: center">
                        <td style="padding-left: 6px"> {{ $ensayos[($i - 1) * 30 +$j]->lote }}</td>
                        <td style="padding-left: 6px"> {{ $ensayos[($i - 1) * 30 +$j]->elemento->nombre }}</td>
                        <td style="padding-left: 6px"> {{ $ensayos[($i - 1) * 30 +$j]->elemento->simbolo }}</td>
                        <td style="padding-left: 6px">
                            <strong> {{ number_format( ($ensayos[($i - 1) * 30 +$j]->resultado),2) . ' ' .$ensayos[($i - 1) * 30 +$j]->elemento->unidad}}</strong></td>
                    </tr>
                @endif


                </tbody>

            @endfor
            @for($k = 0; $k < (($i *30)-$ensayos->count()); $k++)
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td colspan="4">&nbsp;</td>
                </tr>
            @endfor

        </table>

        {{----}}
        <br>
        <table class="table table-bordered"
               style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
            <thead>
            <tr style="font-size: 12px; background-color: #ECEFF1">
                <th colspan="4" style=" text-align: center"> OBSERVACIONES</th>
            </tr>

            </thead>
            <tr style="font-size: 11px; text-align: center">
                <td colspan="4"><br><br><br><br></td>

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
            <label style="font-size: 15px;"> <strong> Para verificar la autenticidad del informe de ensayo escanea el código QR.</strong></label>
            <p style="font-size: 13px;">Los valores del presente informe de ensayo son resultados de procedimientos químicos efectuados según las normas técnicas adecuadas y políticas de Colquechaca Laboratory, calidad, excelencia, transparencia e innovación.
                Toda la información adicional es proporcionada por el cliente, las muestras serán almacenadas durante tres meses y queda prohibido la reproducción total o parcial de este documento.</p>
            <label style="font-size: 13px;"> <strong> Todo trabajo aceptado está sujeto a nuestros términos y condiciones.</strong></label>
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

    #parent {
        position: relative
    }


</style>

