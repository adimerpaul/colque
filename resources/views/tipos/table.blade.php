<div class="table-responsive">
    <table class="table" id="tipo-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tipos as $tipo)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $tipo->tabla }}</td>
                <td>{{ $tipo->valor }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
