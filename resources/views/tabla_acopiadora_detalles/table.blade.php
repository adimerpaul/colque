<div class="table-responsive" style="width: 100%">
    <table class="table table-striped table-bordered" id="tablaAcopiadoraDetalles-table">
        <thead class="thead-dark">
        <tr>
            <th class="text-center header" scope="col">Cot.</th>
            @if(isset($ta->l_0_incremental) && isset($ta->l_0_inicial))
                <th class="text-center header" scope="col">
                    <small>{{ round($ta->l_0_incremental,2) }} </small> <br> 0%
                </th>
            @endif
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_5_incremental, 2) }} </small> <br> 5%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_10_incremental, 2) }} </small> <br> 10%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_15_incremental, 2) }} </small> <br> 15%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_20_incremental, 2) }} </small> <br> 20%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_25_incremental, 2) }} </small> <br> 25%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_30_incremental, 2) }} </small> <br> 30%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_35_incremental, 2) }} </small> <br> 35%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_40_incremental, 2) }} </small> <br> 40%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_45_incremental, 2) }} </small> <br> 45%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_50_incremental, 2) }} </small> <br> 50%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_55_incremental, 2) }} </small> <br> 55%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_60_incremental, 2) }} </small> <br> 60%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_65_incremental, 2) }} </small> <br> 65%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_70_incremental, 2) }} </small> <br> 70%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_75_incremental, 2) }} </small> <br> 75%
            </th>
            <th class="text-center header" scope="col">
                <small>{{ round($ta->l_80_incremental, 2) }} </small> <br> 80%
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($ta->tablaAcopiadoraDetalles as $tad)
            <tr>
                <td style="max-width: 50px"><strong>{{ round($tad->cotizacion, 2) }}</strong></td>
                @if(isset($tad->l_0))
                <td style="min-width: 50px">{{ $tad->l_0 }}</td>
                @endif
                <td style="min-width: 50px">{{ round($tad->l_5, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_10, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_15, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_20, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_25, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_30, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_35, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_40, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_45, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_50, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_55, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_60, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_65, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_70, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_75, 2) }}</td>
                <td style="min-width: 50px">{{ round($tad->l_80, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
