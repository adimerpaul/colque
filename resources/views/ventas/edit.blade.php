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
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1-1"
                       data-toggle="tab">
                        <i class="fa fa-file"></i>
                        Formulario {!! \App\Patrones\Fachada::estado($venta->estado)  !!}</a>
                </li>
                <li><a href="#tab_2-2" data-toggle="tab"><i class="fa fa-truck"></i> Pesaje</a></li>
                <li><a href="#tab_3-3" data-toggle="tab"><i class="fa fa-usd"></i> (-) Anticipos</a></li>

                <li><a href="#tab_4-4" data-toggle="tab"><i class="fa fa-upload"></i> Documentos</a></li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    @include('ventas.resumen')
                    @if(\App\Patrones\Permiso::esComercial())
                        <div class="row">
                            @include('ventas.composito.index')
                            @if(!is_null($venta->monto) and $venta->estado =='Liquidado')
                                <div class="col-sm-12 text-right">
                                    <h3><strong>MONTO FINAL (BOB): {{   $venta->monto }}</strong></h3>
                                    <hr>
                                </div>
                            @endif
                            <div class="form-group col-sm-12 text-right">

                                {!! Form::model($venta, ['route' => ['ventas.cambiar-estado', $venta->id], 'method' => 'put', 'id' => 'frmEdicion']) !!}

                                @if($venta->esEscritura)
                                    {{--                                @if(\App\Patrones\Permiso::esAdmin())--}}
                                    <button type="submit" class="btn btn-danger" name="btnAnular"
                                            onclick="return cambiarEstado('Anular')">Anular venta
                                    </button>
                                    {!! Form::hidden('totalSuma', null, ['class' => 'form-control', 'required', 'v-model'=>'totalSuma', 'id' => 'totalSuma']) !!}
                                    {!! Form::hidden('porcentajeSuma', null, ['class' => 'form-control', 'required', 'v-model'=>'porcentajeSuma', 'id' => 'porcentajeSuma']) !!}
                                    {!! Form::hidden('montoCobrar', null, ['class' => 'form-control', 'required', 'v-model'=>'montoCobrar', 'id' => 'montoCobrar']) !!}

                                    <button type="button" class="btn btn-success"
                                            name="btnFinalizar"
                                            id="btnFinalizar"
                                            onclick="finalizar()">Finalizar y liquidar
                                    </button>

                                @elseif(!$venta->esEscritura AND !$venta->es_cancelado)
                                    <button type="submit" class="btn btn-info" name="btnRestablecer" id="btnRestablecer"
                                            onclick="return cambiarEstado('Restablecer')">Restablecer venta
                                    </button>
                                @endif
                                {!! Form::close() !!}
                                @if(!$venta->esEscritura AND !$venta->es_cancelado AND !$venta->es_aprobado and \App\Patrones\Permiso::esAdmin())
                                    {!! Form::open(['route' => 'aprobar-venta']) !!}
                                    {!! Form::hidden('id', $venta->id, ['class' => 'form-control', 'required']) !!}

                                    <button type="submit" class="btn btn-success"
                                            >Aprobar
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2" style="{{ $venta->only_read }}">
                    @include('ventas.pesaje')
                    @include('ventas.pesaje_table')
                    <hr>

                </div>

                <div class="tab-pane" id="tab_3-3" style="{{ $venta->only_read }}">
                    @if(\App\Patrones\Permiso::esComercial() and $venta->tipo_lote==\App\Patrones\TipoLoteVenta::Venta)
                        @include('anticipos_ventas.form')
                        @include('anticipos_ventas.index')
                    @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4-4">
                    <div class="row">
                        @include('ventas.documentos.edit-doc')
                    </div>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        @include("ventas.historial")

    </div>
    @include("compradores.modal")
    @include("vehiculos.modal")
    @include("choferes.modal")
    {!! Form::model($venta, ['route' => ['ventas.finalizar', $venta->id], 'method' => 'put', 'id' => 'frmFinalizar']) !!}
    @include("ventas.modal_finalizacion")

    {!! Form::close() !!}


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
                tipo_lote: 'Venta'
            },
            mounted() {
                this.getVenta();
                this.saveDocumento();
                this.showDocumento();
                this.getHistorial();
                this.getOtrosCostos();
                this.getPesajes();
                this.getAnticipos();
                this.getConcentrados();

            },
            methods: {
                eliminarPesaje(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/pesajes-ventas/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getPesajes();
                                this.getHistorial();
                                this.getVenta();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },

                savePesaje() {
                    let url = "{{ url('pesajes-ventas') }}";
                    axios.post(url, {
                        numero_pesaje: this.pesaje.numero_pesaje,
                        chofer_id: this.pesaje.chofer_id,
                        vehiculo_id: this.pesaje.vehiculo_id,
                        peso_bruto_humedo: this.pesaje.peso_bruto_humedo,
                        tara: this.pesaje.tara,
                        venta_id: "{{ $venta->id }}"
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.pesaje.numero_pesaje = '';
                            this.pesaje.chofer_id = '';
                            this.pesaje.vehiculo_id = '';
                            this.pesaje.peso_bruto_humedo = '';
                            this.pesaje.tara = '';
                            this.getPesajes();
                            this.getHistorial();
                            this.getVenta();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                getPesajes() {
                    let url = "{{ url('pesajes-ventas') }}";
                    let venta_id = "{{ $venta->id }}";
                    axios.get(url, {
                        params: {venta_id: venta_id}
                    }).then(response => {
                        this.pesajesLista = response.data.pesajes;
                        this.sumaPesajeBruto = response.data.sumaBruto;
                        this.sumaPesajeNeto = response.data.sumaNeto;
                    });
                },
                getVenta() {
                    const venta_id = "{{ $venta->id }}";

                    axios.get("/ventas/" + venta_id).then(response => {
                        this.venta = response.data;
                        this.comprador = response.data.comprador_id;
                    })
                },
                actualizarCosto(e) {
                    var texto = e.target.textContent;
                    var campo = e.target.getAttribute('name');

                    texto = texto.toString().replace(",", ".");
                    if (e.target.getAttribute('value') !== texto) {
                        const url = "{{ url('ventas/pid') }}".replace("pid", this.venta.id);
                        axios.put(url, {
                            valor: texto,
                            nombre: campo
                        }).then(response => {
                            if (response.data.res) {
                                this.getConcentrados();
                                toastr.success(response.data.message);
                            } else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error('Ocurrió un error, revise que el formato del registro sea el correcto');
                        });
                    }
                },
                actualizar() {
                    const url = "{{ url('ventas/update-fields/pid') }}".replace("pid", this.venta.id);
                    axios.put(url, {
                        comprador_id: this.venta.comprador_id,
                        lote_comprador: this.venta.lote_comprador,
                        tipo_transporte: this.venta.tipo_transporte,
                        trayecto: this.venta.trayecto,
                        tranca: this.venta.tranca,
                        municipio: this.venta.municipio,
                        empaque: this.venta.empaque,
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.getHistorial();
                            this.getVenta()
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error('Ocurrió un error, revise e intente nuevamente');
                    });
                },
                eliminarAnticipo(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/anticipos-ventas/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getAnticipos();
                                this.getHistorial();
                                this.getVenta();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },
                imprimirAnticipo(id) {
                    url = "{{ url('anticipos-ventas') }}" + "/" + id + "/imprimir";
// url = '/anticipos/' + id + "/imprimir";
                    var win = window.open(url, '_blank');
                },
                saveAnticipo() {
                    let url = "{{ url('anticipos-ventas') }}";
                    axios.post(url, {
                        motivo: this.motivo,
                        monto: this.monto,
                        venta_id: "{{ $venta->id }}"
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.motivo = '';
                            this.monto = '';
                            this.getAnticipos();
                            this.getHistorial();
                            this.getVenta();

                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                getAnticipos() {
                    let url = "{{ url('anticipos-ventas') }}";
                    let venta_id = "{{ $venta->id }}";
                    axios.get(url, {
                        params: {venta_id: venta_id}
                    }).then(response => {
                        this.anticiposLista = response.data;
                    });
                },

                saveOtroCosto() {
                    let url = "{{ url('costos-ventas') }}";
                    axios.post(url, {
                        descripcion: this.descripcion_otro,
                        monto: this.monto_otro,
                        otros: this.otros,
                        venta_id: "{{ $venta->id }}"
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.descripcion_otro = '';
                            this.monto_otro = '';
                            this.otros = '';
                            this.getHistorial();
                            this.getVenta();
                            this.getOtrosCostos();
                            this.getConcentrados()
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
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

                eliminarOtroCosto(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/costos-ventas/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getHistorial();
                                this.getVenta();
                                this.getOtrosCostos();
                                this.getConcentrados()
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },
                getConcentrados() {

                    const venta_id = "{{ $venta->id }}";
                    axios.get("/concentrados?venta_id=" + venta_id+'&tipo=Venta').then(response => {
                        this.concentrados = response.data.concentrados;
                        this.getSumatorias(response.data);
                        this.montoCobrar= response.data.concentrados[0].total_venta;
                        document.getElementById("montos").value=response.data.concentrados[0].total_venta;
                    })

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
                    var otrosCostos =await  (document.getElementById("otrosCostos").textContent.toString().replace(",", "."));


                    this.mermaSuma = this.agregarComa((res.pesoNetoSeco - pnsFinal) / pnsFinal);
                    this.pnsSuma = this.agregarComa(res.pesoNetoSeco - pnsFinal);
                    this.totalSuma = await( this.agregarComa(res.total - mercaderiaFinal - otrosCostos));

                    this.costoComercializacion= await (this.agregarComa(parseFloat(mercaderiaFinal) + parseFloat(otrosCostos)));
                    this.porcentajeSuma = await (this.agregarComa((res.total - mercaderiaFinal) / mercaderiaFinal));
                },
                actualizarConcentrado(e) {
                    var texto = e.target.textContent;
                    var campo = e.target.getAttribute('name');
                    if (campo !== 'nombre' || campo !== 'fecha')
                        texto = texto.toString().replace(",", ".");
                    if (e.target.getAttribute('value') !== texto) {
                        const url = "{{ url('concentrados/pid') }}".replace("pid", e.target.id);
                        axios.put(url, {
                            valor: texto,
                            nombre: campo
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getConcentrados();
                            } else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error('Ocurrió un error, revise que el formato del registro sea el correcto');
                        });
                    }
                },

                eliminarConcentrado(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/concentrados/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                if(response.data.esIngenio)
                                    location.reload();

                                this.getConcentrados();
                                this.getHistorial();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            toastr.error(e.error);
                        })
                    }
                },

                editarMerma(id, merma) {
                    this.concentrado_id = id;
                    this.merma_porcentaje_editar = merma;
                    $('#modalMermaEditar').modal('show');
                },

                dialogoEnviarLote(id) {
                    this.concentrado_id = id;
                    $('#modalEnviar').modal('show');
                },

                updateMerma() {
                    let url = "{{ url('concentrados-actualizar-merma') }}";
                    axios.post(url, {
                        id: parseInt(this.concentrado_id),
                        merma_porcentaje: this.merma_porcentaje_editar,
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.getConcentrados();
                            this.merma_porcentaje_editar = 0;
                            this.concentrado_id = 0;
                            $('#modalMermaEditar').modal('hide');
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },

                enviarLote() {
                    let url = "{{ url('enviar-lote-ingenio') }}";
                    axios.post(url, {
                        id: parseInt(this.concentrado_id),
                        lote_destino: this.lote_destino,
                        con_plomo: this.con_plomo,
                        ley_pb: this.con_plomo? this.plomo.ley : '',
                        cotizacion_pb: this.con_plomo? this.plomo.cotizacion: '',
                        con_zinc: this.con_zinc,
                        ley_zn: this.con_zinc? this.zinc.ley : '',
                        cotizacion_zn: this.con_zinc? this.zinc.cotizacion: ''
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.lote_destino = '';
                            this.concentrado_id = 0;
                            $('#modalEnviar').modal('hide');
                            this.getConcentrados();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },

                saveConcentrado() {
                    const venta_id = "{{ $venta->id }}";

                    let url = "{{ url('concentrados') }}";
                    axios.post(url, {
                        venta_id: parseInt(venta_id),
                        merma_porcentaje: this.merma_porcentaje,
                        tipo_lote: this.tipo_lote,
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.getConcentrados();
                            this.getHistorial();
                            this.merma_porcentaje = 0;
                            $('#modalMerma').modal('hide');
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                enviarOperaciones() {
                    if (confirm("¿Seguro que quiere enviar a operaciones?")) {

                        const venta_id = "{{ $venta->id }}";

                        let url = "{{ url('enviar-operaciones') }}";
                        axios.post(url, {
                            venta_id: parseInt(venta_id)
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getVenta();
                                this.getHistorial();
                            } else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                        });
                    }
                },



                getHistorial() {
                    const venta_id = "{{ $venta->id }}";
                    axios.get("/historial_ventas?venta_id=" + venta_id).then(response => {
                        this.historiales = response.data;
                    })
                },
                saveDocumento() {
                    $('#formDocumento').ajaxForm({
                        uploadProgress: function (event, position, total, percentComplete) {
                            $("#documento_escaneado").html('Cargando: ' + percentComplete + "% ...");
                        },
                        success: function () {
                            showDocumento();
                        },
                        complete: function (xhr) {
                            var res = xhr.responseJSON;
                            if (res.res) {
                                toastr.success("Registro modificado correctamente!");
                                appFormularioVenta.getHistorial();
                                appFormularioVenta.getVenta();
                            } else {
                                alert(formarListaDeErrores(xhr.responseJSON));
                                alert('Por favor verifique que los archivos no esten corruptos');
                            }
                        },
                        error: function () {
                            toastr.error("Ha ocurrido un error, por favor verifique que los archivos no esten corruptos");
                        },
                        resetForm: true,
                    });
                },

                showDocumento() {
                    showDocumento();
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

        function showDocumento() {
            let id = "{{ $venta->id }}";
            axios.get("/documentos-ventas/" + id).then(response => {
                $("#documento_escaneado").html(response.data);
            });
        }

        async function finalizar() {
            await appFormularioVenta.getConcentrados();

            if (appFormularioVenta.venta.falta_documento) {
                let msg = `No puede finalizar porque faltan los documentos: ` + appFormularioVenta.venta.documento_que_falta;
                return alert(msg);
            // } else if (appFormularioVenta.venta.faltan_pesajes) {
            //     let msg = `No puede finalizar porque falta introducir el pesaje`;
            //     return alert(msg);
            } else {

                let msg = `¿Estás seguro?\n\nVas a finalizar la venta`;
                if (confirm(msg)){
                    $('#modalVenta').modal('show');
                    document.getElementById("utilidad").value=document.getElementById("totalSuma").value;
                    document.getElementById("margen").value=document.getElementById("porcentajeSuma").value;
                    document.getElementById("montos").value=document.getElementById("montoCobrar").value;
                }

            }
        }

        function cambiarEstado(accion) {

            let msg = `¿Estás seguro?\n\nVas a ${accion} la venta`;
            return (confirm(msg));
        }

        $('#modalVenta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var cambiar = button.data('txtcambiar')


            var modal = $(this)
            modal.find('.modal-body #cambiar').val(cambiar);


        })

        $("#frmFinalizar").on("submit", function() {
            $("#botonFinalizarModal").prop("disabled", true);
            $("#btnFinalizar").prop("disabled", true);
        });


    </script>
@endpush
