<div id="appCliente">

    <div id="modalCliente" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Cliente</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveCliente', 'files' =>true]) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'v-model' => 'nombre', 'maxlength' => '100']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('nit', 'CI *:') !!}
                            {!! Form::text('nit', null, ['class' => 'form-control', 'required', 'v-model' => 'nit', 'maxlength' => '20']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('celular', 'Celular: *') !!}
                            {!! Form::number('celular', null, ['class' => 'form-control', 'maxlength' => '15', 'min' => 0, 'required', 'v-model' => 'celular']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('cooperativa', 'Productor *:') !!}
                            {!! Form::select('cooperativa_id', [null => 'Seleccione...']  + \App\Models\Cooperativa::get()->pluck('razon_social', 'id')->toArray(), null, ['class' => 'form-control', 'required', 'v-model' => 'cooperativa_id']) !!}

                        </div>


                        <div class="col-sm-12">
                            <div class="thumbnail">

                                <div class="caption text-center">
                                    <img id="img_destino" alt="firma" style="height: 150px" required>
                                    <div class="foto_boton file btn btn-lg btn-primary">
                                        <i class="glyphicon glyphicon-paperclip"></i> Cargar firma
                                        <input id="foto_input" class="foto_input" type="file" name="foto_input"
                                               accept="image/x-png"/>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>
@push('scripts')
    <script>
        appCliente = new Vue({
            el: "#appCliente",
            data: {
                nombre: '',
                nit: '',
                cooperativa_id: '',
                celular: '',
                esFormulario: '',
            },
            methods: {
                saveCliente() {
                    var firmaSrc = document.getElementById("img_destino").src;
                    if (firmaSrc == '')
                        return alert('La firma es obligatoria');
                        let url = "{{ url('clientes') }}";
                    axios.post(url, {
                        nombre: this.nombre,
                        nit: this.nit,
                        celular: this.celular,
                        foto_input: firmaSrc,
                        cooperativa_id: this.cooperativa_id,
                        esFormulario: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            firmaSrc = '';
                            this.nombre = '';
                            this.nit = '';
                            this.cooperativa_id = '';
                            this.celular = '';
                            $('#modalCliente').modal('hide');
                        } else
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                    }).catch(e => {
                        alert(formarListaDeErrores(e.response.data.errors));
                    });

                },

            }
        });

    </script>
@endpush
