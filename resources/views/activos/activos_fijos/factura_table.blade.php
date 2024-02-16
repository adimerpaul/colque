<table table class="table">
    <thead class="table-red">
    <tr>



        <th scope="coll">Cantidad</th>
        <th scope="coll">Valor Unitario BOB</th>
        <th scope="coll">Factura</th>
        <th scope="coll">Descripción</th>
        
        <th>

        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($facturas as $item)

    <tr>

        <td>{{ $item->cantidad_stock}}</td>
        <td>{{ $item->valor_unitario}}</td>
        <td>{{ $item->factura}}</td>
        <td>{{ $item->descripcion}}</td>

        

    </tr>
    @endforeach
    </tbody>
</table>
