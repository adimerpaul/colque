<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;"></th>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaRecepcion">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;" id="loteCompra">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="proveedor">PROVEEDOR</th>
            <th rowspan="2" style=" border: 1px solid black;" id="cliente">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoHumedo">PESO NETO HUMEDO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="humedadPorcentaje">Humedad (%)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="humedadKg">Humedad (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="merma">MERMA (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            @if($producto->id===2)
                <th colspan="3" style=" border: 1px solid black; text-align: center" id="pb" class="pb">PB</th>
                <th colspan="3" style=" border: 1px solid black; text-align: center" id="ag" class="ag">AG</th>
            @elseif($producto->id===4)
                <th colspan="3" style=" border: 1px solid black; text-align: center" id="sn" class="sn">SN</th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;" id="valorPorTonelada">VALOR POR TONELADA USD</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="regaliaMinera">REGALIA MINERA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="totalRetencionesDescuento">TOTAL RETENCIONES Y
                DESCUENTOS
            </th>
            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificaciones">TOTAL BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="anticipos">ANTICIPO/ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="anticiposFEntrega">ANTICIPO/F. ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="liquidoPagable">LIQUIDO PAGABLE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="tratamiento">COSTO TRATAMIENTO</th>
            <th rowspan="2" style=" border: 1px solid black;" id="laboratorio">COSTO LABORATORIO</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesaje">COSTO PESAJE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="comision">COSTO DE COMISIONES</th>
        </tr>
        <tr>
            @if($producto->id===2)
                <th style=" border: 1px solid black;" id="pb" class="pb">%Pb</th>
                <th style=" border: 1px solid black;" id="pb" class="pb">Peso Fino (Kg)</th>
                <th style=" border: 1px solid black;" id="pb" class="pb">C. Diaria</th>
                <th style=" border: 1px solid black;" id="ag" class="ag">DM Ag</th>
                <th style=" border: 1px solid black;" id="ag" class="ag">Peso Fino (Kg)</th>
                <th style=" border: 1px solid black;" id="ag" class="ag">C. Diaria</th>
            @elseif($producto->id===4)
                <th style=" border: 1px solid black;" id="sn" class="sn">% Sn</th>
                <th style=" border: 1px solid black;" id="sn" class="sn">Peso Fino (Kg)</th>
                <th style=" border: 1px solid black;" id="sn" class="sn">C. Diaria</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr id="{{'form'. $formulario->id}}">
                <td style=" border: 1px solid black;"><input type="checkbox" id="{{$loop->iteration}}"
                                                             onchange='seleccionar("{{$loop->iteration}}", "{{$formulario->id}}")'>
                </td>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;" id="fechaRecepcionTd"
                    class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote }}</td>

                <td style=" border: 1px solid black;" id="proveedorTd"
                    class="proveedorTd">{{ $formulario->cliente->cooperativa->razon_social }}</td>
                <td style=" border: 1px solid black;" id="clienteTd"
                    class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoHumedoTd"
                    class="pesoNetoHumedoTd">{{number_format($formulario->peso_neto,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="humedadPorcentajeTd"
                    class="humedadPorcentajeTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='H2O'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="humedadKgTd"
                    class="humedadKgTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='H2O'){{number_format(($lab->promedio * $formulario->peso_neto / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="mermaTd"
                    class="mermaTd">{{number_format($formulario->merma_kg,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoSecoTd"
                    class="pesoNetoSecoTd">{{number_format($formulario->peso_neto_seco,2, ',', '')}}</td>
                @if($producto->id===2)
                    <td style=" border: 1px solid black;" id="leyPbTd" class="pbTd">
                        @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Pb') {{number_format($lab->promedio,2, ',', '')}} @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;" id="pesoFinoPbTd"
                        class="pbTd"> @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Pb'){{number_format(($lab->promedio * $formulario->peso_neto_seco / 100),2, ',', '')}}  @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;" id="cotizacionPbTd"
                        class="pbTd">{{$formulario->cotizacion_pb}}</td>
                    <td style=" border: 1px solid black;"
                        class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Ag'){{number_format($lab->promedio,2, ',', '')}} @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;"
                        class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Ag'){{number_format(($lab->promedio * $formulario->peso_neto_seco / 10000),2, ',', '')}}  @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;"
                        class="agTd">{{number_format($formulario->cotizacion_ag,2, ',', '')}}</td>
                @elseif($producto->id===4)
                    <td style=" border: 1px solid black;" id="leySnTd" class="snTd">
                        @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Sn') {{number_format($lab->promedio,2, ',', '')}} @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;" id="pesoFinoSnTd" class="snTd"> @foreach($formulario->laboratorio_promedio as $lab)
                            @if($lab->simbolo=='Sn'){{number_format(($lab->promedio * $formulario->peso_neto_seco / 100),2, ',', '')}}  @endif
                        @endforeach
                    </td>
                    <td style=" border: 1px solid black;" id="cotizacionSnTd" class="snTd">{{$formulario->cotizacion_sn}}</td>
                @endif
                <td style=" border: 1px solid black;" id="valorPorToneladaTd"
                    class="valorPorToneladaTd">{{number_format($formulario->valor_por_tonelada,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="valorNetoVentaTd"
                    class="valorNetoVentaTd">{{number_format($formulario->valor_neto_venta,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="regaliaMineraTd"
                    class="regaliaMineraTd">{{number_format($formulario->totales['total_minerales'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd"
                    class="totalRetencionesDescuentoTd">{{number_format(($formulario->totales['total_retenciones']+$formulario->totales['total_descuentos']),2, ',', '') }}</td>


                <td style=" border: 1px solid black;" id="totalBonificacionesTd"
                    class="totalBonificacionesTd">{{number_format($formulario->totales['total_bonificaciones'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="anticiposTd"
                    class="anticiposTd">{{number_format($formulario->totales['total_anticipos'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="anticiposFEntregaTd"
                    class="anticiposFEntregaTd">{{number_format($formulario->totales['total_anticipo_fentrega'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="liquidoPagableTd"
                    class="liquidoPagableTd">{{number_format($formulario->totales['total_final'],2, ',', '') }}</td>


                <td style=" border: 1px solid black;" id="tratamientoTd"
                    class="tratamientoTd">{{number_format(($formulario->peso_bruto * $formulario->costo->tratamiento * 6.97),2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="laboratorioTd"
                    class="laboratorioTd">{{number_format(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="pesajeTd"
                    class="pesajeTd">{{number_format($formulario->costo_pesaje,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="comisionTd"
                    class="comisionTd">{{number_format(($formulario->peso_neto_seco * $formulario->costo->comision * 6.86),2, ',', '') }}</td>

            </tr>
        @endforeach

        </tbody>
    </table>
</div>
<script>
    var arraySeleccionados = [];
    var table = document.getElementById("kardex-tabla");
    var sumaPesoNeto = 0, sumaPesoFinoAg = 0, sumaPesoNetoSeco = 0, sumaLeyAg = 0, sumaCotizacionAg = 0,
        sumaCotizacionAgFinal = 0,
        sumaPesoFinoPb = 0, sumaLeyPb = 0, sumaCotizacionPb = 0, sumaCotizacionPbFinal = 0,
        sumaPesoFinoSn = 0, sumaLeySn = 0, sumaCotizacionSn = 0, sumaCotizacionSnFinal = 0;

    function seleccionar(contador, formularioId) {
        if ($("#" + contador).is(':checked')) {
            arraySeleccionados.push(formularioId)
            sumaPesoNeto = parseFloat(sumaPesoNeto) + parseFloat(table.rows[parseInt(contador) + 1].cells[6].innerHTML.replace(/,/g, "."));

            sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, "."));
            if("{{$producto->id==1}}")
                calculosZincPlata(true, table, sumaPesoNetoSeco, contador);
            else if("{{$producto->id==2}}")
                calculosPlomoPlata(true, table, sumaPesoNetoSeco, contador);
            else
                calculosEstanio(true, table, sumaPesoNetoSeco, contador);
            document.getElementById("form" + formularioId).style.backgroundColor = "#90CAF9";
        } else {
            var index = arraySeleccionados.indexOf(formularioId);
            if (index > -1) {
                arraySeleccionados.splice(index, 1);
            }
            sumaPesoNeto = parseFloat(sumaPesoNeto) - parseFloat(table.rows[parseInt(contador) + 1].cells[6].innerHTML.replace(/,/g, "."));
            document.getElementById("form" + formularioId).style.backgroundColor = "white";

            sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) - parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, "."));
            if("{{$producto->id==1}}")
                calculosZincPlata(false, table, sumaPesoNetoSeco, contador);
            else if("{{$producto->id==2}}")
                calculosPlomoPlata(false, table, sumaPesoNetoSeco, contador);
            else
                calculosEstanio(false, table, sumaPesoNetoSeco, contador);
        }
        contador = parseInt(contador) + 1;
        // alert(arraySeleccionados +"  _______________  " + contador +"  " +table.rows[contador].cells[2].innerHTML);
        document.getElementById("seleccionados").innerHTML = arraySeleccionados.length + " Seleccionados";
        document.getElementById("pesoNetoSeleccionados").innerHTML = "Peso Neto Húmedo: " + sumaPesoNeto.toFixed(2);
    }

    function calculosZincPlata(seleccionado, table, sumaPesoNetoSeco, contador) {
        if (seleccionado) {
            sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaCotizacionZn = parseFloat(sumaCotizacionZn) + (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) + parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeyZn = ((parseFloat(sumaPesoFinoZn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionZnFinal = parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[parseInt(contador) + 1].cells[15].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
        } else {
            sumaCotizacionAg = parseFloat(sumaCotizacionAg) - (parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaCotizacionZn = parseFloat(sumaCotizacionZn) - (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) - parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeyZn = ((parseFloat(sumaPesoFinoZn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionZnFinal = parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) - parseFloat(table.rows[parseInt(contador) + 1].cells[15].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
        }
        document.getElementById("leyAgSeleccionados").innerHTML = "Ag %: " + sumaLeyAg.toFixed(2);
        document.getElementById("cotizacionAgSeleccionados").innerHTML = "Ag Cotización Diaria: " + sumaCotizacionAgFinal.toFixed(2);
        document.getElementById("leyZnSeleccionados").innerHTML = "Zn %: " + sumaLeyZn.toFixed(2);
        document.getElementById("cotizacionZnSeleccionados").innerHTML = "Zn Cotización Diaria: " + sumaCotizacionZnFinal.toFixed(2);
    }

    function calculosPlomoPlata(seleccionado, table, sumaPesoNetoSeco, contador) {
        if (seleccionado) {
            sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaCotizacionPb = parseFloat(sumaCotizacionPb) + (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) + parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeyPb = ((parseFloat(sumaPesoFinoPb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionPbFinal = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[parseInt(contador) + 1].cells[15].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
        } else {
            sumaCotizacionAg = parseFloat(sumaCotizacionAg) - (parseFloat(table.rows[parseInt(contador) + 1].cells[16].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaCotizacionPb = parseFloat(sumaCotizacionPb) - (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));
            sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) - parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeyPb = ((parseFloat(sumaPesoFinoPb) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionPbFinal = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);

            sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) - parseFloat(table.rows[parseInt(contador) + 1].cells[15].innerHTML.replace(/,/g, "."));
            sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
            sumaCotizacionAgFinal = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
        }
        document.getElementById("leyAgSeleccionados").innerHTML = "Ag %: " + sumaLeyAg.toFixed(2);
        document.getElementById("cotizacionAgSeleccionados").innerHTML = "Ag Cotización Diaria: " + sumaCotizacionAgFinal.toFixed(2);
        document.getElementById("leyPbSeleccionados").innerHTML = "Pb %: " + sumaLeyPb.toFixed(2);
        document.getElementById("cotizacionPbSeleccionados").innerHTML = "Pb Cotización Diaria: " + sumaCotizacionPbFinal.toFixed(2);
    }

    function calculosEstanio(seleccionado, table, sumaPesoNetoSeco, contador) {
        if (seleccionado) {
            sumaCotizacionSn = parseFloat(sumaCotizacionSn) + (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoSn = parseFloat(sumaPesoFinoSn) + parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeySn = ((parseFloat(sumaPesoFinoSn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionSnFinal = parseFloat(sumaCotizacionSn) / parseFloat(sumaPesoNetoSeco);
        } else {
            sumaCotizacionSn = parseFloat(sumaCotizacionSn) - (parseFloat(table.rows[parseInt(contador) + 1].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[parseInt(contador) + 1].cells[10].innerHTML.replace(/,/g, ".")));

            sumaPesoFinoSn = parseFloat(sumaPesoFinoSn) - parseFloat(table.rows[parseInt(contador) + 1].cells[12].innerHTML.replace(/,/g, "."));
            sumaLeySn = ((parseFloat(sumaPesoFinoSn) / parseFloat(sumaPesoNetoSeco)) * 100);
            sumaCotizacionSnFinal = parseFloat(sumaCotizacionSn) / parseFloat(sumaPesoNetoSeco);
        }
        document.getElementById("leySnSeleccionados").innerHTML = "Sn %: " + sumaLeySn.toFixed(2);
        document.getElementById("cotizacionSnSeleccionados").innerHTML = "Sn Cotización Diaria: " + sumaCotizacionSnFinal.toFixed(2);
    }
</script>
