<div id="modalActa" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generar acta de entrega </h4>
            </div>
            <div class="modal-body">
                <div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('Fecha', 'Fecha Adq. Ini.:') !!}
                        {!! Form::date('fechaInicial', date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 year')), ['class' => 'form-control', 'id' => 'fechaInicial']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('Fecha', 'Fecha Adq. Fin.:') !!}
                        {!! Form::date('fechaFinal', date('Y-m-d'), ['class' => 'form-control', 'id' => 'fechaFinal']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('personal_id', 'Responsable :') !!}
                        {!! Form::select('personalId', \App\Patrones\Fachada::getPersonal(), null, ['class' => 'form-control', 'required', 'id' => 'personalId']) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="button" onclick="generarActa()" class="btn btn-primary" id="botonGuardar">
                            Generar
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
