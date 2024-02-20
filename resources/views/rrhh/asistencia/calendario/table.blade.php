<table table class="table">
    <thead class="table-red">
    <tr>
        <th scope="coll">#</th>
        <th scope="coll">Año</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($aniosUnicos as $item)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$item}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
