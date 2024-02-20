<div class="table-responsive">
    <table class="table" id="tipoCambios-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($constantes as $constante)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $constante->tipo }}</td>
                <td>{{ $constante->valor }}</td>
                <td>
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="#" data-target="#modalEdicion"
                               data-txtid="{{$constante->id}}"
                               data-txtvalor="{{$constante->valor}}"
                               data-txttipo="{{$constante->tipo}}"
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
