<table class="table table-bordered"
       style="border:1px solid; font-size: 11px; border: #CFD8DC; border-collapse: collapse; width: 100%; margin-top: -10px">
    <thead>
    <tr style="background-color: #CFD8DC; border: #CFD8DC; border:1px solid; text-align: center">
        <th style="text-align: center" rowspan="2">1. CANTIDAD, TARA, HUMEDAD Y MERMA</th>
        <th style="text-align: center" rowspan="2">2. CALIDAD ELEMENTO</th>
        <th style="text-align: center" colspan="3">3. PRECIOS Y ALICUOTAS</th>
        <th style="text-align: center" rowspan="2">4. TIPO DE CAMBIO</th>
    </tr>
    <tr style="background-color: #CFD8DC; border: #CFD8DC; border:1px solid;">
        <th style="text-align: center">C. DIARIA</th>
        <th style="text-align: center">C. OFICIAL</th>
        <th style="text-align: center">ALICUOTA</th>
    </tr>
    </thead>
    <tbody>
    <tr style="font-size: 11px; vertical-align: top; ">
        <td style="width: 35%; padding-left: 2px; border: #CFD8DC; border:1px solid; padding-left: 3px">
            <p style="margin-top: 2px"><strong>PESO BRUTO
                    HÚMEDO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($pesoBruto, 2) }} KG</label><br></p>
            <p style="margin-top: -8px">
                <strong>TARA: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($tara, 2) }} KG</label><br></p>
            <p style="margin-top: -8px"><strong>PESO NETO
                    HÚMEDO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($pesoNetoHumedo, 2) }} KG</label><br></p>
            <p style="margin-top: -8px"><strong>HUMEDAD:&nbsp;&nbsp;
                    {{ number_format($humedad,2) }}%
                </strong>
                <label
                    style="float: right; margin-right: 3px"> {{ number_format($humedadKg, 2) }} KG</label>
                <br>
            </p>
            <p style="margin-top: -8px">
                <strong>MERMA: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($merma, 2) }}
                    % </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($mermaKg, 2) }} KG</label><br></p>

            <p style="margin-top: -8px"><strong>PESO NETO
                    SECO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($pesoNetoSeco, 2) }}
                    KG</label><br></p>
        </td>
        <td style="width: 15%; border: #CFD8DC; border:1px solid; padding-left: 3px">

            <div style="margin-top: 2px">
                @if($producto->letra==='A')
                    <strong>Ag: </strong>{{ number_format($leyes['Ag'],2) }} DM<br>
                    <strong>Zn: </strong>{{ number_format($leyes['Zn'],2) }} %<br>
                @elseif($producto->letra==='B')
                    <strong>Ag: </strong>{{ number_format($leyes['Ag'],2) }} DM<br>
                    <strong>Pb: </strong>{{ number_format($leyes['Pb'],2) }} %<br>
                @elseif($producto->letra==='E')
                    <strong>Ag: </strong>{{ number_format($leyes['Ag'],2) }} DM<br>
                @elseif($producto->letra==='C')
                    <strong>Ag: </strong>{{ number_format($leyes['Ag'],2) }} DM<br>
                    <strong>Pb: </strong>{{ number_format($leyes['Pb'],2) }} %<br>
                    <strong>Zn: </strong>{{ number_format($leyes['Zn'],2) }} %<br>
                @elseif($producto->letra==='F')
                    <strong>Sb: </strong>{{ number_format($leyes['Sb'],2) }} %<br>
                @elseif($producto->letra==='G')
                    <strong>Cu: </strong>{{ number_format($leyes['Cu'],2) }} %<br>
                @else
                    <strong>Sn: </strong>{{ number_format($leyes['Sn'],2) }} %<br>
                @endif
            </div>
        </td>

        <td style="width: 12%; text-align: center">
            <div style="margin-top: 2px">
                @foreach($cotizacionesDiarias as $diaria)
                    {{ number_format($diaria->monto_form,2). ' ' .$diaria->unidad_form  }}
                    <br>
                @endforeach
            </div>
        </td>
        <td style="width: 14%; text-align: center">
            <div style="margin-top: 2px">
                @foreach($cotizacionesOficiales as $oficial)
                    {{ number_format($oficial->monto, 2). ' $us/' .$oficial->unidad  }}<br>
                @endforeach
            </div>
        </td>
        <td style="width: 12%; text-align: center">
            <div style="margin-top: 2px">
                @foreach($cotizacionesOficiales as $oficial)
                    {{ number_format($oficial->alicuota_interna, 2) }}<br>
                @endforeach
            </div>
        </td>

        <td style="width: 20%; border: #CFD8DC; border:1px solid; padding-left: 3px">
            <p style="margin-top: 2px">
                <strong>Oficial: </strong> <label
                    style="float: right; margin-right: 3px">{{ number_format($tipoCambio->dolar_venta, 2) }}</label>
            </p>
            <p style="margin-top: -8px">
                <strong>Comercial: </strong> <label
                        style="float: right; margin-right: 3px">{{ number_format($tipoCambio->dolar_compra, 2) }}</label>
            </p>

        </td>
    </tr>
    </tbody>

</table>
