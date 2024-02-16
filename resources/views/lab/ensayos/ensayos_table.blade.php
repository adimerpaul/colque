<div class="table-responsive">
    <table class="table" id="activos-tabla" style=" border: 1px solid black;    ">
        <thead class="tabletypehead" style="background-color: #3C8DBC;">
        <tr>
            <th style="border: 1px solid black; color:white;">#</th>
            <th style="border: 1px solid black; color:white;">Fecha</th>
            <th style="border: 1px solid black; color:white;">Código</th>
            <th style="border: 1px solid black; color:white;">Lote</th>
            <th style="border: 1px solid black; color:white;">Caracteristicas</th>
            <th style="border: 1px solid black; color:white;">Peso humedo</th>
            <th style="border: 1px solid black; color:white;">Peso seco</th>
            <th style="border: 1px solid black; color:white;">Peso tara</th>
            
            
        </tr>
        </thead>
        <tbody class="tabletype" style="background-color: #3e515c;">
            @foreach ($ensayos as $item)

                <tr>
                    <td style=" border: 1px solid  black ; color:white;">{{ $loop->iteration}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->created_at->format('Y-m-d')}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->codigo}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->lote}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->caracteristicas}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->peso_humedo}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->peso_seco}}</td>
                    <td style=" border: 1px solid  black ; color:white;">{{ $item->peso_tara }}</td>
                    
                </tr>

            @endforeach
        </tbody>
    </table>
    <br>


</div>
<style>
    .tabletypehead {
        background: linear-gradient(to bottom, #3C8DBC, rgba(103, 153, 181));
    }
    .tabletype {
        background: linear-gradient(to bottom, #3e515c, rgba(114, 129, 138));
    }
    
</style>