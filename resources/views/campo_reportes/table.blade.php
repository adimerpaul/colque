<div class="col-sm-12">
    <hr>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Campo</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in campos" :key="index">
            <td style="width: 400px">@{{ item.nombre }}</td>
            <td>
                <input type="checkbox" @click="cambiar(item.id)" :checked="item.visible" name="es_visible" id="es_visible">
                &nbsp; @{{ item.visible ? 'Visible' : 'No Visible' }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
