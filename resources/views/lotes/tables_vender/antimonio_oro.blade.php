<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;"></th>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;">FEC. LIQ.</th>
            <th rowspan="2" style=" border: 1px solid black;">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;">PRODUCTOR</th>
            <th rowspan="2" style=" border: 1px solid black;">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;">PESO NETO HUMEDO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;">Humedad (%)</th>
            <th rowspan="2" style=" border: 1px solid black;">Humedad (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;">MERMA (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;">PESO NETO SECO (Kg)</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center">SB</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center">AU</th>
            <th rowspan="2" style=" border: 1px solid black;">VALOR POR TONELADA USD</th>
            <th rowspan="2" style=" border: 1px solid black;">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;">REGALIA MINERA</th>
            <th rowspan="2" style=" border: 1px solid black;">TOTAL RETENCIONES Y
                DESCUENTOS
            </th>
            <th rowspan="2" style=" border: 1px solid black;">TOTAL BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;">ANTICIPO/ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;">SALDO POR DEUDA/PRÉSTAMO</th>
            <th rowspan="2" style=" border: 1px solid black;">LIQUIDO PAGABLE</th>
            <th rowspan="2" style=" border: 1px solid black;">COSTO TRATAMIENTO</th>
            <th rowspan="2" style=" border: 1px solid black;">COSTO LABORATORIO</th>
            <th rowspan="2" style=" border: 1px solid black;">COSTO PESAJE</th>
            <th rowspan="2" style=" border: 1px solid black;">COSTO PUBLICIDAD</th>
            <th rowspan="2" style=" border: 1px solid black;">COSTO DIRIMICIÓN</th>
            <th rowspan="2" style=" border: 1px solid black;">PRO PRODUCTOR</th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;">%Sb</th>
            <th style=" border: 1px solid black;">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;">C. Diaria</th>
            <th style=" border: 1px solid black;">G/T Au</th>
            <th style=" border: 1px solid black;">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;">C. Diaria</th>
        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr id="{{'form'. $formulario->id}}">
                <td style=" border: 1px solid black;"><input type="checkbox" id="{{$loop->iteration}}"
                                                             onchange='seleccionar("{{$loop->iteration}}", "{{$formulario->id}}")'>
                </td>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
                <td style=" border: 1px solid black;">{{ $formulario->lote_sin_gestion }}</td>

                <td style=" border: 1px solid black;">{{ $formulario->cliente->cooperativa->razon_social }}</td>
                <td style=" border: 1px solid black;">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->peso_neto,2, ',', '')}}</td>
                <td style=" border: 1px solid black;"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='H2O'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='H2O'){{number_format(($lab->promedio * $formulario->peso_neto / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;">{{number_format($formulario->merma_kg,2, ',', '')}}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->peso_seco,2, ',', '')}}</td>

                <td style=" border: 1px solid black;">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sb') {{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sb'){{number_format(($lab->promedio * $formulario->peso_seco / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;">{{$formulario->cotizacion_sb}}</td>

                <td style=" border: 1px solid black;"
                > @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Au'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"
                > @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Au'){{number_format(($lab->promedio * $formulario->peso_seco / 10000),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"
                >{{number_format($formulario->cotizacion_au,2, ',', '')}}</td>

                <td style=" border: 1px solid black;">{{number_format($formulario->valor_por_tonelada,2, ',', '')}}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->neto_venta,2, ',', '')}}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->regalia_minera,2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format(($formulario->total_retencion_descuento),2, ',', '') }}</td>


                <td style=" border: 1px solid black;">{{number_format($formulario->total_bonificacion,2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format($formulario->total_anticipo,2, ',', '') }}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->totales['total_cuentas_cobrar'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format($formulario->liquido_pagable,2, ',', '') }}</td>


                <td style=" border: 1px solid black;">{{number_format($formulario->costo->tratamiento,2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format($formulario->costo_pesaje,2, ',', '') }}</td>
                <td style=" border: 1px solid black;">{{number_format(($formulario->costo_publicidad),2, ',', '') }}</td>
                <td style=" border: 1px solid black;">{{number_format($formulario->costo->dirimicion,2, ',', '') }}</td>

                <td style=" border: 1px solid black;">{{number_format(( $formulario->costo_pro_productor),2, ',', '') }}</td>

            </tr>
        @endforeach

        </tbody>
    </table>
</div>
<script type="application/javascript">
    var table = document.getElementById("kardex-tabla");
    var sumaPesoNetoHumedo = 0, sumaPesoFinoAu = 0, sumaPesoNetoSeco = 0, sumaLeyAu = 0, sumaCotizacionAu = 0,
        sumaCotizacionAuFinal = 0, sumaNetoVenta=0,
        sumaPesoFinoSb = 0, sumaLeySb = 0, sumaCotizacionSb = 0, sumaCotizacionSbFinal = 0;

    function seleccionar(contador, formularioId) {
        if ($("#" + contador).is(':checked')) {
            appVenderLotes.arraySeleccionados.push(formularioId)
            sumaPesoNetoHumedo = parseFloat(sumaPesoNetoHumedo) + parseFloat(table.rows[parseInt(contador) + 1].cells[7].innerHTML.replace(/,/g, "."));
            sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, "."));

            sumaCotizacionAu = parseFloat(sumaCotizacionAu) + (parseFloat(table.rows[parseInt(contador) + 1].cells[17].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionSb = parseFloat(sumaCotizacionSb) + (parseFloat(table.rows[parseInt(contador) + 1].cells[14].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoSb = parseFloat(sumaPesoFinoSb) + parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, "."));
            sumaLeySb = ((parseFloat(sumaPesoFinoSb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionSbFinal = parseFloat(sumaCotizacionSb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAu = parseFloat(sumaPesoFinoAu) + parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, "."));
            sumaLeyAu = ((parseFloat(sumaPesoFinoAu) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAuFinal = parseFloat(sumaCotizacionAu) / parseFloat(sumaPesoNetoSeco);

            sumaNetoVenta = parseFloat(sumaNetoVenta) + parseFloat(table.rows[parseInt(contador) + 1].cells[19].innerHTML.replace(/,/g, "."));

            document.getElementById("form" + formularioId).style.backgroundColor = "#00A65A";
            document.getElementById("form" + formularioId).style.color = "white";
        } else {
            var index = appVenderLotes.arraySeleccionados.indexOf(formularioId);
            if (index > -1) {
                appVenderLotes.arraySeleccionados.splice(index, 1);
            }
            document.getElementById("form" + formularioId).style.backgroundColor = "white";
            document.getElementById("form" + formularioId).style.color = "black";

            sumaPesoNetoHumedo = parseFloat(sumaPesoNetoHumedo) - parseFloat(table.rows[parseInt(contador) + 1].cells[7].innerHTML.replace(/,/g, "."));
            sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) - parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, "."));

            sumaCotizacionAu = parseFloat(sumaCotizacionAu) - (parseFloat(table.rows[parseInt(contador) + 1].cells[17].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionSb = parseFloat(sumaCotizacionSb) - (parseFloat(table.rows[parseInt(contador) + 1].cells[14].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaPesoFinoSb = parseFloat(sumaPesoFinoSb) - parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, "."));
            sumaLeySb = ((parseFloat(sumaPesoFinoSb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionSbFinal = parseFloat(sumaCotizacionSb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAu = parseFloat(sumaPesoFinoAu) - parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, "."));
            sumaLeyAu = ((parseFloat(sumaPesoFinoAu) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAuFinal = parseFloat(sumaCotizacionAu) / parseFloat(sumaPesoNetoSeco);

            sumaNetoVenta = parseFloat(sumaNetoVenta) - parseFloat(table.rows[parseInt(contador) + 1].cells[19].innerHTML.replace(/,/g, "."));
        }
        contador = parseInt(contador) + 1;
        document.getElementById("seleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 1 ? (appVenderLotes.arraySeleccionados.length + " Seleccionado") : (appVenderLotes.arraySeleccionados.length + " Seleccionados");
        document.getElementById("pesoNetoSecoSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Peso Neto Seco: " : "- Peso Neto Seco: " + sumaPesoNetoSeco.toFixed(2);
        document.getElementById("pesoNetoHumedoSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Peso Neto Húmedo: " : "- Peso Neto Húmedo: " + sumaPesoNetoHumedo.toFixed(2);
        document.getElementById("leyAuSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Au %: " : "- Au %: " + sumaLeyAu.toFixed(2);
        document.getElementById("cotizacionAuSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Au Cotización Diaria: " : "- Au Cotización Diaria: " + sumaCotizacionAuFinal.toFixed(2);
        document.getElementById("leySbSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Sb %: " : "- Sb %: " + sumaLeySb.toFixed(2);
        document.getElementById("cotizacionSbSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Sb Cotización Diaria: " : "- Sb Cotización Diaria: " + sumaCotizacionSbFinal.toFixed(2);
        document.getElementById("valorNetoVentaSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Valor Neto Venta: " : "- Valor Neto Venta: " + sumaNetoVenta.toFixed(2);
    }

</script>
