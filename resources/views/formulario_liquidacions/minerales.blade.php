<tr>
    <td style="width: 10px"></td>
    <td colspan="2">REGALÍA MINERA</td>
    <td class="text-right" style="width: 120px">@{{ redondear(totalMinerales) }} BOB</td>
</tr>
<tr>
    <td style="width: 10px"></td>
    <td colspan="2">
        <div v-if="minerales.length <= 0">
            <div class="alert alert-danger">
                No existen cotizaciones oficiales para los minerales a la fecha de liquidacion
            </div>
        </div>
        <div v-else>
            <table class="table table-bordered" style="margin-bottom: -2px">
                <thead>
                <tr>
                    <th>Mineral</th>
                    <th>Unidad</th>
                    <th>Ley (%)</th>
                    <th>Peso <br>Fino (Kg)</th>
                    <th>Cotización <br>Oficial</th>
                    <th>Valor bruto <br>Venta</th>
                    <th>Alicuota int.</th>
                    <th>Regalía minera</th>
                </tr>
                </thead>
                <tr v-for="(row, index) in minerales" :key="index">
                    <td>@{{ row.simbolo }}</td>
                    <td>@{{ row.unidad }}</td>
                    <td>@{{ redondearTres(row.ley) }}</td>
                    <td>@{{ redondear(row.peso_fino) }}</td>
                    <td>@{{ redondear(row.cotizacion_oficial) }}</td>
                    <td>@{{ redondear(row.valor_bruto_venta) }}</td>
                    <td>@{{ redondear(row.alicuota_interna) }}</td>
                    <td class="text-right"><strong>@{{ redondear(row.sub_total) }}</strong></td>
                </tr>
            </table>
        </div>
    </td>
    <td></td>
</tr>
