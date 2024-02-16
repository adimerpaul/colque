<div id="modalTipo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span>Nuevo</span> Tipo</h4>
            </div>
            <div class="modal-body">

                <div>
                    {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'save']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('valor', 'Valor:') !!}
                        {!! Form::text('valor', null, ['class' => 'form-control', 'required', 'v-model' => 'valor', 'maxlength' => '15']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('tabla', 'Tipo:') !!}
                        {!! Form::select('tabla', \App\Patrones\Fachada::tiposTablas(), null, ['class' => 'form-control', 'v-model'=>'tabla', 'required']) !!}
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
@push('scripts')
    <script>
        appTipo = new Vue({
            el: "#appTipo",
            data: {
                valor: '',
                tabla: '',
            },
            methods: {
                save() {
                    let url = "{{ url('tipos') }}";
                    axios.post(url, {
                        valor: this.valor, tabla: this.tabla
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.valor = '';
                            this.tabla = '';
                            $('#modalTipo').modal('hide');
                            location.reload();
                        } else
                            toastr.error("Error! vuelve a intentarlo más tarde1.");
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde2.");
                    });
                },

            }
        });

    </script>
@endpush
