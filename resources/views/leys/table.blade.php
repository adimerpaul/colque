<div class="table-responsive">
    <table class="table" id="leys-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Valor</th>
            <th>Unidad</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($leys as $ley)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ley->valor }}</td>
                <td>{{ $ley->unidad }}</td>
                <td>
                    {!! Form::open(['route' => ['leys.destroy', $ley->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leys.edit', [$ley->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
