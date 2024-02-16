
<table style="width: 95%; font-family: Arial, Helvetica, sans-serif;">
    <tr style="font-size: 6px;" >
        <td  style="width: 60%; text-align: center; ">
            <b style="color: #042E44; font-size: 12px" > ACTIVO FIJO</b>
        </td>
        <td rowspan="3"  style="width: 40%; text-align: center">
            <div style="text-align: center; margin-top: 1px" >
                <img style="width: 105%; text-align: center" src="data:image/png;base64, {!! $qrcode !!}">
            </div>
        </td>
    </tr>


    <tr style="font-size: 12px; padding-top: -25px; padding: -5px" >
        <td style="text-align: center;"><b>{{ $activo->codigo}}</b></td>
    </tr>


    <tr style="padding-top: -25px; padding: -5px" >
        <td style="text-align: center;"> <img src="{{ 'logos/logo.png'}}" style="width: 68px; height: 33px; "></td>
    </tr>

</table>

<style>
    @page {
        margin: 2px 2px 0px 2px !important;
    }

    table {
        border-collapse:collapse;
        border-spacing: 0 1em;

    }
</style>
