<div id="appCatalogo">

    <div id="modalCatalogo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Descuento/Bonificación/Retención</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveCatalogo']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('nombre', 'Nombre:') !!}
                            {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'v-model' => 'nombre', 'maxlength' => '100']) !!}
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
        appCatalogo = new Vue({
            el: "#appCatalogo",
            data: {
                nombre: '',
            },
            methods: {
                saveCatalogo() {
                    let url = "{{ url('descuentos-catalogos') }}";
                    axios.post(url, {
                        nombre: this.nombre
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.nombre = '';
                            $('#modalCatalogo').modal('hide');
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
