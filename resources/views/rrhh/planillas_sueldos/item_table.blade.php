
<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <p id="campos" style="font-size: 1px; visibility: hidden"></p>
            <th colspan="41" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>PLANILLA DE SUELDOS Y SALARIOS
                <br>Correspondiente al mes de {{ \App\Patrones\Fachada::getMesEspanol($mes)}} de {{$anio}}
                <br>(Expresado en Bolivianos)
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="4">N°</th>
            <th rowspan="4" style="border: 1px solid black; vertical-align: middle; width: 5px; height: 50px;">DOCUMENTO IDENTIDAD</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="fechaLiquidacion">EXP</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="loteCompra">APELLIDOS Y NOMBRES</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="loteVenta">NACIONALIDAD</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="proveedor">FECHA NACIMIENTO</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="cliente">SEXO(F/M)</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="pesoBrutoHumedo">OCUPACION DESEMPEÑA</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="proveedor">FECHA INGRESO</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="pesoNetoHumedo">DIAS PAG.(MES)</th>
            <th rowspan="4" style=" border: 1px solid black; vertical-align: middle;" id="humedadPorcentaje">HRS. PAG. (DIA)</th>
            <th rowspan="3" style=" border: 1px solid black; vertical-align: middle;">HABER BASICO</th>
            <th rowspan="3" style=" border: 1px solid black; vertical-align: middle;" id="tara">BONO DE ANTIG.</th>
            <th colspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" id="cu" class="cu">HORAS EXTRAS</th>
            <th colspan="3" style=" border: 1px solid black; vertical-align: middle; text-align: center" id="retencionesDeLey" class="retencionesDeLey">BONOS</th>
            <th rowspan="1" style=" border: 1px solid black; vertical-align: middle; text-align: center" id="proveedor">TOTAL GANADO</th>
            <th colspan="4" style=" border: 1px solid black; vertical-align: middle; text-align: center" id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS </th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle;" id="totalRetencionesDescuento">TOTAL DSCTOS.</th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle;" id="totalRetencionesDescuento">LIQUIDO PAGABLE</th>
        </tr>
        <tr>
            <th rowspan="3" style=" border: 1px solid black; vertical-align: middle; text-align: center "  id="cu" class="cu">N°</th>
            <th rowspan="2"style=" border: 1px solid black; vertical-align: middle; text-align: center" id="cu" class="cu">MONTO PAGADO</th>

            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="retencionesDeLey">BONO PROD.</th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="retencionesDeLey">DOMINIC.</th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="retencionesDeLey">OTROS BONOS</th>

            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="descuentosInstitucionales">A+B+C+D+E+F</th>

            <th style=" border: 1px solid black; vertical-align: middle; text-align: center" class="descuentosInstitucionales">AFP</th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="descuentosInstitucionales">APORTE SOLID.</th>
            <th style=" border: 1px solid black; vertical-align: middle; text-align: center" class="descuentosInstitucionales">RC-IVA</th>
            <th rowspan="2" style=" border: 1px solid black; vertical-align: middle; text-align: center" class="bonificaciones">ANTICIP. OTROS DSCTOS.</th>
        </tr>
        <tr> 
        <th style=" border: 1px solid black; text-align: center; vertical-align: middle; text-align: center" class="bonificaciones">12,71%</th>
        <th style=" border: 1px solid black; text-align: center; vertical-align: middle; text-align: center" class="bonificaciones">13%</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(K)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(L)</th>



        </tr>
        <tr>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(A)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(B)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(C)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(D)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(E)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(F)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(G)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(H)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(I)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(J)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(K)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(H+I+J)</th>
        <th style=" border: 1px solid black; text-align: center;" class="bonificaciones">(G-K)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($planillas as $planilla)
            <tr>
            <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->ci}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->expedido}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->nombre_completo}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->nacionalidad}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->fecha_nacimiento}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->sexo}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->cargo}}</td>
            <td style=" border: 1px solid black;">{{$planilla->personal->fecha_ingreso}}</td>
            <td style=" border: 1px solid black;">{{$planilla->dias_trabajados}}</td>
            <td style=" border: 1px solid black;">{{$planilla->horas_trabajadas}}</td>
            <td style=" border: 1px solid black;">{{$planilla->haber_basico}}</td>
            <td style=" border: 1px solid black;">{{$planilla->bono_antiguedad}} </td>
            <td style=" border: 1px solid black;">
            @if($planilla->numero_horas_extra>0)
                    @php
                        $normal= \App\Patrones\Fachada::horasExtra($planilla->personal_id,$planilla->fecha_planilla);
                        $feriadoPrimero = \App\Patrones\Fachada::horasExtraFeriadoDetallePrimero($planilla->personal_id, $planilla->fecha_planilla);
                        $feriadoSegundo = \App\Patrones\Fachada::horasExtraFeriadoDetalleSegundo($planilla->personal_id,$planilla->fecha_planilla);
                        $domingoPrimero = \App\Patrones\Fachada::horasExtraDomingoPrimero($planilla->personal_id,$planilla->fecha_planilla);
                        $domingoSegundo = \App\Patrones\Fachada::horasExtraDomingoSegundo($planilla->personal_id,$planilla->fecha_planilla);
                    @endphp
                <a  href="#" 
                    data-target="#modalDetalleHoraExtra"
                    style="margin-top: 7px;"
                    data-txtid="{{$planilla->id}}"
                    data-txtnombre="{{$planilla->personal->nombre_completo}}"
                    data-txthoraextra="{{$planilla->numero_horas_extra}}"
                    data-txthoraextranormal="{{$normal}}"
                    data-txthoraextranormalomonto="{{ (\App\Patrones\Fachada::horaExtraMonto($normal, $planilla->haber_basico,2))}}"
                    data-txthoraextraferiado="{{\App\Patrones\Fachada::horasExtraFeriado($planilla->personal_id,$planilla->fecha_planilla)}}"
                    data-txthoraextraferiadoprimero="{{$feriadoPrimero}}"
                    data-txthoraextraferiadoprimeromonto="{{ (\App\Patrones\Fachada::horaExtraMonto($feriadoPrimero, $planilla->haber_basico,2))}}"
                    data-txthoraextraferiadosegundo="{{$feriadoSegundo}}"
                    data-txthoraextraferiadosegundomondo="{{(\App\Patrones\Fachada::horaExtraMonto($feriadoSegundo,$planilla->haber_basico,3))}}"
                    data-txthoraextradomingo="{{\App\Patrones\Fachada::horasExtraDomingo($planilla->personal_id,$planilla->fecha_planilla)}}"
                    data-txthoraextradomingoprimero="{{$domingoPrimero}}"
                    data-txthoraextradomingoprimeromonto="{{(\App\Patrones\Fachada::horaExtraMonto($domingoPrimero,$planilla->haber_basico,2))}}"
                    data-txthoraextradomingosegundo="{{$domingoSegundo}}"
                    data-txthoraextradomingosegundomonto="{{(\App\Patrones\Fachada::horaExtraMonto($domingoSegundo,$planilla->haber_basico,3))}}"
                    data-txthoraextratotalmonto="{{$planilla->hora_extra_monto_pagado}}"
                    data-toggle="modal">{{$planilla->numero_horas_extra}}
                </a>
                @else
                {{$planilla->numero_horas_extra}}
                @endif</td>
            <td style=" border: 1px solid black;">{{$planilla->hora_extra_monto_pagado}}</td>
            <td style=" border: 1px solid black;">{{$planilla->bono_prod}} </td>
            <td style=" border: 1px solid black;">{{$planilla->dominical}}</td>
            <td style=" border: 1px solid black;">{{$planilla->otros_bonos}}</td>
            <td style=" border: 1px solid black;">{{$planilla->total_ganado}}</td>
            <td style=" border: 1px solid black;">{{$planilla->afp}}</td>
            <td style=" border: 1px solid black;">{{$planilla->aporte_solidario}}</td>
            <td style=" border: 1px solid black;">{{$planilla->rc_iva}}</td>
            <td style=" border: 1px solid black;">{{$planilla->anticipos_otros_descuentos}}</td>
            <td style=" border: 1px solid black;">
                @if($planilla->total_descuentos>0)
                <a  href="#" 
                    data-target="#modalDetalle"
                    style="margin-top: 7px;"
                    data-txtid="{{$planilla->id}}"
                    data-txtnombre="{{$planilla->personal->nombre_completo}}"
                    data-txtatraso="{{$planilla->atrasos}}"
                    data-txtatrasomonto="{{$planilla->atrasos_monto}}"
                    data-txtfaltas="{{$planilla->faltas}}"
                    data-txtfaltasmonto="{{$planilla->faltas_monto}}"
                    data-txtfaltasmediodia="{{$planilla->faltas_medio_dia}}"
                    data-txtfaltasmediodiamonto="{{$planilla->faltas_medio_dia_monto}}"
                    data-txtfaltasmontototal="{{$planilla->faltas_monto_total}}"

                    data-txtpermiso="{{$planilla->permiso_sin_goce_haber}}"
                    data-txtpermisomonto="{{$planilla->permiso_sin_goce_haber_monto}}"
                    data-txtafp="{{$planilla->afp}}"
                    data-txtdescuentototal="{{$planilla->total_descuento}}"
                    data-toggle="modal">{{$planilla->total_descuentos}}
                </a>
                @else
                {{$planilla->total_descuentos}}
                @endif

            </td>
            <td style=" border: 1px solid black;">{{$planilla->liquido_pagable}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="11" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
            </b>
            </td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("haber_basico"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("bono_antiguedad"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("numero_horas_extra"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("hora_extra_monto_pagado"))?></b></td>
            <td style=" border: 1px solid black;"><b></b></td>
            <td style=" border: 1px solid black;"><b></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("otros_bonos"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("total_ganado"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("afp"))?></b></td>
            <td style=" border: 1px solid black;"><b></b></td>
            <td style=" border: 1px solid black;"><b></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("anticipos_otros_descuentos"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("total_descuentos"))?></b></td>
            <td style=" border: 1px solid black;"><b><?php print($planillas->sum("liquido_pagable"))?></b></td>

        </tr>

        
        </tbody>
    </table>
</div>
@include("rrhh.planillas_sueldos.modal_detalles")
@include("rrhh.planillas_sueldos.modal_detalles_hora_extra")

<script>
    $('#modalDetalle').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')
            var atraso = button.data('txtatraso')
            var atrasomonto = button.data('txtatrasomonto')
            var permiso = button.data('txtpermiso')
            var permisomonto = button.data('txtpermisomonto')
            var faltas = button.data('txtfaltas')
            var faltasmonto = button.data('txtfaltasmonto')
            var faltasmediodia = button.data('txtfaltasmediodia')
            var faltasmediodiamonto = button.data('txtfaltasmediodiamonto')
            var faltasmontototal= button.data('txtfaltasmontototal')
            var afp = button.data('txtafp')
            var sumadescuentos = button.data('txtdescuentototal')

            var modal = $(this)
            var faltaDiaCompleto = modal.find('#faltadiacompleto');
            var faltaMedioDia = modal.find('#faltamediodia');
            var singosehaber = modal.find('#sinGoseHaber');
            var totalfaltas = modal.find('#totalFaltas');
            var aticiposdescuentos = modal.find('#aticiposdescuentos');
            modal.find('.modal-body #idAsistencia').val(id);
            modal.find('.modal-header #nombre').val(nombre);
            modal.find('.modal-body #cantidadAtrasos').val(atraso);
            modal.find('.modal-body #montoAtraso').val(atrasomonto);
            modal.find('.modal-body #cantidadFaltas').val(faltas);
            modal.find('.modal-body #montoFaltas').val(faltasmonto);
            modal.find('.modal-body #cantidadFaltasMedioDia').val(faltasmediodia);
            modal.find('.modal-body #montoFaltasMedioDia').val(faltasmediodiamonto);
            modal.find('.modal-body #montoFaltasTotal').val(faltasmontototal);
            modal.find('.modal-body #cantidadPermiso').val(permiso);
            modal.find('.modal-body #montoPermiso').val(permisomonto);
            modal.find('.modal-body #montoAfp').val(afp);
            modal.find('.modal-body #totalDescuento').val(sumadescuentos);
            totalfaltas.hide();    
        if (faltasmediodiamonto !== 0) {
        // Muestra la tabla
            faltaMedioDia.show();
            totalfaltas.show();

        } else {
        // Oculta la tabla si el valor es cero
            faltaMedioDia.hide();
        }
        if (faltasmonto !== 0) {
        // Muestra la tabla
            faltaDiaCompleto.show();
            totalfaltas.show();
        } else {
        // Oculta la tabla si el valor es cero
            faltaDiaCompleto.hide();
        }
        if (permisomonto !== 0) {
        // Muestra la tabla
                singosehaber.show();
        } else {
        // Oculta la tabla si el valor es cero
                singosehaber.hide();
        }
        


        })
    $('#modalDetalleHoraExtra').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('txtid')
        var nombre = button.data('txtnombre')
        var horaextra = button.data('txthoraextra')
        var horaextranormal = button.data('txthoraextranormal')
        var horaextranormalmonto = button.data('txthoraextranormalomonto')
        var horaextraferiado = button.data('txthoraextraferiado')
        var horaextraferiadoprimero = button.data('txthoraextraferiadoprimero')
        var horaextraferiadoprimeromonto = button.data('txthoraextraferiadoprimeromonto')
        var horaextraferiadosegundo = button.data('txthoraextraferiadosegundo')
        var horaextraferiadosegundomonto = button.data('txthoraextraferiadosegundomondo')
        var horaextradomingo = button.data('txthoraextradomingo')
        var horaextradomingoprimero = button.data('txthoraextradomingoprimero')
        var horaextradomingoprimeromonto = button.data('txthoraextradomingoprimeromonto')
        var horaextradomingosegundo = button.data('txthoraextradomingosegundo')
        var horaextradomingosegundomonto = button.data('txthoraextradomingosegundomonto')
        var horaextratotalmonto = button.data('txthoraextratotalmonto')
        var modal = $(this)
        var tablaFeriado = modal.find('#feriado');
        var tablaDomingo = modal.find('#domingo');

        modal.find('.modal-body #idAsistencia').val(id);
        modal.find('.modal-header #nombre').val(nombre);
        modal.find('.modal-body #horaExtra').val(horaextra);
        modal.find('.modal-body #horaExtraNormal').val(horaextranormal);
        modal.find('.modal-body #horaExtraNormalMonto').val(horaextranormalmonto);
        modal.find('.modal-body #horaExtraFeriado').val(horaextraferiado);
        modal.find('.modal-body #horaExtraFeriadoprimero').val(horaextraferiadoprimero);
        modal.find('.modal-body #horaExtraFeriadoPrimeroMonto').val(horaextraferiadoprimeromonto);
        modal.find('.modal-body #horaExtraFeriadosegundo').val(horaextraferiadosegundo);
        modal.find('.modal-body #horaExtraFeriadoSegundoMonto').val(horaextraferiadosegundomonto);
        modal.find('.modal-body #horaExtraDomingo').val(horaextradomingo);
        modal.find('.modal-body #horaExtraDomingoprimero').val(horaextradomingoprimero);
        modal.find('.modal-body #horaExtraDomingoprimeromonto').val(horaextradomingoprimeromonto);
        modal.find('.modal-body #horaExtraDomingosegundo').val(horaextradomingosegundo);
        modal.find('.modal-body #horaExtraDomingosegundomonto').val(horaextradomingosegundomonto);
        modal.find('.modal-body #horaExtraTotalMonto').val(horaextratotalmonto);
        if (horaextraferiado !== 0) {
        // Muestra la tabla
            tablaFeriado.show();
        } else {
        // Oculta la tabla si el valor es cero
            tablaFeriado.hide();
        }
        if (horaextradomingo !== 0) {
        // Muestra la tabla
            tablaDomingo.show();
        } else {
        // Oculta la tabla si el valor es cero
            tablaDomingo.hide();
        }
    })
</script>