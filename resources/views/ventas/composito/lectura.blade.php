@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            Ventas
        </h1>
        <h1 class="pull-right">Nro. de Lote: {{$venta->lote}}</h1>
        <hr>
    </section>
    <div class="content" id="appFormularioVenta">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">


                <div class="row">
                    <button type="button" class="btn btn-success pull-right"
                            style="margin-right: 10px"
                            onclick="exportarAExcel()"><i
                            class="fa fa-file-excel-o"></i>
                        Exportar Composito
                    </button>
                    <br>
                    @include('ventas.composito.index')
                    @if(\App\Patrones\Permiso::esRrhh() )
                        <div style="margin-right: 10px; margin-left: 10px">
                        <div class="col-sm-12">
                            <button v-if="venta.es_despachado==true && !venta.verificado_despacho"  class="btn btn-success"
                                    v-on:click="verificarDespacho">
                                <i class="fa fa-check" style="font-weight: bold"></i>
                                Verificar despacho
                            </button>
                        </div>
                        <div v-if="venta.verificado_despacho==true"  class="form-group col-sm-12 alert alert-success" role="alert">
                            <span>DESPACHO VERIFICADO POR CONTABILIDAD</span>
                        </div>
                        </div>
                    @endif

                </div>
                <!-- /.tab-pane -->

            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script type="text/javascript">
        appFormularioVenta = new Vue({
            el: "#appFormularioVenta",
            data: {
                historiales: [],
                concentrados: [],
                anticiposLista: [],
                mostrarHistorial: false,
                otros_costos: '',
                transporte: '',
                mermaSuma: 0,
                pnsSuma: 0,
                leyZnSuma: 0,
                pesoFinoZnSuma: 0,
                cotizacionZnSuma: 0,
                leyAgSuma: 0,
                pesoFinoAgSuma: 0,
                cotizacionAgSuma: 0,
                leySnSuma: 0,
                pesoFinoSnSuma: 0,
                cotizacionSnSuma: 0,
                leyPbSuma: 0,
                pesoFinoPbSuma: 0,
                cotizacionPbSuma: 0,
                leyAuSuma: 0,
                pesoFinoAuSuma: 0,
                cotizacionAuSuma: 0,
                leySbSuma: 0,
                pesoFinoSbSuma: 0,
                cotizacionSbSuma: 0,
                leyCuSuma: 0,
                pesoFinoCuSuma: 0,
                cotizacionCuSuma: 0,
                totalSuma: 0,
                porcentajeSuma: 0,
                costoComercializacion: 0,
                merma_porcentaje: 0,
                merma_porcentaje_editar: 0,
                lote_destino: '',
                concentrado_id: 0,
                motivo: '',
                monto: '',
                costo: {
                    transporte: '',
                    otros_costos: ''
                },
                descripcion_otro: '',
                monto_otro: '',
                otros: '',
                venta: [],
                comprador: '',
                pesajesLista: [],
                costosLista: [],

                pesaje: {
                    numero_pesaje: '',
                    vehiculo_id: '',
                    chofer_id: '',
                    peso_bruto_humedo: '',
                    tara: '',
                },
                sumaPesajeBruto: 0,
                sumaPesajeNeto: 0,
                montoCobrar: 0,
                con_plomo: false,
                con_zinc: false,
                plomo: {
                    ley: '',
                    cotizacion: '',
                },
                zinc: {
                    ley: '',
                    cotizacion: '',
                },
            },
            mounted() {
                this.getVenta();
                this.getOtrosCostos();
                this.getConcentrados();

            },
            methods: {

                getVenta() {
                    const venta_id = "{{ $venta->id }}";
                    axios.get("/ventas/" + venta_id).then(response => {
                        this.venta = response.data;
                        this.comprador = response.data.comprador_id;
                    })
                },

                getOtrosCostos() {
                    let url = "{{ url('costos-ventas') }}";
                    let venta_id = "{{ $venta->id }}";
                    axios.get(url, {
                        params: {venta_id: venta_id}
                    }).then(response => {
                        this.costosLista = response.data;
                    });
                },

                getConcentrados() {
                    const venta_id = "{{ $venta->id }}";
                    axios.get("/concentrados?venta_id=" + venta_id+'&tipo=Venta').then(response => {
                        this.concentrados = response.data.concentrados;
                        this.getSumatorias(response.data);
                        this.montoCobrar = response.data.concentrados[0].total_venta;
                    })
                },
                verificarDespacho() {
                    if (confirm("¿Seguro que quiere verificar el despacho?")) {
                        const venta_id = "{{ $venta->id }}";
                        let url = "{{ url('verificar-despacho') }}";
                        axios.post(url, {
                            venta_id: parseInt(venta_id)
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                setTimeout(function(){
                                    document.location.href = "/ventas";
                                }, 3000);

                            } else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                        });
                    }
                },
                async getSumatorias(res) {
                    if ("{{$venta->letra=='D'}}") {
                        var leySnFinal = (document.getElementById("leySnTotal").textContent.toString().replace(",", "."));
                        var pesoFinoSnFinal = (document.getElementById("pesoFinoSnTotal").textContent.toString().replace(",", "."));
                        var cotizacionSnFinal = (document.getElementById("cotizacionSnTotal").textContent.toString().replace(",", "."));

                        this.leySnSuma = this.agregarComa((res.leySn - leySnFinal) / leySnFinal);
                        this.pesoFinoSnSuma = this.agregarComa((res.pesoFinoSn - pesoFinoSnFinal) / pesoFinoSnFinal);
                        this.cotizacionSnSuma = this.agregarComa((res.cotizacionSn - cotizacionSnFinal) / cotizacionSnFinal);
                    }

                    if ("{{$venta->letra=='A'}}" || "{{$venta->letra=='C'}}") {
                        var leyZnFinal = (document.getElementById("leyZnTotal").textContent.toString().replace(",", "."));
                        var pesoFinoZnFinal = (document.getElementById("pesoFinoZnTotal").textContent.toString().replace(",", "."));
                        var cotizacionZnFinal = (document.getElementById("cotizacionZnTotal").textContent.toString().replace(",", "."));

                        this.leyZnSuma = this.agregarComa((res.leyZn - leyZnFinal) / leyZnFinal);
                        this.pesoFinoZnSuma = this.agregarComa((res.pesoFinoZn - pesoFinoZnFinal) / pesoFinoZnFinal);
                        this.cotizacionZnSuma = this.agregarComa((res.cotizacionZn - cotizacionZnFinal) / cotizacionZnFinal);
                    }
                    if ("{{$venta->letra=='A'}}" || "{{$venta->letra=='B'}}" || "{{$venta->letra=='C'}}" || "{{$venta->letra=='E'}}") {
                        var leyAgFinal = (document.getElementById("leyAgTotal").textContent.toString().replace(",", "."));
                        var pesoFinoAgFinal = (document.getElementById("pesoFinoAgTotal").textContent.toString().replace(",", "."));
                        var cotizacionAgFinal = (document.getElementById("cotizacionAgTotal").textContent.toString().replace(",", "."));
                        this.leyAgSuma = this.agregarComa((res.leyAg - leyAgFinal) / leyAgFinal);
                        this.pesoFinoAgSuma = this.agregarComa((res.pesoFinoAg - pesoFinoAgFinal) / pesoFinoAgFinal);
                        this.cotizacionAgSuma = this.agregarComa((res.cotizacionAg - cotizacionAgFinal) / cotizacionAgFinal);
                    }

                    if ("{{$venta->letra=='B'}}" || "{{$venta->letra=='C'}}") {
                        var leyPbFinal = (document.getElementById("leyPbTotal").textContent.toString().replace(",", "."));
                        var pesoFinoPbFinal = (document.getElementById("pesoFinoPbTotal").textContent.toString().replace(",", "."));
                        var cotizacionPbFinal = (document.getElementById("cotizacionPbTotal").textContent.toString().replace(",", "."));
                        this.leyPbSuma = this.agregarComa((res.leyPb - leyPbFinal) / leyPbFinal);
                        this.pesoFinoPbSuma = this.agregarComa((res.pesoFinoPb - pesoFinoPbFinal) / pesoFinoPbFinal);
                        this.cotizacionPbSuma = this.agregarComa((res.cotizacionPb - cotizacionPbFinal) / cotizacionPbFinal);
                    }
                    if ("{{$venta->letra=='F'}}") {
                        var leySbFinal = (document.getElementById("leySbTotal").textContent.toString().replace(",", "."));
                        var pesoFinoSbFinal = (document.getElementById("pesoFinoSbTotal").textContent.toString().replace(",", "."));
                        var cotizacionSbFinal = (document.getElementById("cotizacionSbTotal").textContent.toString().replace(",", "."));
                        this.leySbSuma = this.agregarComa((res.leySb - leySbFinal) / leySbFinal);
                        this.pesoFinoSbSuma = this.agregarComa((res.pesoFinoSb - pesoFinoSbFinal) / pesoFinoSbFinal);
                        this.cotizacionSbSuma = this.agregarComa((res.cotizacionSb - cotizacionSbFinal) / cotizacionSbFinal);

                        var leyAuFinal = (document.getElementById("leyAuTotal").textContent.toString().replace(",", "."));
                        var pesoFinoAuFinal = (document.getElementById("pesoFinoAuTotal").textContent.toString().replace(",", "."));
                        var cotizacionAuFinal = (document.getElementById("cotizacionAuTotal").textContent.toString().replace(",", "."));
                        this.leyAuSuma = this.agregarComa((res.leyAu - leyAuFinal) / leyAuFinal);
                        this.pesoFinoAuSuma = this.agregarComa((res.pesoFinoAu - pesoFinoAuFinal) / pesoFinoAuFinal);
                        this.cotizacionAuSuma = this.agregarComa((res.cotizacionAu - cotizacionAuFinal) / cotizacionAuFinal);
                    }

                    if ("{{$venta->letra=='G'}}") {
                        var leyCuFinal = (document.getElementById("leyCuTotal").textContent.toString().replace(",", "."));
                        var pesoFinoCuFinal = (document.getElementById("pesoFinoCuTotal").textContent.toString().replace(",", "."));
                        var cotizacionCuFinal = (document.getElementById("cotizacionCuTotal").textContent.toString().replace(",", "."));

                        this.leyCuSuma = this.agregarComa((res.leyCu - leyCuFinal) / leyCuFinal);
                        this.pesoFinoCuSuma = this.agregarComa((res.pesoFinoCu - pesoFinoCuFinal) / pesoFinoCuFinal);
                        this.cotizacionCuSuma = this.agregarComa((res.cotizacionCu - cotizacionCuFinal) / cotizacionCuFinal);
                    }

                    var pnsFinal = await (document.getElementById("pesoNetoSecoTotal").textContent.toString().replace(",", "."));
                    var mercaderiaFinal = await (document.getElementById("costoMercaderia").textContent.toString().replace(",", "."));
                    var otrosCostos = await (document.getElementById("otrosCostos").textContent.toString().replace(",", "."));


                    this.mermaSuma = this.agregarComa((res.pesoNetoSeco - pnsFinal) / pnsFinal);
                    this.pnsSuma = this.agregarComa(res.pesoNetoSeco - pnsFinal);
                    this.totalSuma = await (this.agregarComa(res.total - mercaderiaFinal - otrosCostos));

                    this.costoComercializacion = await (this.agregarComa(parseFloat(mercaderiaFinal) + parseFloat(otrosCostos)));
                    this.porcentajeSuma = await (this.agregarComa((res.total - mercaderiaFinal) / mercaderiaFinal));
                },

                getDateYear(date) {
                    return moment(date).format('DD/MM/YY HH:mm');
                },
                getDateOnly(date) {
                    return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
                },
                agregarComa(numero) {
                    numero = parseFloat(numero).toFixed(2);
                    return numero.toString().replace(".", ",");
                },

                agregarComaCuatro(numero) {
                    numero = parseFloat(numero).toFixed(4);
                    return numero.toString().replace(".", ",");
                },
                dosDecimales(numero) {
                    return parseFloat(numero).toFixed(2);
                },
            }
        });


    </script>
@endpush
