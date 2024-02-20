@extends('layouts.app')

@section('content')
    <div id="appVenderLotes">
        <section class="content-header">
            <h1 class="pull-left">Lotes a vender</h1>
            <h1 class="pull-right col-sm-4" v-if="arraySeleccionados.length>0">
                <div class="col-sm-12">
                {!! Form::open(['route' => 'ventas.store', 'onSubmit' =>'enviar()']) !!}
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        {!! Form::select('tipo_lote', \App\Patrones\Fachada::getTiposLoteVenta(), null, ['class' => 'form-control', 'style' => 'margin-top: -10px;margin-bottom: 5px' ]) !!}
                    </div>
                {!! Form::hidden('seleccionados', null, ['class' => 'form-control', 'required', 'name'=>'seleccionados', 'v-model'=>'arraySeleccionados']) !!}

                {!! Form::submit('Vender', ['class' => 'btn btn-primary pull-right', 'id'=>'botonGuardar', 'style' => 'margin-top: -10px;margin-bottom: 5px']) !!}
                {!! Form::close() !!}
                </div>

            </h1>
        </section>
        <div class="content">
            <div class="clearfix"></div>

            @include('flash::message')

            <div class="clearfix"></div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">

                            {!! Form::open(['route' => 'lotes.index', 'method'=>'get']) !!}

                            <div class="form-group col-sm-2">
                                {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Nro de lote']) !!}
                            </div>
                            <div class="form-group col-sm-2">
                                {!! Form::label('Fecha', 'F. Liq. Inicio:') !!}
                                {!! Form::date('fecha_inicio', isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group col-sm-2">
                                {!! Form::label('Fecha', 'F. Liq. Fin:') !!}
                                {!! Form::date('fecha_fin', isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="form-group col-sm-2">
                                {!! Form::label('producto_id', 'Producto: *') !!}
                                {!! Form::select('producto_id', [null => 'Seleccione...'] +  \App\Models\Producto::orderBy('letra')->get()->pluck('info', 'letra')->toArray(), isset($_GET['producto_id']) ? $_GET['producto_id'] : null, ['class' => 'form-control', 'required' ]) !!}
                            </div>

                            <div class="form-group col-sm-2">
                                {!! Form::label('txtEstado', 'Estado:') !!}
                                {!! Form::select('txtEstado', \App\Patrones\Fachada::getEstadosAVender(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group col-sm-2" style="margin-top: 25px">
                                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                    Buscar
                                </button>

                                <button type="button" class="btn btn-success" title="Exportar"
                                        onclick="exportarAExcel()"><i
                                        class="fa fa-file-excel-o"></i>

                                </button>
                            </div>
                            <br>
                            <div class="form-group col-sm-2 ">
                                <a data-toggle="modal" data-target="#modalVenta" title="Agregar"
                                   class='btn btn-primary pull-left' v-if="arraySeleccionados.length>0"><i
                                        class="glyphicon glyphicon-plus"></i> Agregar a Venta</a>
                            </div>
                            <div class="form-group col-sm-12">
                                <div class="form-group col-sm-3">.</div>
                                <div class="form-group col-sm-6 alert alert-success" role="alert"
                                     style="font-weight: bold">
                                    <h4 class="alert-heading">Cálculos</h4>
                                    <span id="pesoNetoSecoSeleccionados"
                                          class="form-group col-sm-6">- Peso Neto Seco: </span>
                                    <span id="pesoNetoHumedoSeleccionados"
                                          class="form-group col-sm-6">- Peso Neto Húmedo:</span>
                                    <span id="valorNetoVentaSeleccionados"
                                          class="form-group col-sm-6">- Valor Neto Venta: </span>
                                    <span
                                          class="form-group col-sm-6">&nbsp;</span>
                                    @if($producto)
                                        @if($producto->id===1 OR $producto->id===2 OR $producto->id===3 OR $producto->id===6)
                                            <span id="leyAgSeleccionados" class="form-group col-sm-6">- Ag DM: </span>
                                            <span id="cotizacionAgSeleccionados" class="form-group col-sm-6">- Ag Cotización Diaria: </span>
                                        @endif

                                        @if($producto->id===2 OR $producto->id===3)

                                            <span id="leyPbSeleccionados" class="form-group col-sm-6">- Pb %: </span>
                                            <span id="cotizacionPbSeleccionados" class="form-group col-sm-6">- Pb Cotización Diaria: </span>
                                        @endif

                                        @if($producto->id===4)
                                            <span id="leySnSeleccionados" class="form-group col-sm-6">- Sn %: </span>
                                            <span id="cotizacionSnSeleccionados" class="form-group col-sm-6">- Sn Cotización Diaria: </span>
                                        @endif

                                        @if($producto->id===1 OR $producto->id===3)
                                            <span id="leyZnSeleccionados" class="form-group col-sm-6">- Zn %: </span>
                                            <span id="cotizacionZnSeleccionados" class="form-group col-sm-6">- Zn Cotización Diaria: </span>
                                        @endif

                                        @if($producto->id===5)
                                            <span id="leySbSeleccionados" class="form-group col-sm-6">- Sb %: </span>
                                            <span id="cotizacionSbSeleccionados" class="form-group col-sm-6">- Sb Cotización Diaria: </span>

                                            <span id="leyAuSeleccionados" class="form-group col-sm-6">- Au G/T: </span>
                                            <span id="cotizacionAuSeleccionados" class="form-group col-sm-6">- Au Cotización Diaria: </span>
                                        @endif
                                    @endif
                                    <p style="font-size: 1px;">.</p>
                                    <hr style="margin-top: 1px">
                                    <strong><p class="mb-0" id="seleccionados"> 0 Seleccionados</p></strong>
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>

                    @if($producto)
                        @if($producto->id===1)
                            @include('lotes.tables_vender.zinc_plata')
                        @elseif($producto->id===2)
                            @include('lotes.tables_vender.plomo_plata')
                        @elseif($producto->id===3)
                            @include('lotes.tables_vender.complejo')
                        @elseif($producto->id===4)
                            @include('lotes.tables_vender.estanio')
                        @elseif($producto->id===5)
                            @include('lotes.tables_vender.antimonio_oro')
                        @elseif($producto->id===6)
                            @include('lotes.tables_vender.plata')
                        @elseif($producto->id===7)
                            @include('lotes.tables_vender.cobre')
                        @endif
                    @endif
                </div>
            </div>
            <div class="text-center">
                {{ $formularios->appends($_GET)->links()  }}
            </div>
        </div>
        {!! Form::open(['route' => 'ventas.actualizar', 'onsubmit' => 'agregar()']) !!}
        {!! Form::hidden('seleccionados', null, ['class' => 'form-control', 'required', 'name'=>'seleccionados', 'v-model'=>'arraySeleccionados']) !!}
        @include("ventas.modal_create")
        {!! Form::close() !!}
    </div>

    @push('scripts')

        <script type="text/javascript">
            function enviar() {
                $("#botonGuardar").prop("disabled", true);
            }

            function agregar() {
                $("#botonAgregar").prop("disabled", true);
            }

            appVenderLotes = new Vue({
                el: "#appVenderLotes",
                data: {
                    arraySeleccionados: [],
                    sumaPesoNetoSeco: 0,
                    sumaPesoNetoHumedo: 0,
                    sumaValorNetoVenta: 0,
                    sumaPesoFinoSn: 0,
                    sumaLeySn: 0,
                    sumaCotizacionSn: 0,
                    sumaPesoFinoZn: 0,
                    sumaLeyZn: 0,
                    sumaCotizacionZn: 0,
                    sumaPesoFinoAg: 0,
                    sumaLeyAg: 0,
                    sumaCotizacionAg: 0,
                    sumaPesoFinoPb: 0,
                    sumaLeyPb: 0,
                    sumaCotizacionPb: 0,
                    sumaPesoFinoSb: 0,
                    sumaLeySb: 0,
                    sumaCotizacionSb: 0,
                    sumaPesoFinoAu: 0,
                    sumaLeyAu: 0,
                    sumaCotizacionAu: 0,
                    sumaPesoFinoCu: 0,
                    sumaLeyCu: 0,
                    sumaCotizacionCu: 0,
                },
                methods: {
                    getCalculos(e) {
                        const venta_id = e.target.value;
                        axios.get("/ventas/" + venta_id).then(response => {
                            this.venta = response.data;
                            //PNS
                            this.sumaPesoNetoSeco = parseFloat(response.data.suma_peso_neto_seco) + parseFloat(sumaPesoNetoSeco);
                            document.getElementById("pesoNetoSecoSumados").innerHTML = "- Peso Neto Seco: " + this.sumaPesoNetoSeco.toFixed(2);

                            //PNH
                            this.sumaPesoNetoHumedo = parseFloat(response.data.suma_peso_neto_humedo) + parseFloat(sumaPesoNetoHumedo);
                            document.getElementById("pesoNetoHumedoSumados").innerHTML = "- Peso Neto Húmedo: " + this.sumaPesoNetoHumedo.toFixed(2);

                            this.sumaValorNetoVenta = parseFloat(response.data.suma_valor_neto_venta) + parseFloat(sumaNetoVenta);
                            document.getElementById("valorNetoVentaSumados").innerHTML = "- Valor Neto Venta: " + this.sumaValorNetoVenta.toFixed(2);


                            if (response.data.letra === 'D') {
                                //Peso Fino Sn
                                this.sumaPesoFinoSn = parseFloat(response.data.suma_peso_fino_sn) + parseFloat(sumaPesoFinoSn);
                                //Ley Sn
                                this.sumaLeySn = (parseFloat(this.sumaPesoFinoSn) / parseFloat(this.sumaPesoNetoSeco)) * 100;
                                document.getElementById("leySnSumados").innerHTML = "- Sn %: " + this.sumaLeySn.toFixed(2);
                                //Cotizacion Sn
                                this.sumaCotizacionSn = parseFloat(response.data.suma_cotizacion_sn) + parseFloat(sumaCotizacionSn);
                                this.sumaCotizacionSn = this.sumaCotizacionSn / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionSnSumados").innerHTML = "- Sn Cotización Diaria: " + this.sumaCotizacionSn.toFixed(2);
                            }

                            if (response.data.letra === 'A' || response.data.letra === 'C') {
                                //Peso Fino Zn
                                this.sumaPesoFinoZn = parseFloat(response.data.suma_peso_fino_zn) + parseFloat(sumaPesoFinoZn);
                                //Ley Zn
                                this.sumaLeyZn = (parseFloat(this.sumaPesoFinoZn) / parseFloat(this.sumaPesoNetoSeco)) * 100;
                                document.getElementById("leyZnSumados").innerHTML = "- Zn %: " + this.sumaLeyZn.toFixed(2);
                                //Cotizacion Zn
                                this.sumaCotizacionZn = parseFloat(response.data.suma_cotizacion_zn) + parseFloat(sumaCotizacionZn);
                                this.sumaCotizacionZn = this.sumaCotizacionZn / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionZnSumados").innerHTML = "- Zn Cotización Diaria: " + this.sumaCotizacionZn.toFixed(2);
                            }

                            if (response.data.letra === 'B' || response.data.letra === 'C') {
                                //Peso Fino Pb
                                this.sumaPesoFinoPb = parseFloat(response.data.suma_peso_fino_pb) + parseFloat(sumaPesoFinoPb);
                                //Ley Pb
                                this.sumaLeyPb = (parseFloat(this.sumaPesoFinoPb) / parseFloat(this.sumaPesoNetoSeco)) * 100;
                                document.getElementById("leyPbSumados").innerHTML = "- Pb %: " + this.sumaLeyPb.toFixed(2);
                                //Cotizacion Pb
                                this.sumaCotizacionPb = parseFloat(response.data.suma_cotizacion_pb) + parseFloat(sumaCotizacionPb);
                                this.sumaCotizacionPb = this.sumaCotizacionPb / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionPbSumados").innerHTML = "- Pb Cotización Diaria: " + this.sumaCotizacionPb.toFixed(2);
                            }

                            if (response.data.letra === 'A' || response.data.letra === 'B' || response.data.letra === 'C' || response.data.letra === 'E') {
                                //Peso Fino Ag
                                this.sumaPesoFinoAg = parseFloat(response.data.suma_peso_fino_ag) + parseFloat(sumaPesoFinoAg);
                                //Ley Ag
                                this.sumaLeyAg = (parseFloat(this.sumaPesoFinoAg) / parseFloat(this.sumaPesoNetoSeco)) * 10000;
                                document.getElementById("leyAgSumados").innerHTML = "- Ag %: " + this.sumaLeyAg.toFixed(2);
                                //Cotizacion Ag
                                this.sumaCotizacionAg = parseFloat(response.data.suma_cotizacion_ag) + parseFloat(sumaCotizacionAg);
                                this.sumaCotizacionAg = this.sumaCotizacionAg / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionAgSumados").innerHTML = "- Ag Cotización Diaria: " + this.sumaCotizacionAg.toFixed(2);
                            }

                            if (response.data.letra === 'F') {
                                //Peso Fino Sb
                                this.sumaPesoFinoSb = parseFloat(response.data.suma_peso_fino_sb) + parseFloat(sumaPesoFinoSb);
                                //Ley Sb
                                this.sumaLeySb = (parseFloat(this.sumaPesoFinoSb) / parseFloat(this.sumaPesoNetoSeco)) * 100;
                                document.getElementById("leySbSumados").innerHTML = "- Sb %: " + this.sumaLeySb.toFixed(2);
                                //Cotizacion Sb
                                this.sumaCotizacionSb = parseFloat(response.data.suma_cotizacion_sb) + parseFloat(sumaCotizacionSb);
                                this.sumaCotizacionSb = this.sumaCotizacionSb / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionSbSumados").innerHTML = "- Sb Cotización Diaria: " + this.sumaCotizacionSb.toFixed(2);


                                //Peso Fino Au
                                this.sumaPesoFinoAu = parseFloat(response.data.suma_peso_fino_au) + parseFloat(sumaPesoFinoAu);
                                //Ley Au
                                this.sumaLeyAu = (parseFloat(this.sumaPesoFinoAu) / parseFloat(this.sumaPesoNetoSeco)) * 10000;
                                document.getElementById("leyAuSumados").innerHTML = "- Au %: " + this.sumaLeyAu.toFixed(2);
                                //Cotizacion Au
                                this.sumaCotizacionAu = parseFloat(response.data.suma_cotizacion_au) + parseFloat(sumaCotizacionAu);
                                this.sumaCotizacionAu = this.sumaCotizacionAu / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionAuSumados").innerHTML = "- Au Cotización Diaria: " + this.sumaCotizacionAu.toFixed(2);
                            }

                            if (response.data.letra === 'G') {
                                //Peso Fino Cu
                                this.sumaPesoFinoCu = parseFloat(response.data.suma_peso_fino_cu) + parseFloat(sumaPesoFinoCu);
                                //Ley Cu
                                this.sumaLeyCu = (parseFloat(this.sumaPesoFinoCu) / parseFloat(this.sumaPesoNetoSeco)) * 100;
                                document.getElementById("leyCuSumados").innerHTML = "- Cu %: " + this.sumaLeyCu.toFixed(2);
                                //Cotizacion Cu
                                this.sumaCotizacionCu = parseFloat(response.data.suma_cotizacion_cu) + parseFloat(sumaCotizacionCu);
                                this.sumaCotizacionCu = this.sumaCotizacionCu / this.sumaPesoNetoSeco;
                                document.getElementById("cotizacionCuSumados").innerHTML = "- Cu Cotización Diaria: " + this.sumaCotizacionCu.toFixed(2);
                            }
                        })
                    },
                }
            });

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
                    sa = iframeExport.document.execCommand("SaveAs", true, "kardex.xls");
                } else {
                    var link = document.createElement('a');

                    document.body.appendChild(link); // Firefox requires the link to be in the body
                    link.download = "kardex.xls";
                    link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        </script>
    @endpush


@endsection
