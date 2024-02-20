<div class="table-responsive" style="padding: 20px">
    <!-- Laboratorio Field -->

    <div  style="text-align: center">
        <h3> Costo BOB: {{ $costo->laboratorio}}</h3>

    </div>


    <table class="table table-striped table-bordered" id="formularioLiquidacions-table">
        <thead>

        <tr>
            <th style="width: 130px"></th>
            <th v-for="laboratorio in laboratorios" :key="laboratorio.id" class="text-center"> @{{
                laboratorio.info_mineral }}
                <select class="form-control select2" :name="laboratorio.mineral_id"
                        :id="laboratorio.formulario_liquidacion_id"
                        onchange="updateUnidad(this.value, this.id, this.name)">
                    <option :value="laboratorio.unidad">@{{ laboratorio.unidad }}</option>
                    <option value="D.M">D.M</option>
                    <option value="%">%</option>
                    <option value="G/T">G/T</option>
                    <option value="PPM">PPM</option>
                </select>
            </th>
            <th class="text-center">Humedad
                {!! Form::text('humedad', '%', ['class' => 'form-control', 'disabled']) !!}

            </th>
        </tr>

        </thead>
        <tbody>
        <tr>

        </tr>
        <tr>
            <td><strong>Empresa</strong>

            </td>
            <td v-for="empresa in laboratoriosEmpresas" :key="empresa.id">
                <input type="number" class="form-control clEmpresa" step="0.001" min="0" :value="empresa.valor"
                       :id="empresa.id" :style="empresa.bloqueo_humedad"
                       :name="empresa.valor" onblur="update(this.value, this.id)"/>
            </td>
        </tr>
        <tr>
            <td><strong>Cliente</strong>

            </td>
            <td v-for="cliente in laboratoriosClientes" :key="cliente.id">
                <input type="number" class="form-control clCliente" step="0.001" min="0" :value="cliente.valor"
                       :id="cliente.id" :style="cliente.bloqueo_humedad"
                       :name="cliente.valor" onblur="update(this.value, this.id)"/>
            </td>
        </tr>


        <tr style="background-color: #B9F6CA">
            <td><strong>PROMEDIO</strong></td>
            <td v-for="row in formulario.laboratorio_promedio" :key="row.mineral_id">
                <strong> @{{ redondear(row.promedio) }} </strong><br>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" class="text-center">
                <div class='btn-group text-center'>

                    <a onclick="mostrarDirimicion()" id="btnMostrarDirimicion"
                       class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-plus"></i> Agregar Dirimición</a>
                    <a onclick="ocultarDirimicion()" id="btnOcultarDirimicion"
                       class='btn btn-danger btn-xs'><i class="glyphicon glyphicon-minus"></i> Quitar Dirimición</a>
                </div>
            </td>
        </tr>
        <tr id="dirimicion">
            <td><strong>Dirimición</strong></td>
            <td v-for="dirimicion in laboratoriosDirimiciones" :key="dirimicion.id">
                <input type="number" class="form-control" step="0.001" min="0" :value="dirimicion.valor"
                       :id="dirimicion.id" :style="dirimicion.bloqueo_humedad"
                       name="valorDirimicion" onblur="update(this.value, this.id)"/>
            </td>
        </tr>

        </tbody>
    </table>
    <div  style="text-align: right">
    <button onclick="finalizarRefrescar()"
            class='btn btn-success' ><i class="glyphicon glyphicon-ok"></i> Finalizar y Actualizar</button>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let url = "{{ url('contarDirimicion') }}" + "/" + "{{ $formularioLiquidacion->id }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    if (data === 0) {
                        $("#dirimicion").hide();
                        $("#btnOcultarDirimicion").hide();
                    } else {
                        $("#btnMostrarDirimicion").hide();
                    }
                },
            });
        });

        function getPrecioLaboratorio() {
            let laboratorioId = document.getElementById('laboratorioId').value;
            let letraProducto = "{{ substr($formularioLiquidacion->producto, 0, 1)}}";
            let url = "{{ url('laboratorioPrecios') }}" + "/" + laboratorioId + "/" + letraProducto;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    document.getElementById('montoLaboratorio').value = data;
                },
            });
        }

        function getPrecioDirimicion() {
            let laboratorioId = document.getElementById('laboratorioIdDirimicion').value;
            let letraProducto = "{{ substr($formularioLiquidacion->producto, 0, 1)}}";
            let url = "{{ url('laboratorioPrecios') }}" + "/" + laboratorioId + "/" + letraProducto;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    document.getElementById('montoLaboratorioDirimicion').value = data;
                },
            });
        }



        function mostrarDirimicion() {
            $("#dirimicion").show();
            $("#btnMostrarDirimicion").hide();
            $("#btnOcultarDirimicion").show();
        }

        function ocultarDirimicion() {
            if (confirm("Seguro que quiere quitar la dirimición?")) {
                let url = "{{ url('laboratorios/quitarDirimicion') }}" + "/" + "{{ $formularioLiquidacion->id }}";
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $("#dirimicion").hide();
                 //       document.getElementById('montoLaboratorioDirimicion').value = 0;
                //        document.getElementById('laboratorioIdDirimicion').value = null;

                        $("#btnMostrarDirimicion").show();
                        $("#btnOcultarDirimicion").hide();
                        appFormulario.updateValorPorTonelada();
                        appFormulario.getResumen();
                        appFormulario.cargarDescuentosBonificaciones();

                        document.getElementsByName('valorDirimicion').forEach(function (ele, idx) {
                            ele.value = '';
                        })
                    },
                });

            }

        }

        function update(valor, idLab) {
            if (valor !== null && valor !== '') {
                if (valor < 0) {
                    toastr.error('El valor debe ser un número positivo');
                } else {
                    let url = "{{ url('laboratorios/actualizar') }}" + "/" + idLab + '/' + valor;
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            if (data.message !== '') {
                                if(data.res)
                                    toastr.success(data.message);
                                else
                                    toastr.error(data.message);
                            }
                        },
                        error: function () {
                            $("#" + idLab).val($("#" + idLab).attr('name'));
                        },
                    });
                }
            } else {
                $("#" + idLab).val($("#" + idLab).attr('name'));
            }
        }


        function finalizarRefrescar(){
            appFormulario.getLaboratorios();
            appFormulario.updateValorPorTonelada();
            appFormulario.getResumen();
            appFormulario.cargarDescuentosBonificaciones();
            appFormulario.getHistorial();
        }
    </script>
@endpush
