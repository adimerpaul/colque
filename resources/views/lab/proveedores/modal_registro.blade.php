<div id="appProveedorRegistro">
    <div id="modalProveedorRegistro" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Proveedor</h4>
                </div>
                <div class="modal-body">

                    <div>
                    {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveProveedor']) !!}

                    <!-- Nim Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('nit', 'Nit: *') !!}
                            {!! Form::number('nit', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'v-model' => 'nit', 'autocomplete' => 'off']) !!}
                        </div>

                        <!-- Razon Social Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => '100', 'required', 'v-model' => 'nombre', 'autocomplete' => 'off']) !!}
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
        appProveedorRegistro = new Vue({
            el: "#appProveedorRegistro",
            data: {
                nombre: '',
                nit: '',
                esFormulario: '',
            },
            methods: {
                saveProveedor() {
                    let url = "{{ url('proveedores-lab') }}";
                    axios.post(url, {
                        nombre: this.nombre,
                        nit: this.nit,
                        esModal: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.nombre = '';
                            this.nit = '';
                            getProveedores();
                            $('#modalProveedorRegistro').modal('hide');
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
