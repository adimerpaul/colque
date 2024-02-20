<div id="modalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Fecha Final</h4>
            </div>
            <div class="modal-body">
             {!! Form::hidden('idAsistencia', null, ['class' => 'form-control', 'name'=>'idfecha', 'id'=>'idfecha']) !!}
                 <table class="table table-bordered">
                        <tr>
                            <td><label class="control-label" style="margin-top: 7px">Nombre:</label></td>
                            <td><input style="border: 0; background-color:white" readonly class="form-control" name="nombre" id="nombre"></td>
                        </tr>
                    </table>
                <div class="form-group">    
                    {!! Form::label('fecha', 'Fecha_marcada :*') !!}
                    {!! Form::date('hora_marcada', null, ['class' => 'form-control', 'required', 'name' => 'fecha', 'id' => 'fecha']) !!}
                </div>
                <div class="form-group col-sm-12" style="text-align: right">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'name'=>'guardar','id'=>'guardar','onclick' => "return confirm('¿Está seguro de editar la fecha?')"]) !!}
                </div>
                
            </div>

            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>