<div class="content">
    <div>
        <div class="box-body">
            <div class="row">
                @if($venta->estado!=\App\Patrones\EstadoVenta::Anulado)

                    @if(\App\Patrones\Permiso::esComercial() )
                        @include('ventas.fields')

                        <div class="col-sm-12">
                            <div class="form-group col-sm-4" style="{{ $venta->only_read }}">


                                <button class="btn btn-info" data-target="#modalMerma" data-toggle="modal">
                                    <i class="fa fa-plus" style="font-weight: bold"></i>
                                    Agregar producto
                                </button>
                                <button v-if="!venta.a_operaciones" class="btn btn-default"
                                        style="background-color: #455A64; color: white" v-on:click="enviarOperaciones">
                                    <i class="fa fa-send" style="font-weight: bold"></i>
                                    Enviar a operaciones
                                </button>



                            </div>

                            <div class="btn-group col-sm-8" role="group">
                                <a class="btn btn-warning pull-right"
                                   href="{{ route('ventas.ordenVenta', ['id' => $venta->id]) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                    Orden de Venta
                                </a>
                                <a class="btn btn-primary pull-right"
                                   href="{{ route('ventas.ordenDespacho', ['id' => $venta->id]) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                    Orden de Despacho
                                </a>

                                <button type="button" class="btn btn-success pull-right"
                                        onclick="exportarAExcel()"><i
                                        class="fa fa-file-excel-o"></i>
                                    Exportar Composito
                                </button>

                            </div>
                        </div>
                    @endif
                @endif
            </div>
            @if($venta->estado!=\App\Patrones\EstadoVenta::Anulado)
                <div>
                    @if($venta->letra==='A')
                        @include('ventas.composito.zinc_plata')
                    @elseif($venta->letra==='B')
                        @include('ventas.composito.plomo_plata')
                    @elseif($venta->letra==='C')
                        @include('ventas.composito.complejo')
                    @elseif($venta->letra=='D')
                        @include('ventas.composito.estanio')
                    @elseif($venta->letra=='E')
                        @include('ventas.composito.plata')
                    @elseif($venta->letra=='F')
                        @include('ventas.composito.antimonio_oro')
                    @elseif($venta->letra=='G')
                        @include('ventas.composito.cobre')
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="text-center">
        {{--            {{ $formularios->appends($_GET)->links()  }}--}}
    </div>


    {!! Form::open(['method' => 'POST', 'id'=>'frmVentaConcentrado', 'v-on:submit.prevent' => 'saveConcentrado']) !!}
    @include("ventas.modal_merma")
    {!! Form::close() !!}

    {!! Form::open(['method' => 'POST', 'id'=>'frmEditarMerma', 'v-on:submit.prevent' => 'updateMerma']) !!}
    @include("ventas.modal_merma_editar")
    {!! Form::close() !!}

    {!! Form::open(['method' => 'POST', 'id'=>'frmEnviarLote', 'v-on:submit.prevent' => 'enviarLote']) !!}
    @include("ventas.modal_enviar_lote")
    {!! Form::close() !!}

    @include("costos_ventas.modal")
</div>
<script type="application/javascript">
    $(document).ready(function () {
        $("#otrosDiv").hide();
        document.getElementById('otros_otros').removeAttribute('required', '');

        $("#divPb").hide();
        document.getElementById('enviar_ley_pb').removeAttribute('required', '');
        document.getElementById('enviar_cotizacion_pb').removeAttribute('required', '');

        $("#divZn").hide();
        document.getElementById('enviar_ley_zn').removeAttribute('required', '');
        document.getElementById('enviar_cotizacion_zn').removeAttribute('required', '');
    });

    $("#frmEnviarLote").on("submit", function () {
        $("#botonEnviar").prop("disabled", true);
    });


    function cambiarNombre() {
        const input = document.getElementById('otros_otros');
        if (document.getElementById("descripcion_otro").value == 'Otros') {
            $("#otrosDiv").show();
            input.setAttribute('required', '');
        } else {
            $("#otrosDiv").hide();
            input.removeAttribute('required', '');
        }
    }

    function getCompradores() {
        let url = "{{ url('get_compradores') }}";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $('select[name="comprador_id"]').empty();
                $('select[name="comprador_id"]').append('<option selected value="">Seleccione..</option>');
                $.each(data, function (key, value) {
                    $('select[name="comprador_id"]').append('<option value="' + key + '">' + value + '</option>');
                });
            },
        });
    }

    function exportarAExcel() {
        var htmlExport = jQuery('#kardex-tabla').prop('outerHTML')
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

//other browser not tested on IE 11
// If Internet Explorer
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            jQuery('body').append(" <iframe id=\"iframeExport\" style=\"display:none\"></iframe>");
            iframeExport.document.open("txt/html", "replace");
            iframeExport.document.write(htmlExport);
            iframeExport.document.close();
            iframeExport.focus();
            sa = iframeExport.document.execCommand("SaveAs", true, "composito_" + "{{$venta->lote}}" + ".xls");
        } else {
            var link = document.createElement('a');

            document.body.appendChild(link); // Firefox requires the link to be in the body
            link.download = "composito_" + "{{$venta->lote}}" + ".xls";
            link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
            link.click();
            document.body.removeChild(link);
        }
    }


    function cambiarPb() {
        const ley = document.getElementById('enviar_ley_pb');
        const coti = document.getElementById('enviar_cotizacion_pb');
        if (document.getElementById("con_pb").value == true) {
            $("#divPb").show();
            ley.setAttribute('required', '');
            coti.setAttribute('required', '');
        } else {
            $("#divPb").hide();
            ley.removeAttribute('required', '');
            coti.removeAttribute('required', '');
        }
    }

    function cambiarZn() {
        const ley = document.getElementById('enviar_ley_zn');
        const coti = document.getElementById('enviar_cotizacion_zn');
        if (document.getElementById("con_zn").value == true) {
            $("#divZn").show();
            ley.setAttribute('required', '');
            coti.setAttribute('required', '');
        } else {
            $("#divZn").hide();
            ley.removeAttribute('required', '');
            coti.removeAttribute('required', '');
        }
    }
</script>

