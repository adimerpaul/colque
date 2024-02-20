<h3>REPORTE PRODUCTORES LIQUIDADOS</h3>

        <div class="table-responsive">
            <table style=" border: 1px solid black;" class="table table-striped" id="tablaProductores">
                <thead>
                <tr>
                    <th style=" border: 1px solid black;">#</th>

                    <th style=" border: 1px solid black;">Productor</th>
                    <th style=" border: 1px solid black;">Nit</th>
                    <th style=" border: 1px solid black;">Producto</th>
                    <th style=" border: 1px solid black;">Cantidad</th>

                </tr>
                </thead>
                <tbody>

                @foreach($reporteProductoresLiquidados as $cooperativa)
                    <tr>
                        <td style=" border: 1px solid black;" class="text-muted">{{ $loop->iteration }}</td>
                        <td style=" border: 1px solid black;">{{$cooperativa->razon_social }}</td>
                        <td style=" border: 1px solid black;">{{$cooperativa->nit }}</td>
                        <td style=" border: 1px solid black;">{{$cooperativa->producto }}</td>
                        <td style=" border: 1px solid black;">{{$cooperativa->count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

