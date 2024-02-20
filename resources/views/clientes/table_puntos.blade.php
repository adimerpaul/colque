<div class="table-responsive">
    <table class="table table-striped" id="puntos-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Valor</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($puntos->currentPage() - 1) * $puntos->perPage();
        $row = 1;
        ?>
        @foreach($puntos as $punto)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($punto->created_at)) }}</td>
                <td>{{ $punto->descripcion}}</td>
                <td>{{ $punto->valor }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
