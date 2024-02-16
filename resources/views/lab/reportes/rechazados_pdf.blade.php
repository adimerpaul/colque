<head>
    <title>Muestras Rechazadas</title>
</head>
<div style="width: 29%; float:right; text-align: right; margin-top: -10px">
    <img src="{{ 'logos/logoLab.png'}}" style="width: 150px; height: 75px;">
</div>

<div id="parent" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: -12px">


    <div class="centro" style="font-family: Arial, Helvetica, sans-serif;">
<br><br>
        <h2 style="margin-top: 1px; color: #042E44"> REPORTE DE MUESTRAS RECHAZADAS</h2>
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
                <td><strong>FECHA</strong></td>
                <td><strong>CÓDIGO</strong></td>

                <td><strong>DESCRIPCIÓN</strong></td>
                <td><strong>CLIENTE</strong></td>
                <td><strong>NIT</strong></td>
            </tr>
            @foreach($pedidos as $pedido)
                <tbody id="tabla">
                <tr style="font-size: 11px; font-weight: normal; text-align: center">
                    <td style="padding-left: 6px"> {{  $loop->iteration }}</td>
                    <td style="padding-left: 6px"> {{ date('d/m/y H:i', strtotime($pedido->fecha_rechazo)) }}</td>

                    <td style="padding-left: 6px"> {{ $pedido->codigo_pedido }}
                    </td>
                    <td style="padding-left: 6px"> {{ $pedido->descripcion }}</td>
                    <td style="padding-left: 6px"> {{ $pedido->cliente->nombre }}</td>
                    <td style="padding-left: 6px"> {{ $pedido->cliente->nit }}</td>
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

