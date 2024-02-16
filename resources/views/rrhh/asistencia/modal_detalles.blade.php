<div id="modalDetalle" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cambios realizados</h4>
            </div>
            <div class="modal-body">
             {!! Form::hidden('idAsistencia', null, ['class' => 'form-control', 'name'=>'idAsistencia', 'id'=>'idAsistencia']) !!}
                 <table class="table table-bordered">
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Nombre del personal :</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="nombre" id="nombre"></td>
                        </tr>
                        <tr name="fechaMarcada" id="fechaMarcada">
                            <td><label class="control-label" style="margin-top: 7px">Editado por:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="userEdit" id="userEdit"></td>
                        </tr>
                        <tr name="aprobado" id="aprobado">
                            <td><label class="control-label" style="margin-top: 7px">Aprobador por:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="userRegistro" id="userRegistro"></td>
                        </tr>
                        <tr name="detalle" id="detalle">
                            <td><label class="control-label" style="margin-top: 7px">Descripción:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="observacion" id="observacion"></td>

                        </tr>
                 </table>
            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>