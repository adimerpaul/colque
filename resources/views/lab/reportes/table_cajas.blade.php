<div class="table-responsive">
    <table class="table table-striped" id="egresos-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Glosa</th>
            <th style=" border: 1px solid black;">Cantidad</th>
            <th style=" border: 1px solid black;">Efectivo BOB</th>
            <th style=" border: 1px solid black;">Banco BOB</th>
        </tr>
        </thead>
        <tbody>

        @foreach($egresos as $egreso)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $loop->iteration }}</td>
                <td style=" border: 1px solid black;">{{ $egreso->glosa }}</td>
                <td style=" border: 1px solid black;">{{ $egreso->cantidad }}</td>
                @if($egreso->metodo=='Efectivo')
                    <td style=" border: 1px solid black;">{{ number_format($egreso->sumatoria, 2) }}</td>
                @else
                    <td style=" border: 1px solid black;"></td>
                @endif

                @if($egreso->metodo=='Cuenta Bancaria')
                    <td style=" border: 1px solid black;">{{ number_format($egreso->sumatoria, 2) }}</td>
                @else
                    <td style=" border: 1px solid black;"></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

</div>


<div class="table-responsive">
    <table class="table table-striped" id="ingresos-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Glosa</th>
            <th style=" border: 1px solid black;">Método</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
            <th style=" border: 1px solid black;">Lotes</th>

        </tr>
        </thead>
        <tbody>

        @foreach($ingresos as $ingreso)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $loop->iteration }}</td>
                <td style=" border: 1px solid black;">{{ $ingreso->glosa }}</td>
                <td style=" border: 1px solid black;">{{ $ingreso->metodo }}</td>
                <td style=" border: 1px solid black;">{{ number_format($ingreso->sumatoria, 2) }}</td>
                <td style=" border: 1px solid black;">{{ $ingreso->cantidad }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
