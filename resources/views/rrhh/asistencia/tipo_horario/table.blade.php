<table table class="table">
    <thead class="table-red">
    <tr>
        <th scope="coll">#</th>
        <th scope="coll">Descripcion</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tiposHorarios as $item)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$item->horario}}</td>
        <td>
            <a class='btn btn-default btn-xs'
            href="#"
            data-target="#modalEdit"
            data-toggle="modal"
            data-txtid="{{ $item->id }}"
            data-txtdescripcion="{{$item->descripcion}}"
            title="Editar">
            <i class="glyphicon glyphicon-edit"></i>
            </a>

        </td>
    </tr>
    @endforeach
    </tbody>
    
</table>
