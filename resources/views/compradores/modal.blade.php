<div id="appComprador">
    <div id="modalComprador" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Comprador</h4>
                </div>
                <div class="modal-body">

                    <div>
                    {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveComprador']) !!}

                    <!-- Nim Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('nit', 'Nit: *') !!}
                            {!! Form::text('nit', null, ['class' => 'form-control', 'maxlength' => '20', 'required', 'v-model' => 'nit']) !!}
                        </div>
                        <!-- Nim Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('nro_nim', 'Nro Nim: *') !!}
                            {!! Form::text('nro_nim', null, ['class' => 'form-control', 'maxlength' => '11', 'required', 'v-model' => 'nro_nim']) !!}
                        </div>

                        <!-- Razon Social Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('razon_social', 'Razon Social: *') !!}
                            {!! Form::text('razon_social', null, ['class' => 'form-control', 'maxlength' => '100', 'required', 'v-model' => 'razon_social']) !!}
                        </div>

                        <!-- Direccion Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('direccion', 'Dirección: ') !!}
                            {!! Form::text('direccion', null, ['class' => 'form-control', 'maxlength' => '150', 'v-model' => 'direccion']) !!}
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
        appComprador = new Vue({
            el: "#appComprador",
            data: {
                razon_social: '',
                nit: '',
                nro_nim: '',
                direccion: '',
                esFormulario: '',
            },
            methods: {
                saveComprador() {
                    let url = "{{ url('compradores') }}";
                    axios.post(url, {
                        razon_social: this.razon_social,
                        nit: this.nit,
                        nro_nim: this.nro_nim,
                        direccion: this.direccion,
                        esFormulario: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.razon_social = '';
                            this.nit = '';
                            this.nro_nim = '';
                            this.direccion = '';
                            $('#modalComprador').modal('hide');
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
