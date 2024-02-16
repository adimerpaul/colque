<table class="table table-striped">
        <thead >
        <tr>
                <th>#</th>
                <th>Personal</th>
                <th>Fecha Inicial</th>
                <th>Fecha Final</th>
                <th>Descripción</th>
                <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($horasExtra as $item)
    

        <tr>
            <td>{{  $loop->iteration}}</td>
            <td>{{ \App\Patrones\Fachada::getPersonal()[$item->personal_id] }}</td>
            <td>{{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y H:i:s') }}</td>
            <td>{{  $item->descripcion}}</td>
            <td>
                <a class='dropdown-item' href="{{ route('horas-extras.edit', $item->id) }}">
                    <i class="glyphicon glyphicon-edit" title="Editar"></i>
                </a>
            </td>
        
        </tr>
    @endforeach
    </tbody>
</table>