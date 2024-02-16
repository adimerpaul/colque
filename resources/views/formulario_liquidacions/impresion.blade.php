<head>
    <title>{{$formularioLiquidacion->lote}}</title>
</head>
<div style="width: 30%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 170px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">
    <p><b style="font-size: 18px;">LOTE: {{substr( $formularioLiquidacion->lote,0, -3)}}</b>
        @if($formularioLiquidacion->codigo_caja!='')
            <br>
            <label style="font-size:11px">
                COMPROBANTE:
                {{$formularioLiquidacion->codigo_caja}}
            </label>
        @endif
    </p>


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">

        <h2 style="margin-top: -12px; color: #042E44"> LIQUIDACIÓN DE MINERALES</h2>
        <table style="width: 100%; margin-top: -19px">
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>PRODUCTOR:</b></td>
                <td style="width: 50%">{{$formularioLiquidacion->cliente->cooperativa->razon_social}}</td>
                <td style="width: 20%"><b>FECHA RECEPCIÓN:</b></td>
                <td style="width: 10%; text-align: right"> {{ isset($formularioLiquidacion->created_at) ? date( 'd/m/Y', strtotime($formularioLiquidacion->created_at)) :  null }}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CLIENTE:</b></td>
                <td style="width: 50%">
                    @if(is_null($nombre))
                        {{isset($formularioLiquidacion->cliente->nombre) ? $formularioLiquidacion->cliente->nombre :  null}}
                    @else
                        {{$nombre}}
                    @endif
                </td>
                <td style="width: 20%"><b>FECHA LIQUIDACIÓN:</b></td>
                <td style="width: 10%; text-align: right"> {{ isset($formularioLiquidacion->fecha_liquidacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_liquidacion)) :  null }}</td>
            </tr>
            <tr style="font-size: 12px; vertical-align: top;">
                <td style="width: 20%"><b>CÓDIGO CLIENTE:</b></td>
                <td style="width: 50%">{{$formularioLiquidacion->cliente->nit}}</td>
                <td style="width: 20%"><b>FECHA COTIZACIÓN:</b></td>
                <td style="width: 10%; text-align: right"> {{ isset($formularioLiquidacion->fecha_cotizacion) ? date( 'd/m/Y', strtotime($formularioLiquidacion->fecha_cotizacion)) :  null }}</td>
            </tr>
        </table>
        <br>
        @if($anticipos->count()<2 and $formularioLiquidacion->total_prestamos==0 and $bonos->count()==0)
            <br>
        @endif

        @include('formulario_liquidacions.impresion.resumen')


        @include('formulario_liquidacions.impresion.retenciones')

        <table style="width: 100%; border:1px solid; margin-top: -1px; border: #CFD8DC; border-collapse: collapse;">
            <tr style="font-size: 12px; text-align: left;">
                <td colspan="4" style="padding-top: 3px; padding-bottom: 3px">
                    <strong>SON:</strong> {{$literal}}.
                </td>
            </tr>
        </table>

        <table
            style="width: 100%; text-align: center; margin-top: -2px; border:1px solid; border: #CFD8DC; border-collapse: collapse;">
            <tr style="font-size: 11px; ">
                <td style="width: 12%">
                    <img style="width: 90%" src="data:image/png;base64, {!! $qrcode !!}">
                </td>
                <td class="center"
                    style="max-width: 25%; border:1px solid; border: #CFD8DC; border-collapse: collapse;">
                    <div style="height:140px; width: 100%; overflow:hidden">
                        <br><br>
                        <div style=" float:right; color: transparent; height:80px; width: 100%">

                                @if((!\App\Patrones\Permiso::esSoloCaja() || $formularioLiquidacion->cliente->cooperativa_id==44) && !is_null($formularioLiquidacion->cliente->firma) && ($formularioLiquidacion->cliente->firma)!='blanco.png')
                                    @if($formularioLiquidacion->cliente->cooperativa_id==44 and $formularioLiquidacion->fecha_liquidacion< '2023-11-06')
                                        <img src="{{ 'firmas/1676485974.jpg'}}" alt="."
                                         style="height: 100%; width: 25%">
                                    @else
                                        <img src="{{ 'firmas/'.$formularioLiquidacion->cliente->firma}}" alt="."
                                         style="height: 100%; width: 25%">
                                    @endif

                                @else
                                    <img src="{{ 'images/fondo.png'}}" alt="."
                                         style="height: 100%; width: 25%">
                                @endif
                        </div>
                        <br><br><br><br>CLIENTE
                        <br>@if(is_null($nombre))
                            {{isset($formularioLiquidacion->cliente->nombre) ? $formularioLiquidacion->cliente->nombre :  null}}
                        @else
                            {{$nombre}}
                        @endif
                    </div>
                </td>
                <td class="center"
                    style="max-width: 25%; border:1px solid; border: #CFD8DC; border-collapse: collapse;">
                    <div style="height:140px;  width: 100%; overflow:hidden">
                        <br><br>
                        <div style=" float:right; color: transparent; height:80px; width: 100%">
                            @if(!is_null($formularioLiquidacion->usuario_liquidacion->personal->firma) && ($formularioLiquidacion->usuario_liquidacion->personal->firma)!='blanco.png')
                                <img src="{{ 'firmas/'.$formularioLiquidacion->usuario_liquidacion->personal->firma}}"
                                     alt="."
                                     style="height: 100%; width: 25%">
                            @else
                                <img src="{{ 'images/fondo.png'}}" alt="."
                                     style="height: 100%; width: 25%">
                            @endif
                        </div>

                        <br><br><br><br>RESPONSABLE COMERCIAL<br>
                        @if(!is_null($formularioLiquidacion->usuario_liquidacion->personal->nombre_completo))
                            {{ $formularioLiquidacion->usuario_liquidacion->personal->nombre_completo}}
                        @else
                            {{ auth()->user()->personal->nombre_completo }}
                        @endif
                    </div>
                </td>
                <td class="center" style="width: 38%; border:1px solid; border: #CFD8DC; border-collapse: collapse;">
                    <div style="height:143px;  width: 100%; overflow:hidden">
                        <br><br>
                        <div style=" float:right; color: transparent; height:106px; width: 100%; margin-top: -20px">
                            @if(!\App\Patrones\Permiso::esSoloCaja())
                                @if(!is_null($formularioLiquidacion->usuario_cancelacion) and $formularioLiquidacion->es_cancelado)
                                    <img
                                        src="{{ 'firmas/'.$formularioLiquidacion->usuario_cancelacion->personal->firma}}"
                                        alt="."
                                        style="height: 100%; width: 90%">
                                @else
                                    <img src="{{ 'images/fondo.png'}}" alt="."
                                         style="height: 100%; width: 25%">
                                @endif
                            @endif
                        </div>

                        <br><br><br><br>RESPONSABLE DE CAJA<br>
                        @if(!is_null($formularioLiquidacion->usuario_cancelacion) and $formularioLiquidacion->es_cancelado)
                            {{$formularioLiquidacion->usuario_cancelacion->personal->nombre_completo}}
                        @else

                            VICTOR PEÑA AFINO
                        @endif

                    </div>
                    {{--                    <div style="height:140px; overflow:hidden; padding-top: 10px">--}}
                    {{--                        <br><br><br><br><br><br><br><br>RESPONSABLE DE CAJA<br>VICTOR PEÑA AFINO--}}
                    {{--                    </div>--}}

                </td>
            </tr>

        </table>

    </div>
    @if($formularioLiquidacion->estado=='Anulado')
        <div class="divAnulado">

        </div>
    @endif
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

    .divAnulado {
        background-image: url("https://i.ibb.co/Z6pMSbj/form-Anulado.png");
        background-repeat: no-repeat;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 10px
    }

</style>

