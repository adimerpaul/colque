<div id="modalDetalleHoraExtra" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalle Hora Extra de: 
                    <input style="border: 0; background-color: white; display: inline-block; width: 350px;" readonly class="form-control" name="nombre" id="nombre">
                </h4>
            </div>
            <div class="modal-body">
             {!! Form::hidden('idAsistencia', null, ['class' => 'form-control', 'name'=>'idAsistencia', 'id'=>'idAsistencia']) !!}
             <table class="table table-bordered">
                <thead>
                        <tr>
                            <th colspan="4">Horas Extra (Hrs.) : 
                            <input style="border: 0; background-color:white; display: inline-block; width: auto;" readonly class="form-control" name="horaExtra" id="horaExtra">
                            </th>
                        </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label class="control-label" style="margin-top: 7px">Normal:</label></td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 12px; color: #999;">Horas</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraNormal" id="horaExtraNormal">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 12px; color: #999;">Horas Detalle</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraNormal" id="horaExtraNormal">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 12px; color: #999;">Monto bs</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraNormalMonto" id="horaExtraNormalMonto">
                        </td>
                    </tr>
                    <tr>
                    </tr>

                    <tr id="feriado">
                        <td><label class="control-label" style="margin-top: 7px">Feriados:</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraFeriado" id="horaExtraFeriado">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">hora doble</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraFeriadoprimero" id="horaExtraFeriadoprimero" title="Hora trabaja menor o igual a 8 hrs">
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">hora triple</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraFeriadosegundo" id="horaExtraFeriadosegundo" title="Hora trabajadas arriba de 8 hrs">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">Pago doble</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraFeriadoPrimeroMonto" id="horaExtraFeriadoPrimeroMonto" title="Pago doble">
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">Pago triple</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraFeriadoSegundoMonto" id="horaExtraFeriadoSegundoMonto" title="Pago triple">
                        </td>
                    </tr>
                    <tr id="domingo">
                        <td><label class="control-label" style="margin-top: 7px">Domingo:</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraDomingo" id="horaExtraDomingo">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">hora doble</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraDomingoprimero" id="horaExtraDomingoprimero" title="Hora trabaja menor o igual a 8 hrs">
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">hora triple</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraDomingosegundo" id="horaExtraDomingosegundo" title="Hora trabajadas arriba de 8 hrs">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">Pago doble</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraDomingoprimeromonto" id="horaExtraDomingoprimeromonto" title="Pago doble">
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 9px; color: #999;">Pago triple</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraDomingosegundomonto" id="horaExtraDomingosegundomonto" title="Pago doble">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <label class="control-label" style="margin-top: 7px">Total</label>
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtra" id="horaExtra">
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="horaExtraTotalMonto" id="horaExtraTotalMonto">
                        </td>
                    </tr>
                </tfoot>
            </table>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>