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
            <th colspan="3" style=" border: 1px solid black; text-align: center">PB</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center">AG</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center">ZN</th>
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
            <th style=" border: 1px solid black;">%Pb</th>
            <th style=" border: 1px solid black;">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;">C. Diaria</th>
            <th style=" border: 1px solid black;">DM Ag</th>
            <th style=" border: 1px solid black;">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;">C. Diaria</th>
            <th style=" border: 1px solid black;">%Zn</th>
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
                <td style=" border: 1px solid black; background-color: #00838F; color:white">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb') {{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb'){{number_format(($lab->promedio * $formulario->peso_seco / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;">{{$formulario->cotizacion_pb}}</td>
                <td style=" border: 1px solid black; background-color: #00838F; color:white"
                > @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"
                > @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format(($lab->promedio * $formulario->peso_seco / 10000),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;">{{number_format($formulario->cotizacion_ag,2, ',', '')}}</td>
                <td style=" border: 1px solid black; background-color: #00838F; color:white">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn') {{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn'){{number_format(($lab->promedio * $formulario->peso_seco / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;">{{$formulario->cotizacion_zn}}</td>

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
    var sumaPesoNetoHumedo = 0, sumaPesoFinoAg = 0, sumaPesoNetoSeco = 0, sumaLeyAg = 0, sumaCotizacionAg = 0,
        sumaCotizacionAgFinal = 0, sumaNetoVenta=0,
        sumaPesoFinoPb = 0, sumaLeyPb = 0, sumaCotizacionPb = 0, sumaCotizacionPbFinal = 0,
        sumaPesoFinoZn = 0, sumaLeyZn = 0, sumaCotizacionZn = 0, sumaCotizacionZnFinal = 0;

    function seleccionar(contador, formularioId) {
        if ($("#" + contador).is(':checked')) {
            appVenderLotes.arraySeleccionados.push(formularioId)
            sumaPesoNetoHumedo = parseFloat(sumaPesoNetoHumedo) + parseFloat(table.rows[parseInt(contador) + 1].cells[7].innerHTML.replace(/,/g, "."));

            sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, "."));

            sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(table.rows[parseInt(contador) + 1].cells[17].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionPb = parseFloat(sumaCotizacionPb) + (parseFloat(table.rows[parseInt(contador) + 1].cells[14].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionZn = parseFloat(sumaCotizacionZn) + (parseFloat(table.rows[parseInt(contador) + 1].cells[20].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) + parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, "."));
            sumaLeyPb = ((parseFloat(sumaPesoFinoPb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionPbFinal = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) + parseFloat(table.rows[parseInt(contador) + 1].cells[19].innerHTML.replace(/,/g, "."));
            sumaLeyZn = ((parseFloat(sumaPesoFinoZn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionZnFinal = parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSeco);

            sumaNetoVenta = parseFloat(sumaNetoVenta) + parseFloat(table.rows[parseInt(contador) + 1].cells[22].innerHTML.replace(/,/g, "."));

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

            sumaCotizacionAg = parseFloat(sumaCotizacionAg) - (parseFloat(table.rows[parseInt(contador) + 1].cells[17].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionPb = parseFloat(sumaCotizacionPb) - (parseFloat(table.rows[parseInt(contador) + 1].cells[14].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));
            sumaCotizacionZn = parseFloat(sumaCotizacionZn) - (parseFloat(table.rows[parseInt(contador) + 1].cells[20].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[11].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) - parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, "."));
            sumaLeyPb = ((parseFloat(sumaPesoFinoPb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionPbFinal = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) - parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) - parseFloat(table.rows[parseInt(contador) + 1].cells[19].innerHTML.replace(/,/g, "."));
            sumaLeyZn = ((parseFloat(sumaPesoFinoZn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionZnFinal = parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSeco);

            sumaNetoVenta = parseFloat(sumaNetoVenta) - parseFloat(table.rows[parseInt(contador) + 1].cells[22].innerHTML.replace(/,/g, "."));
        }
        contador = parseInt(contador) + 1;
        document.getElementById("seleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 1 ? (appVenderLotes.arraySeleccionados.length + " Seleccionado") : (appVenderLotes.arraySeleccionados.length + " Seleccionados");
        document.getElementById("pesoNetoSecoSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Peso Neto Seco: " : "- Peso Neto Seco: " + sumaPesoNetoSeco.toFixed(2);
        document.getElementById("pesoNetoHumedoSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Peso Neto Húmedo: " : "- Peso Neto Húmedo: " + sumaPesoNetoHumedo.toFixed(2);
        document.getElementById("leyAgSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Ag %: " : "- Ag %: " + sumaLeyAg.toFixed(2);
        document.getElementById("cotizacionAgSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Ag Cotización Diaria: " : "- Ag Cotización Diaria: " + sumaCotizacionAgFinal.toFixed(2);
        document.getElementById("leyPbSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Pb %: " : "- Pb %: " + sumaLeyPb.toFixed(2);
        document.getElementById("cotizacionPbSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Pb Cotización Diaria: " : "- Pb Cotización Diaria: " + sumaCotizacionPbFinal.toFixed(2);
        document.getElementById("leyZnSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Zn %: " : "- Zn %: " + sumaLeyZn.toFixed(2);
        document.getElementById("cotizacionZnSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Zn Cotización Diaria: " : "- Zn Cotización Diaria: " + sumaCotizacionZnFinal.toFixed(2);
        document.getElementById("valorNetoVentaSeleccionados").innerHTML = appVenderLotes.arraySeleccionados.length === 0 ? "- Valor Neto Venta: " : "- Valor Neto Venta: " + sumaNetoVenta.toFixed(2);
    }

</script>
