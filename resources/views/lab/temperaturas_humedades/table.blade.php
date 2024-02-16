<div class="table-responsive">
    <table class="table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Tipo</th>
            <th style=" border: 1px solid black;">Rango</th>
            <th style=" border: 1px solid black;">Reportado</th>
            <th style=" border: 1px solid black;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($temperaturas as $temperatura)
            <tr>
                <td style="border: 1px solid black;">{{$loop->iteration}}</td>
                <td style="border: 1px solid black;">{{date('d/m/Y H:i', strtotime($temperatura->created_at))}}</td>
                <td style="border: 1px solid black;">{{ $temperatura->tipo }}</td>
                <td style="border: 1px solid black;">{{ $temperatura->rangoMedicion->info }}</td>
                @if($temperatura->fuera_rango)
                    <td style="border: 1px solid black;"> <label class='label label-danger' style="font-size: 13px"> {{ $temperatura->valor }}</label></td>
                @else
                    <td style="border: 1px solid black;"> {{ $temperatura->valor }}</td>
                @endif
                <td style="border: 1px solid black;">
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="#" data-target="#modalEdicion"
                               data-txtid="{{$temperatura->id}}"
                               data-txtambiente="{{$temperatura->ambiente}}"
                               data-txtvalor="{{$temperatura->valor}}"
                               data-txttipo="{{$temperatura->tipo}}"
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
