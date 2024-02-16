<div id="modalCrearAnticipo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><b>Solicitar Anticipo</b></h4>
            </div>
            <div class="modal-body">
                 {!! Form::open(['route' => 'anticipos-sueldos.store','method'=>'POST']) !!}
                    <div class="form-group">
                        {!! Form::label('monto', 'Monto: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'min' => '1', 'required','max' => '50000', 'maxlength' => '5', 'oninput'=>'maxLengthCheck(this)']) !!}
                    </div>
            </div>
            
            <div class="modal-footer" style="border-top: none">
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('anticipos-sueldos.index') }}" class="btn btn-default">Cancelar</a>
                            </div>
                 {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>