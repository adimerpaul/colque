<div class="table-responsive">
    <table class="table" id="rangos-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Valor Mínimo</th>
            <th>Valor Máximo</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($rangos as $rango)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $rango->tipo }}</td>
                <td>{{ $rango->minimo }}</td>
                <td>{{ $rango->maximo }}</td>
                <td>
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="#" data-target="#modalEdicion"
                               data-txtid="{{$rango->id}}"
                               data-txtminimo="{{$rango->minimo}}"
                               data-txtmaximo="{{$rango->maximo}}"
                               data-txttipo="{{$rango->tipo}}"
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
