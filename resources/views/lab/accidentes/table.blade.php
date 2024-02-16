<div class="table-responsive">
    <table class="table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Hora</th>
            <th style=" border: 1px solid black;">Accidente</th>
            <th style=" border: 1px solid black;">Descripción</th>
            <th style=" border: 1px solid black;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($accidentes as $accidente)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{date('d/m/Y', strtotime($accidente->fecha))}}</td>
                <td style=" border: 1px solid black;">{{date('H:i', strtotime($accidente->hora))}}</td>
                <td style=" border: 1px solid black;">{{ $accidente->tipo }}</td>
                <td style=" border: 1px solid black;">{{ $accidente->descripcion }}</td>
                <td style=" border: 1px solid black;">
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="#" data-target="#modalEdicion"
                               data-txtid="{{$accidente->id}}"
                               data-txtdescripcion="{{$accidente->descripcion}}"
                               data-txtfecha="{{$accidente->fecha}}"
                               data-txthora="{{$accidente->hora}}"
                               data-txttipo="{{$accidente->tipo}}"
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
