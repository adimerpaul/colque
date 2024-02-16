<table style="width: 95%; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 6px;">
        <td style="width: 60%;font-size: 10px;; padding-top: 5px; text-align: center;">
            <b style="color: #042E44; font-size: 10px;"> BOLETA PESAJE</b>
            <br><label style="font-size: 6px;text-align: center">LOTE</label>
            <br>
            <b>{{substr( $formularioLiquidacion->lote,0, -3)}}</b>
            <br>
            <label style="font-size: 6px;text-align: center">ELEMENTO</label>
            <br>
            <label style="text-align: center"><b>{{$elemento }}</b></label></td>


        <td rowspan="1" style="width:37%; padding-top: 1px; text-align: center">
            <div style="text-align: center; margin-top: 1px">
                <img style="width: 105%; text-align: center" src="data:image/png;base64, {!! $qrcode !!}">
            </div>
        </td>
    </tr>
</table>

<style>
    @page {
        margin: 4px 2px 0px 2px !important;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0 1em;

    }


</style>
