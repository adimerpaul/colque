<div class="table-responsive">
    <table class="table table-striped" id="egresos-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha y hora</th>
            <th style=" border: 1px solid black;">Peso Patrón</th>
            <th style=" border: 1px solid black;">Peso reportado</th>
            <th style=" border: 1px solid black;">Diferencia</th>
            <th style=" border: 1px solid black;"></th>

        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($calibraciones->currentPage() - 1) * $calibraciones->perPage();
        $row = 1;
        ?>
        @foreach($calibraciones as $calibracion)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ date('d/m/Y H:i', strtotime($calibracion->created_at)) }}</td>
                <td style=" border: 1px solid black;">{{ $calibracion->constanteMedida->valor }}</td>
                <td style=" border: 1px solid black;">{{ $calibracion->valor }}</td>
                <td style=" border: 1px solid black;">{{ $calibracion->diferencia }}</td>
                <td style=" border: 1px solid black;">
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="#" data-target="#modalEdicion"
                               data-txtid="{{$calibracion->id}}"
                               data-txtvalor="{{$calibracion->valor}}"
                               data-txttipo="{{$calibracion->tipo}}"
                               data-toggle="modal"
                               class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i></a>

                        </div>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
