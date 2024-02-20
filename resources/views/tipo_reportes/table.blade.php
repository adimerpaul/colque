<div class="table-responsive">
    <table class="table table-striped" id="tipo-reporte-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tipoReportes as $tipoReporte)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $tipoReporte->nombre }}</td>
                <td>{{ $tipoReporte->descripcion }}</td>
                <td>{{ $tipoReporte->color }}</td>
                <td>
                    <div class='btn-group'>

                        <a href="{{ route('tipoReportes.edit', [$tipoReporte->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                        <a href="{{ route('campoReportes.edit', [$tipoReporte->id]) }}" class='btn btn-info btn-xs'><i
                                class="glyphicon glyphicon-check" title="Campos de reporte"></i> </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
