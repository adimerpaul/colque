{!! Form::open(['route' => 'producto_minerales.store']) !!}
    <div class="col-sm-10">
        {!! Form::select('mineral_id', [null => 'seleccione...'] +  \App\Models\Material::whereNotIn('id',$producto->productoMinerals->pluck('mineral_id'))->orderBy('nombre')->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control', 'required']) !!}
        {!! Form::hidden('producto_id', $producto->id, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="col-sm-2 text-right">
        <button type="submit" class="btn btn-primary btn-block pull-right">Agregar</button>
    </div>
{!! Form::close() !!}


<div class="col-sm-12">
    <hr>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Material</th>
            <th>Ley mínima</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in minerales" :key="index">
            <td style="width: 300px">@{{ item.mineral.info }}</td>
            <td style="width: 200px"><input  type="number" class="form-control clEmpresa" step="0.001" min="0" :value="item.ley_minima"
                        :id="item.id"   :name="item.ley_minima" onblur="update(this.value, this.id)"/>
            </td>
            <td>
                <input type="checkbox" @click="cambiar(item.id)" :checked="item.es_penalizacion" name="es_penalizado" id="es_penalizado">
                &nbsp; @{{ item.es_penalizacion ? 'Penalizable' : 'Pagable' }}
            </td>

            <td>
                <button class="btn btn-danger btn-sm" @click="eliminar(item.id)"><i
                        class="glyphicon glyphicon-trash"></i></button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@push('scripts')
    <script type="text/javascript">
        function update(valor, id) {
            if (valor !== null && valor !== '') {
                if (valor < 0) {
                    toastr.error('El valor debe ser un número positivo');
                } else {
                    let url = "{{ url('productos-minerales/editar-ley') }}" + "/" + id + '/' + valor;
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            if (data.res) {
                                toastr.success(data.message);
                            }
                            else{
                                toastr.error(data.message);
                            }
                        },
                        error: function () {
                            toastr.error('Sucedió un error, intente de nuevo mas tarde');
                            $("#" + id).val($("#" + id).attr('name'));
                        },
                    });
                }
            } else {
                $("#" + id).val($("#" + id).attr('name'));
            }
        }
    </script>
@endpush
