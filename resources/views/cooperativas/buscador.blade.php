<div id="modalBusquedaClientes" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar productor por cliente</h4>
            </div>
            <div class="modal-body">

                <div>
                    <div class="row">
                        <div class="form-group col-sm-9">
                            <input type="text" placeholder="Buscar por cliente (Nombre, Nit/Ci)" name="buscador" id="buscador"
                                   v-model="buscador" class="form-control"
                                   >
                        </div>
                        <div class="form-group col-sm-3">
                            <button v-on:click="buscar" class="btn btn-default"><i
                                    class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr class="bg-info">
                                <th>Productor</th>
                                <th>Cliente</th>
                                <th>Carnet</th>
                                <th>Celular</th>
                            </tr>

                            <tbody id="myTable">

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        appBusquedaClientes = new Vue({
            el: "#appBusquedaClientes",
            data: {
                buscador: '',
            },
            methods: {
                buscar() {
                    var table = document.getElementById('myTable');
                    let url = "{{ url('get_by_cliente') }}" + "?buscador=" + this.buscador;
                    //let url = '/get_by_cliente?buscador='+this.buscador;
                    $.ajax({
                        method: 'GET',
                        url: url,
                        success: function (response) {
                            table.innerHTML='';
                            for (var i = 0; i < response.length; i++) {
                                var row = `<tr>
							<td>${response[i].razon_social}</td>
                            <td>${response[i].nombre}</td>
                            <td>${response[i].nit}</td>
                            <td>${response[i].celular}</td>
					  </tr>`
                                table.innerHTML += row
                            }
                        }
                    })
                },
            }
        });


    </script>
@endpush

