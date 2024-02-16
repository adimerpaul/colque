<head>
    <title>{{'Acta de entrega'}}</title>
</head>

    <table style="width: 100%; margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
        <tr>
            <td style="width: 87%; padding-top: 20px">

            </td>
            <td style="width: 20%; margin-bottom: 100px">
            </td>

        </tr>

    </table>

<div style="padding-left: 25px;">
    <p>{!!$contrato!!}</p>
</div>

<div>
    <table border="1" class="table" id="activos-tabla" style="  margin-left:30px; margin-right:10px">
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Cantidad</th>

        </tr>
        </thead>
        <tbody>

        @foreach ($activos as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->codigo}}</td>
                <td>{{ $item->descripcion}}</td>
                <td>{{ $item->cantidad_unidad}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="padding-left: 25px;">
        <p>{!!$firmas!!}</p>
    </div>
</div>
    <style>

        @page {
            margin: 105px 75px 70px 65px !important;
        }


        header {
            position: fixed;
            top: -65px;
            left: 0px;
            right: 53px;
            height: 75px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        td {
            padding: 3px;
            padding-left: 5px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: justify;
        }


    </style>

