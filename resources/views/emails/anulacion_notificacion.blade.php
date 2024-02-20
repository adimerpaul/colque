<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SeLA - Oruro | Notificaciones</title>
    <style type="text/css">
        .padre {
            padding: 0 1rem;
            margin: 1rem;
        }

        .hijo {
            /* IMPORTANTE */
            width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .cabezera {
            background-color: #007bff !important;
            font-family: "Liberation Sans", sans-serif;
            color: #fff !important;
            padding: 5px;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<body>

<div class="content mt-5">
    <div class="padre">
        <div class="hijo cabezera">
            <table style="width: 100%">
                <tr>
                    <td style="width: 55px">
                        <img src="{{ url('https://selaoruro.gob.bo/img/intro-carousel/bolivia.gif') }}" alt="logo"
                             width="50px">
                    </td>
                    <td>
                        <h3>SeLA - ORURO</h3>
                    </td>
                    <td class="text-right">
                    </td>
                </tr>
            </table>

        </div>

        <div class="hijo">
            <hr>
            <div style="font-size: large">
                <p class="text-justify">
                    Estimado (a) cliente: {{ $factura['razon_social']  }} <br>
                    @if(isset($factura['tipo']))
                        Le informamos que su {{ $factura['tipo'] }} ha sido <strong>ANULADA</strong>:
                    @else
                        Le informamos que su factura electrónica ha sido <strong>ANULADA</strong>:
                    @endif
                </p>

                <table style="width: 100%">
                    <tr>
                        <td class="text-right" style="width: 50%"><strong>Número:</strong></td>
                        <td>{{ $factura['numero_factura'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" style="width: 50%"><strong>Fecha de pago:</strong></td>
                        <td>{{ date("d/m/Y", strtotime($factura['fecha_emision']))  }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" style="width: 50%"><strong>Código de Autorización:</strong></td>
                        <td>{{ $factura['cuf']  }}</td>
                    </tr>
                </table>
            </div>

            <p class="text-justify">
                Puede ver el detalle de su factura anulada en el siguiente enlace: <br>
                <a href='{{ \App\Patrones\Env::urlQr . "QR?nit=1010413026&cuf={$factura['cuf']}&numero={$factura['numero_factura']}&t=2"  }}'
                   target="_blank">Detalle de la factura</a>
            </p>

            <p class="text-justify">
                (*) <strong>IMPORTANTE</strong> :
                Si usted ha recibido este email por error, le pedimos informar el remitente y proceda a la destrucción
                de su contenido.
            </p>
        </div>
    </div>
</div>

<footer>
    <div class="padre">
        <div class="hijo text-muted">
            <hr>

            <table style="width: 100%">
                <tr>
                    <td>
                        Dir: Av. Villarroel #222 entre Backovick y Brasil <br>
                        &copy; {{ date('Y') }} - SeLA - ORURO
                    </td>
                    <td class="text-right">
                        Teléfono: 52-35947 <br>
                        Celular:71880887
                    </td>
                </tr>
            </table>
        </div>
    </div>
</footer>

</body>
</html>