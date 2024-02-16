<div id="appVehiculo">

    <div id="modalVehiculo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span>Nuevo</span> Vehículo</h4>
                </div>
                <div class="modal-body">

                    <div>
                        {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'saveVehiculo']) !!}


                        <div class="form-group col-sm-12">
                            {!! Form::label('placa', 'Placa *:') !!}
                            {!! Form::text('placa', null, ['class' => 'form-control', 'required', 'name'=>'placa', 'v-model'=>'placa', 'maxlength' => '7']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('marca', 'Marca *:') !!}
                            {!! Form::text('marca', null, ['class' => 'form-control', 'required', 'name'=>'marca', 'v-model'=>'marca', 'maxlength' => '30']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('color', 'Color Cabina *:') !!}
                            {!! Form::text('color', null, ['class' => 'form-control', 'required', 'name'=>'color', 'v-model'=>'color', 'maxlength' => '20']) !!}
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
        appVehiculo = new Vue({
            el: "#appVehiculo",
            data: {
                marca: '',
                placa: '',
                color: '',
                esFormulario: '',
            },
            methods: {
                saveVehiculo() {
                    let url = "{{ url('vehiculos') }}";
                    axios.post(url, {
                        marca: this.marca, placa: this.placa, color: this.color, esFormulario: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.marca = '';
                            this.placa = '';
                            this.color = '';
                            $('#modalVehiculo').modal('hide');
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
