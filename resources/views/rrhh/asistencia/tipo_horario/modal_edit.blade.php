<div id="modalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tipo Horario</h4>
            </div>
            <div class="modal-body">
            <div class="form-group col-sm-12">
                {!! Form::open(['route' => ['tipo-horario.actualizar'], 'id' => 'formularioModal']) !!}

                        {!! Form::hidden('idTipo', null, ['class' => 'form-control', 'name'=>'idTipo', 'id'=>'idTipo']) !!}
                        <div class="row">
                            <div class="form-group col-sm-12">
                                {!! Form::label('descripcion', 'Descripcion:') !!}
                                {!! Form::text('descripcion', null, ['class' => 'form-control', 'required', 'maxlength' => '300', 'required', 'id' => 'idDescripcion']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                {!! Form::close() !!}
            </div>
            </div>
            <div class="modal-footer" style="border-top: none"></div>
        </div>
    </div>
</div>
