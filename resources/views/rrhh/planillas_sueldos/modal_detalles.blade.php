<div id="modalDetalle" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="font-size: 16px; display: inline-block;">Detalle de descuentos de:</h4>
                    <input style="border: 0; background-color: white; display: inline-block; width: 350px;" readonly class="form-control" name="nombre" id="nombre">

            </div>
            <div class="modal-body">
             {!! Form::hidden('idAsistencia', null, ['class' => 'form-control', 'name'=>'idAsistencia', 'id'=>'idAsistencia']) !!}
             <table class="table table-bordered">
                <thead id="aticiposdescuentos">
                        <tr>
                            <th colspan="3">ANTICIP. OTROS DSCTOS.</th>
                        </tr>
                </thead>
                <tbody id="aticiposdescuentos">
                    <tr>
                        <td><label class="control-label" style="margin-top: 7px">Atraso:</label></td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 12px; color: #999;">Cantidad</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="cantidadAtrasos" id="cantidadAtrasos">
                        </td>
                        <td>
                            <label class="control-label" style="margin-top: -10px; display: block; font-size: 12px; color: #999;">Monto</label>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoAtraso" id="montoAtraso">
                        </td>
                    </tr>
                    <tr id="faltadiacompleto">
                        <td><label class="control-label" style="margin-top: 7px">Faltas(Día Completo):</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="cantidadFaltas" id="cantidadFaltas">
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoFaltas" id="montoFaltas">
                        </td>
                    </tr>
                    <tr id="faltamediodia">
                        <td><label class="control-label" style="margin-top: 7px">Faltas(Medio Día):</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="cantidadFaltasMedioDia" id="cantidadFaltasMedioDia">
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoFaltasMedioDia" id="montoFaltasMedioDia">
                        </td>
                    </tr>
                    <tr id="totalFaltas">
                        <td colspan="2"><label class="control-label" style="margin-top: 7px">Total Faltas:</label></td>
                        
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoFaltasTotal" id="montoFaltasTotal">
                        </td>
                    </tr>
                    
                    <tr id="sinGoseHaber">
                        <td><label class="control-label" style="margin-top: 7px">Permiso sin goce de haber:</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="cantidadPermiso" id="cantidadPermiso">
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoPermiso" id="montoPermiso">
                        </td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="3">AFP (12,71%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label class="control-label" style="margin-top: 7px">Afp:</label></td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="cantidadAfp" id="cantidadAfp" value="0">
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="montoHoraExtra" id="montoAfp">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <label class="control-label" style="margin-top: 7px">Total</label>
                        </td>
                        <td>
                            <input style="border: 0; background-color:white" readonly class="form-control" name="totalDescuento" id="totalDescuento">
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