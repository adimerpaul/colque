<div id="appBases">
    <div class="col-sm-12">
        <hr style="height: 2px; background-color: black">
        <h4 class="text-center">TÉRMINOS DE BASE</h4>

    </div>


    {!! Form::open(['method' => 'POST', 'id'=>'frmBase', 'v-on:submit.prevent' => 'saveBase']) !!}
    <div class="form-group col-sm-4">
        {!! Form::label('lme', 'Lme Pb Mínimo: *') !!}
        {!! Form::number('lme_pb_minimo', null, ['class' => 'form-control', 'v-model' => 'lme_pb_minimo', 'maxlength' => '10', 'required', 'min' =>'0',]) !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('lme', 'Lme Pb Máximo: *') !!}
        {!! Form::number('lme_pb_maximo', null, ['class' => 'form-control', 'v-model' => 'lme_pb_maximo', 'maxlength' => '10', 'required', 'min' =>'0',]) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('base', 'Base: *') !!}
        {!! Form::number('base', null, ['class' => 'form-control', 'v-model' => 'base', 'maxlength' => '10', 'required', 'min' =>'0',]) !!}
    </div>
    <!-- Submit Field -->
    <div class="form-group col-sm-4" style="margin-top: 25px">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'btnGuardarBase']) !!}
    </div>

    {!! Form::close() !!}
    <div class="table-responsive col-sm-12">
        <table id="bases-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Lme Pb Mínimo</th>
                <th>Lme Pb Máximo</th>
                <th>Base</th>
            </tr>
            </thead>
            <tbody >
            <tr v-for="(row, index) in bases" :key="index">
                <td class="text-left">@{{ row.lme_pb_minimo }}</td>
                <td class="text-left">@{{ row.lme_pb_maximo }}</td>
                <td style="width: 50px" class="text-left">@{{ row.base }}</td>
                <td style="width: 80px">
                    <button title="Eliminar" @click="eliminarBase(row.id)"
                            class="btn btn-danger btn-xs">
                        <i class="glyphicon glyphicon-trash"></i></button>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        appContrato = new Vue({
            el: "#appBases",
            data: {
                bases: [],
                lme_pb_minimo: '',
                base: '',
                lme_pb_maximo: ''
            },
            mounted() {
                this.getBases();
            },
            methods: {
                getBases() {
                    let url = "{{ url('bases-plomo-plata') }}";
                    axios.get(url).then(response => {
                        this.bases = response.data;
                    });
                },

                saveBase() {
                    let url = "{{ url('bases-plomo-plata') }}";
                    axios.post(url, {
                        lme_pb_minimo: this.lme_pb_minimo,
                        base: this.base,
                        lme_pb_maximo: this.lme_pb_maximo
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.lme_pb_minimo = '';
                            this.base = '';
                            this.lme_pb_maximo = '';
                            this.getBases();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },

                eliminarBase(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/bases-plomo-plata/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getBases();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },
            }
        });
    </script>
@endpush
