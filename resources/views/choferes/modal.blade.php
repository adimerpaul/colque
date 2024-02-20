<div id="appChofer">

<div id="modalChofer" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span>Nuevo</span> Conductor</h4>
            </div>
            <div class="modal-body">

                <div>
                    {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveChofer']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('nombre', 'Nombre:') !!}
                        {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'v-model' => 'nombre', 'maxlength' => '100']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('licencia', 'Licencia:') !!}
                        {!! Form::text('licencia', null, ['class' => 'form-control', 'required', 'v-model' => 'licencia', 'maxlength' => '20']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('celular', 'Celular:') !!}
                        {!! Form::number('celular', null, ['class' => 'form-control', 'required', 'v-model' => 'celular', 'maxlength' => '8']) !!}
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
        appChofer = new Vue({
            el: "#appChofer",
            data: {
                nombre: '',
                licencia: '',
                celular: '',
                esFormulario: '',
            },
            methods: {
                saveChofer() {
                    let url = "{{ url('choferes') }}";
                    axios.post(url, {
                        nombre: this.nombre, licencia: this.licencia, celular: this.celular, esFormulario: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.nombre = '';
                            this.licencia = '';
                            this.celular = '';
                            $('#modalChofer').modal('hide');
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
