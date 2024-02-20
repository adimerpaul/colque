<div id="appTerminos">
    <div class="col-sm-12">
        <hr style="height: 2px; background-color: black">
        <h4 class="text-center">TÉRMINOS GENERALES</h4>

    </div>


    {!! Form::open(['method' => 'POST', 'id'=>'frmTermino', 'v-on:submit.prevent' => 'saveTermino']) !!}
    <div class="form-group col-sm-4">
        {!! Form::label('minima', 'Lme Pb Mínimo: *') !!}
        {!! Form::number('ley_minima', null, ['class' => 'form-control', 'v-model' => 'ley_minima', 'maxlength' => '8', 'required', 'min' =>'0', 'step'=>'0.001']) !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('maxima', 'Lme Pb Máximo: *') !!}
        {!! Form::number('ley_maxima', null, ['class' => 'form-control', 'v-model' => 'ley_maxima', 'maxlength' => '8', 'required', 'min' =>'0', 'step'=>'0.001']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('maquila', 'Maquila: *') !!}
        {!! Form::number('maquila', null, ['class' => 'form-control', 'v-model' => 'maquila', 'maxlength' => '8', 'required', 'min' =>'0']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('transporte', 'Transporte: *') !!}
        {!! Form::number('transporte', null, ['class' => 'form-control', 'v-model' => 'transporte', 'maxlength' => '8', 'required', 'min' =>'0', 'step'=>'0.001']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('costo_refinacion', 'Costo refinacion: *') !!}
        {!! Form::number('costo_refinacion', null, ['class' => 'form-control', 'v-model' => 'costo_refinacion', 'maxlength' => '8', 'required', 'min' =>'0', 'step'=>'0.001']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('rollback', 'Roll back: *') !!}
        {!! Form::number('rollback', null, ['class' => 'form-control', 'v-model' => 'rollback', 'maxlength' => '8', 'required', 'min' =>'0', 'step'=>'0.001']) !!}
    </div>
    <!-- Submit Field -->
    <div class="form-group col-sm-2" style="margin-top: 25px">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'btnGuardarTermino']) !!}
    </div>

    {!! Form::close() !!}
    <div class="table-responsive col-sm-12">
        <table id="terminos-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Ley Mínima</th>
                <th>Ley Máxima</th>
                <th>Maquila</th>
                <th>Transporte</th>
                <th>Costo Refinacion</th>
                <th>Roll Back</th>
            </tr>
            </thead>
            <tbody >
            <tr v-for="(row, index) in terminos" :key="index">
                <td class="text-left">@{{ row.ley_minima }}</td>
                <td class="text-left">@{{ row.ley_maxima }}</td>
                <td class="text-left">@{{ row.maquila }}</td>
                <td class="text-left">@{{ row.transporte }}</td>
                <td class="text-left">@{{ row.costo_refinacion }}</td>
                <td class="text-left">@{{ row.rollback }}</td>

                <td style="width: 80px">
                    <button title="Eliminar" @click="eliminarTermino(row.id)"
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
            el: "#appTerminos",
            data: {
                terminos: [],
                ley_minima: '',
                maquila: '',
                ley_maxima: '',
                transporte: '',
                costo_refinacion: '',
                rollback: ''
            },
            mounted() {
                this.getTerminos();
            },
            methods: {
                getTerminos() {
                    let url = "{{ url('terminos-plomo-plata') }}";
                    axios.get(url).then(response => {
                        this.terminos = response.data;
                    });
                },

                saveTermino() {
                    let url = "{{ url('terminos-plomo-plata') }}";
                    axios.post(url, {
                        ley_minima: this.ley_minima,
                        maquila: this.maquila,
                        ley_maxima: this.ley_maxima,
                        transporte: this.transporte,
                        costo_refinacion: this.costo_refinacion,
                        rollback: this.rollback

                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.ley_minima = '';
                            this.maquila = '';
                            this.ley_maxima = '';
                            this.transporte = '';
                            this.costo_refinacion = '';
                            this.rollback = '';

                            this.getTerminos();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },

                eliminarTermino(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/terminos-plomo-plata/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getTerminos();
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
