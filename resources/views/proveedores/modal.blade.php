<div id="appProveedor">
    <div id="modalProveedor" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
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

                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre: *') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'v-model' => 'nombre', 'maxlength' => '100']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('nit', 'Nit/Ci: *') !!}
                            {!! Form::text('nit', null, ['class' => 'form-control', 'required', 'v-model' => 'nit', 'maxlength' => '20']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('empresa', 'Empresa: *') !!}
                            {!! Form::text('empresa', null, ['class' => 'form-control', 'required', 'v-model' => 'empresa', 'maxlength' => '100']) !!}
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
        appProveedor = new Vue({
            el: "#appProveedor",
            data: {
                nombre: '',
                nit: '',
                empresa: '',
                esFormulario: '',
            },
            methods: {
                saveProveedor() {
                    let url = "{{ url('proveedores') }}";
                    axios.post(url, {
                        nombre: this.nombre, nit: this.nit, empresa: this.empresa, esFormulario: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.nombre = '';
                            this.nit = '';
                            this.empresa = '';
                            $('#modalProveedor').modal('hide');
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
