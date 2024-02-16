<div class="box box-primary">
    <div class="box-body">
        <button @click="mostrarHistorial = !mostrarHistorial">@{{ mostrarHistorial ? 'Ocultar' : 'Mostrar' }} historial</button>
        <div class="row" v-if="mostrarHistorial">

            <div class="col-sm-12 text-center">
                <h4>HISTORIAL DE EVENTOS</h4>
            </div>

            <div class="table-responsive col-sm-12">
                <table class="table table-striped table-bordered" id="historials-table">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Acción</th>
                        <th>Detalle</th>
                        <th>Usuario</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(historial, index) in historiales" :key="index">
                        <td>@{{ getDateYear(historial.created_at) }}</td>
                        <td>@{{ historial.accion }}</td>
                        <td v-html="historial.observacion"></td>
                        <td style="width: 100px">@{{ historial.users.email }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
