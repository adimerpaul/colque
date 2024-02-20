<div >
    <div id="modalClienteRegistro" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Cliente</h4>
                </div>
                <div class="modal-body">

                    <div>

                    <!-- Nim Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('nit', 'Nit: *') !!}
                            {!! Form::number('nit', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'v-model' => 'nit', 'autocomplete' => 'off']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('complemento', 'Complemento: *') !!}
                            {!! Form::text('complemento', null, ['class' => 'form-control', 'maxlength' => '5', 'required', 'v-model' => 'complemento', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Nim Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('celular', 'Celular: *') !!}
                            {!! Form::text('celular', null, ['class' => 'form-control', 'maxlength' => '8', 'required', 'v-model' => 'celular', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Razon Social Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100', 'required', 'v-model' => 'nombre', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Direccion Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('direccion', 'Dirección: ') !!}
                            {!! Form::text('direccion', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'v-model' => 'direccion', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary" @click="saveCliente">Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
