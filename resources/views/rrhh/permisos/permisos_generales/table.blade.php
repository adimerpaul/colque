<table id="permisos-table" class="table table-striped" style=" border: 1px solid black;">
    <thead class="table-dark">
    <tr>
            <th colspan="5" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE PERMISOS
                <br>
                @if($fechaInicial)<b id="fechas"></b>@endif
                <br>
            </th>
    </tr>
    <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Personal</th>
            <th style=" border: 1px solid black;">Fecha Inicial</th>
            <th style=" border: 1px solid black;">Fecha Final</th>
            <th style=" border: 1px solid black;">Tipo</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($permisos as $item)

        <tr>
            <td style=" border: 1px solid black;">{{  $loop->iteration}}</td>
            <td style=" border: 1px solid black;">{{ \App\Patrones\Fachada::getPersonal()[$item->personal_id] }}</td>
            <td style=" border: 1px solid black;">{{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y H:i:s') }}</td>
            <td style=" border: 1px solid black;">{{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y H:i:s') }}</td>
            <td style=" border: 1px solid black;">{{  $item->tipo}}</td>

        </tr>
    @endforeach
    @if($permisos->count()==0)
        <tr>
            <td colspan="5" class="text-center">No se solicitaron permisos para las fechas seleccionadas </td>
        </tr>
     @endif
    </tbody>
</table>
