@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            Compra
        </h1>

        <h1 class="pull-right">Nro. de Lote: {{ $formulario->lote }}</h1>




        @if($cambios->count()>0)
            <div class="alert alert-danger text-center">
                @foreach($cambios as $cambio)
                    {{$cambio->descripcion}}<br>
                @endforeach
            </div>
        @endif
    </section>
    <div class="content" id="appFormulario" style="margin-top: 15px">
        @if($formulario->cliente->cooperativa_id==44)
            <span style="background-color: #DD4B39; color: white; padding: 5px" class="pull-left">
            Deuda Cooperativa:
        {{ \Illuminate\Support\Facades\DB::table('cliente')
               ->join('cuenta_cobrar', 'cliente.id', '=', 'cuenta_cobrar.origen_id')
               ->where('es_cancelado',false)
               ->where('cuenta_cobrar.origen_type', \App\Models\Cliente::class)
               ->where('cliente.cooperativa_id','44')
                ->sum('monto')
               }} BOB
            </span>
        @endif
        <div class="pull-right" >
            <input type="checkbox" @click="cambiarTornaguia(formulario.id)" :checked="formulario.con_tornaguia" name="con_tornaguia" id="con_tornaguia">
            &nbsp;

            <span v-if="formulario.con_tornaguia" style="background-color: #4CAF50; color: white; padding: 3px; font-size: 16px">Con Tornaguía</span>
            <span v-else style="background-color: #DD4B39; color: white; padding: 3px; font-size: 16px">Falta Tornaguía</span>
        </div>
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
                        Formulario {!! \App\Patrones\Fachada::estado($formularioLiquidacion->estado)  !!}  </a>
                </li>
                <li><a href="#tab_2-2" data-toggle="tab"><i class="fa fa-truck"></i> Pesaje</a></li>
                <li><a href="#tab_3-3" data-toggle="tab"><i class="fa fa-thermometer-full"></i> Laboratorio</a></li>
                <li><a href="#tab_4-4" data-toggle="tab"><i class="fa fa-usd"></i> (-) Anticipos</a></li>
                <li><a href="#tab_5-5" data-toggle="tab"><i class="fa fa-usd"></i> (+) Devoluciones por retiro</a></li>
                <li><a href="#tab_6-6" data-toggle="tab"><i class="fa fa-money"></i> Cuentas por cobrar</a></li>
                <li><a href="#tab_7-7" data-toggle="tab"><i class="fa fa-file"></i> Documentos</a></li>
                <li><a href="#tab_8-8" data-toggle="tab"><i class="fa fa-print"></i> Impresión</a></li>

            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    @if(\App\Patrones\Permiso::esComercial())
                        <div class="row">
                        {!! Form::model($formularioLiquidacion, ['route' => ['formularioLiquidacions.update', $formularioLiquidacion->id], 'method' => 'patch', 'id' => 'frmEdicion']) !!}
                        @include('formulario_liquidacions.fields')
                        @include('formulario_liquidacions.listaCotizaciones')
                        <!-- Submit Field -->
                            <div class="form-group col-sm-6">
                                <a href="{{ route('formularioLiquidacions.index') }}" class="btn btn-default">Volver</a>
                            </div>

                            <div class="form-group col-sm-6 text-right">
                                <a style="margin-left: 4px" data-toggle="modal" data-target="#modalInfoBotones"
                                   title="Información de función de botones"
                                   class='btn btn-primary btn-lm pull-right'><i
                                        class="glyphicon glyphicon-info-sign"></i></a>

                                @if(!$formularioLiquidacion->es_cancelado)
                                    @if($formularioLiquidacion->esEscritura)
                                        @if(\App\Patrones\Permiso::esAdmin())
                                            <button type="button" class="btn btn-danger" name="btnAnular"
                                                    v-if="formulario.cantidad_devoluciones==0"
                                                    data-toggle="modal" data-target="#modalAnulacion">Anular formulario
                                            </button>
                                        @endif
                                        @if(\App\Patrones\Permiso::esComercial())
                                            <button type="button" style="background-color: #FF5722; color: white"
                                                    class="btn"
                                                    onclick="retirar()">Retirar formulario
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-success" id="btnFinalizar"
                                                name="btnFinalizar"
                                                v-if="formulario.cantidad_devoluciones==0"
                                                @click="finalizarFormulario">Finalizar y liquidar
                                        </button>
                                    @else
                                        @if(\App\Patrones\Permiso::esComercial() AND ($formularioLiquidacion->estado == \App\Patrones\Estado::Anulado OR $formularioLiquidacion->estado == \App\Patrones\Estado::Liquidado))
                                            <button type="submit" class="btn btn-info" name="btnRestablecer"
                                                    onclick="return restablecer('Restablecer')">Restablecer formulario
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    <div class="row" style="{{ $formularioLiquidacion->onlyRead }}">
                        {!! Form::model($formularioLiquidacion, ['route' => ['formularioLiquidacions.update', $formularioLiquidacion->id], 'method' => 'patch', 'id' => 'frmFormularioEdit']) !!}
                        @include('formulario_liquidacions.fields_pesaje')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3-3" style="{{ $formularioLiquidacion->only_read }}">
                    @if(\App\Patrones\Permiso::esComercial())

                        <div class="row">
                            @include('formulario_liquidacions.laboratorio')
                        </div>
                    @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4-4" style="{{ $formularioLiquidacion->only_read }}">
                    @if(\App\Patrones\Permiso::esComercial())

                        @include('anticipos.form')
                        @include('anticipos.index')
                    @endif
                </div>

                <div class="tab-pane" id="tab_5-5" style="{{ $formularioLiquidacion->only_read }}">
                    @if(\App\Patrones\Permiso::esComercial())

                        @include('bonos.form')
                        @include('bonos.index')
                    @endif
                </div>

                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_6-6" style="{{ $formularioLiquidacion->only_read }}">
                    @if(\App\Patrones\Permiso::esComercial())

                        @include('cuentas_cobrar.index')
                    @endif
                </div>
                <div class="tab-pane" id="tab_7-7">
                    @include('documentos.create')

                </div>
                <div class="tab-pane" id="tab_8-8">
                    <div class="row">
                        <div class="form-group col-sm-9">
                            {!! Form::text('nombreImpresion',  null, ['class' => 'form-control', 'maxlength'=>'70', 'placeholder'=>'Ingrese Nombre del productor', 'id' =>'nombreImpresion']) !!}
                        </div>

                        <div class="form-group col-sm-3 text-right">
                            <a target="_blank" class="btn btn-primary" onclick="imprimirFormulario()"><i
                                    class="glyphicon glyphicon-print"></i> Imprimir Formulario
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
        {{--        <div class="tab-pane" id="tab_5-5">--}}
        {{--        </div>--}}
        @include("historials.table")

    </div>
    @include("clientes.modal")
    @include("vehiculos.modal")
    @include("choferes.modal")
    @include("formulario_liquidacions.modalFechaLiquidacion")
    @include("formulario_liquidacions.modalFechaCotizacion")
    @include("formulario_liquidacions.modalInfoBotones")
    @include("formulario_liquidacions.modalAnulacion")
    @include("formulario_liquidacions.modalRetiro")
    @include("formulario_liquidacions.modal_cambio_producto")
    {!! Form::open(['route' => 'editar-valor-descuento', 'id' => 'formularioModalEditarDescuento']) !!}
        @include("formulario_liquidacions.modalEditarDescuento")
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script type="text/javascript">

        appFormulario = new Vue({
            el: "#appFormulario",
            data: {
                retenciones: [],
                descuentos: [],
                bonificaciones: [],
                faltantes: [],
                minerales: [],
                title: '',
                fecha_cotizacion: null,
                esEscritura: "{{$formularioLiquidacion->esEscritura}}",
                mineralesCotizacionOficial: [],
                mineralesCotizacionDiaria: [],
                cotizacionesDolar: [],
                formulario: {
                    laboratorio_promedio: [],
                    tipo_cambio: {}
                },
                oficiales: [],
                diarias: [],
                motivo: '',
                cliente_pago:'',
                monto: '',
                fecha: "{{$fechaActual}}",
                anticiposLista: [],
                bonosLista: [],
                cuentasLista: [],
                historiales: [],
                laboratoriosEmpresas: [],
                laboratoriosClientes: [],
                laboratoriosDirimiciones: [],
                cuentasPendientes: [],
                laboratorios: [],
                peso_bruto: "{{$formularioLiquidacion->peso_bruto}}",
                tara: "{{$formularioLiquidacion->tara}}",
                sacos: "{{$formularioLiquidacion->sacos}}",
                boletas: "{{$formularioLiquidacion->boletas}}",
                tipo_material: "{{$formularioLiquidacion->tipo_material}}",
                letra: "{{$formularioLiquidacion->letra}}",
                presentacion: "{{$formularioLiquidacion->presentacion}}",
                es_peso_valido: true,
                mostrarHistorial: false,
                montoLaboratorio: ''
            },
            created() {
            },
            mounted() {
                this.getResumen();
                this.saveDocument();
                this.showDocument();
                this.cargarDescuentosBonificaciones();
                this.getAnticipos();
                this.getBonos();
                this.getCuentasCobrar();
                this.getHistorial();
                this.getLaboratorios();
                this.getCuentasPendientes();
                this.cambiarCheckPromedio();
                this.cambiarCheckManual();
            },
            computed: {
                totalRetenciones() {
                    return this.retenciones.reduce((sum, item) => sum + item.sub_total, 0);
                },
                totalDescuentos() {
                    return this.descuentos.reduce((sum, item) => sum + item.sub_total, 0);
                },
                totalBonificaciones() {
                    return this.bonificaciones.reduce((sum, item) => sum + item.sub_total, 0);
                },
                totalMinerales() {
                    return this.minerales.reduce((sum, item) => sum + item.sub_total, 0);
                },
                peso_neto() {

                    let peso = 0;
                    if (this.presentacion === 'Ensacado'){

                        if("{{$formularioLiquidacion->letra}}"=='E')
                            peso =  this.peso_bruto - (0.225 * parseFloat(this.sacos));
                        else
                            peso =  this.peso_bruto - (0.250 * parseFloat(this.sacos));
                    }
                    else
                        peso = this.peso_bruto - this.tara

                    if (this.tipo_material === 'Broza' && this.letra==='E')
                        peso = this.peso_bruto;

                    this.es_peso_valido = peso > 0
                    return peso
                }
            },
            methods: {
                reiniciarDescuentos(id){
                    if (confirm("¿Seguro de reiniciar los descuentos/bonificaciones?")) {
                    const url = "{{ url('reiniciar-descuentos/pid') }}".replace('pid', id);
                    axios.put(url).then(response => {
                        if(response.data.res){
                            toastr.success(response.data.message);
                            window.onload = setTimeout("location.reload(true)", 3000)
                        }
                        else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error(e.message);
                    });
                    }
                },
                cambiarTornaguia(id){
                    const url = "{{ url('cambiar-tornaguia/pid') }}".replace('pid', id);
                    axios.put(url).then(response => {
                        if(response.data.res){
                            toastr.success(response.data.message);
                            this.getResumen();
                        }
                        else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error(e.message);
                    });
                },
                redondear(valor) {
                    return parseFloat(valor).toFixed(2);
                },
                redondearTres(valor) {
                    return parseFloat(valor).toFixed(3);
                },
                getDate(date) {
                    return moment(date).format('DD/MM/YY HH:mm');
                },
                getDateOnly(date) {
                    return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
                },
                getDateYear(date) {
                    return moment(date).format('DD/MM/YY HH:mm');
                },
                getHistorial() {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    axios.get("/historials?formulario_id=" + formulario_id).then(response => {
                        this.historiales = response.data;
                    })
                },
                getCuentasPendientes() {
                    const cliente_id = "{{ $formularioLiquidacion->cliente_id }}";
                    axios.get("/cuentas-cobrar-cliente/" + cliente_id).then(response => {
                        this.cuentasPendientes = response.data;
                    })
                },


                agregarCuenta(id) {
                    let url = "{{ url('agregar-cuenta') }}";
                    if (confirm("¿Seguro que quiere agregar esta cuenta por cobrar al formulario?")) {
                        axios.post(url, {
                            cuenta_id: id,
                            formulario_liquidacion_id: "{{ $formularioLiquidacion->id }}"
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getResumen();
                                this.getHistorial();
                                this.getCuentasCobrar();
                                this.getCuentasPendientes();

                                $('#modalCuentasPendientes').modal('hide');
                            } else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                        });

                    }
                },

                eliminarAnticipo(id) {
                    if (confirm("Seguro que quiere eliminar este registro?")) {
                        axios.delete("/anticipos/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getAnticipos();
                                this.getResumen();
                                this.getHistorial();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },

                eliminarDocumentos() {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    if (confirm("¿Seguro que quiere eliminar los documentos?")) {
                        axios.delete("/eliminar-documento-compra/" + formulario_id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.showDocument();
                                this.getHistorial();
                                this.getResumen();
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
                    url = "{{ url('anticipos') }}" + "/" + id + "/imprimir";
                    // url = '/anticipos/' + id + "/imprimir";
                    var win = window.open(url, '_blank');
                },
                saveAnticipo() {
                    let url = "{{ url('anticipos') }}";
                    axios.post(url, {
                        motivo: this.motivo,
                        monto: this.monto,
                        fecha: this.fecha,
                        cliente_pago: document.getElementById('cliente_pago').value,
                        formulario_liquidacion_id: "{{ $formularioLiquidacion->id }}"
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.motivo = '';
                            this.monto = '';
                            this.fecha = "{{$fechaActual}}";
                            this.getAnticipos();
                            this.getResumen();
                            this.getHistorial();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },

                saveCostoLaboratorio(costoId) {
                    const url = "{{ url('costos/pid') }}".replace("pid", costoId);
                    axios.put(url, {
                        monto: this.montoLaboratorio,
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                getAnticipos() {
                    let url = "{{ url('anticipos') }}";
                    let formulario_id = "{{ $formularioLiquidacion->id }}";
                    axios.get(url, {
                        params: {formulario_id: formulario_id}
                    }).then(response => {
                        this.anticiposLista = response.data;
                        if (response.data.length > 9)
                            $("#btnGuardarAnticipo").hide();
                        else
                            $("#btnGuardarAnticipo").show();
                    });
                },
                saveBono() {
                    let form = document.getElementById('frmBono');
                    axios({
                        method: form.getAttribute('method'),
                        url: form.getAttribute('action'),
                        data: new FormData(form),
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            form.reset();
                            this.getBonos();
                            this.getResumen();
                            this.getHistorial();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                eliminarBono(id) {
                    if (confirm("Seguro que quiere eliminar este bono?")) {
                        axios.delete("/bonos/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getBonos();
                                this.getResumen();
                                this.getHistorial();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },
                eliminarCuentaCobrar(id) {
                    if (confirm("Seguro que quiere eliminar esta cuenta por cobrar?")) {
                        axios.delete("/cuentas-cobrar/" + id).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getCuentasCobrar();
                                this.getResumen();
                                this.getHistorial();
                            } else {
                                toastr.error(response.data.message);
                            }
                        }).catch(e => {
                            console.log("catch");
                            toastr.error(e.error);
                        })
                    }
                },
                getBonos() {
                    let url = "{{ url('bonos') }}";
                    let formulario_id = "{{ $formularioLiquidacion->id }}";
                    axios.get(url, {params: {formulario_id: formulario_id}}).then(response => {
                        this.bonosLista = response.data;
                    });
                },
                getCuentasCobrar() {
                    let url = "{{ url('cuentas-cobrar') }}";
                    let formulario_id = "{{ $formularioLiquidacion->id }}";
                    axios.get(url, {params: {formulario_id: formulario_id}}).then(response => {
                        this.cuentasLista = response.data;
                    });
                },
                cargarDescuentosBonificaciones() {
                    this.getDescuentoBonificacion('Retencion');
                    this.getDescuentoBonificacion('Descuento');
                    this.getDescuentoBonificacion('Bonificacion');
                },
                getLaboratorios(tipo) {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('get-laboratorios/pid') }}".replace("pid", formulario_id);
                    return axios.get(url).then(response => {
                        this.laboratoriosEmpresas = response.data.laboratoriosEmpresas;
                        this.laboratoriosClientes = response.data.laboratoriosClientes;
                        this.laboratoriosDirimiciones = response.data.laboratoriosDirimiciones;
                        this.laboratorios = response.data.laboratorios;
                    });
                },
                getResumen() {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('get-resumen/pid') }}".replace("pid", formulario_id);
                    return axios.get(url).then(response => {
                        this.formulario = response.data.formulario;
                        this.oficiales = response.data.oficiales;
                        this.diarias = response.data.diarias;
                        this.minerales = response.data.formulario.minerales_regalia;
                    });
                },

                actualizarValorPorTonelada() {
                    if (this.formulario.valor_por_tonelada !== null) {
                        Swal.fire({
                            title: '¿Estas seguro?',
                            text: "Ya tiene registrado un valor por tonelada, se va a actualizar en base a la Tabla Acopiadora",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.updateValorPorTonelada();

                            }
                        });
                    } else {
                        this.updateValorPorTonelada();
                    }
                },

                updateValorPorTonelada() {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('update_valor_por_tonelada/pid') }}".replace("pid", formulario_id);
                    axios.put(url).then(response => {
                        if (response.data.res) {
                            this.formulario = response.data.formulario;
                            Swal.fire({
                                text: "Valor por tonelada actualizado correctamente",
                                type: 'success',
                                position: 'top-end',
                                toast: true,
                                timer: 1000,
                                showConfirmButton: false,
                                icon: 'success'
                            });
                        } else {
                            Swal.fire({
                                text: response.data.message,
                                type: 'error',
                                position: 'top-end',
                                toast: true,
                                timer: 1000,
                                showConfirmButton: false,
                                icon: 'error'
                            });
                        }
                    });
                },

                restarValorPorTonelada() {
                    $("#btnRestarValor").prop("disabled", true);
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('restar-valor-por-tonelada/pid') }}".replace("pid", formulario_id);
                    axios.put(url).then(response => {
                        if (response.data.res) {
                            this.formulario = response.data.formulario;

                            Swal.fire({
                                text: "Valor por tonelada actualizado correctamente",
                                type: 'success',
                                position: 'top-end',
                                toast: true,
                                timer: 1000,
                                showConfirmButton: false,
                                icon: 'success'
                            });
                            this.cargarDescuentosBonificaciones();
                        } else {
                            Swal.fire({
                                text: response.data.message,
                                type: 'error',
                                position: 'top-end',
                                toast: true,
                                timer: 1000,
                                showConfirmButton: false,
                                icon: 'error'
                            });
                        }
                        $("#btnRestarValor").prop("disabled", false);
                    });
                },
                getDescuentoBonificacion(tipo) {
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('descuentos-by-formulario') }}";
                    return axios.get(url, {
                        params: {
                            formulario_id: formulario_id,
                            tipo: tipo
                        }
                    }).then(response => {
                        if (response.data.res) {
                            if (tipo === "Retencion") this.retenciones = response.data.data;
                            if (tipo === "Descuento") this.descuentos = response.data.data;
                            if (tipo === "Bonificacion") this.bonificaciones = response.data.data;
                        }
                    });
                },
                getDescuentoFaltante(tipo) {
                    this.faltantes = [];
                    const formulario_id = "{{ $formularioLiquidacion->id }}";
                    const url = "{{ url('descuentos-faltantes') }}";
                    return axios.get(url, {
                        params: {
                            formulario_id: formulario_id,
                            tipo: tipo
                        }
                    }).then(response => {
                        if (response.data.res) {
                            this.faltantes = response.data.descuentos;
                        }
                    });
                },
                openRetenciones() {
                    if (this.esEscritura) {
                        $('#modalDescuento').modal('show')
                        this.getDescuentoFaltante('Retencion');
                        this.title = "Retenciones"
                    }
                },
                openDescuentos() {
                    if (this.esEscritura) {
                        $('#modalDescuento').modal('show')
                        this.getDescuentoFaltante('Descuento');
                        this.title = "Descuentos"
                    }
                },
                openBonificaciones() {
                    if (this.esEscritura) {
                        $('#modalDescuento').modal('show')
                        this.getDescuentoFaltante('Bonificacion');
                        this.title = "Bonificaciones"
                    }
                },
                async openCotizaciones() {
                    this.fecha_cotizacion = await $("#fecha_recepcion").val();
                    $('#modalCotizaciones').modal('show')

                    //cargar las cotizaciones diarias y las oficiales
                    //cargar las cotizaciones del dolar

                    console.log(this.fecha_cotizacion)
                },
                closeModal() {
                    $('#modalDescuento').modal('hide');
                },
                cambiarCheckPromedio(){
                    if($("#con_cotizacion_promedio").is(':checked')){
                        $("#divManual").show();
                    }
                    else
                        $("#divManual").hide();
                },
                cambiarCheckManual(){
                    if($("#es_cotizacion_manual").is(':checked')){
                        $("#divValorManual").show();
                    }
                    else
                        $("#divValorManual").hide();
                },
                agregarDescuento(id, tipo) {
                    if (confirm("Seguro que quiere agregar al formulario?")) {
                        const formulario_id = "{{ $formularioLiquidacion->id }}";
                        const url = "{{ route('agregar-descuento') }}";
                        axios.post(url, {
                            formulario_id: formulario_id,
                            descuento_id: id
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.getDescuentoFaltante(tipo);
                                this.cargarDescuentosBonificaciones();
                                this.getResumen();
                            }
                        });
                    }
                },
                eliminarDescuento(id) {
                    if (confirm("Seguro que quiere eliminar del formulario?")) {
                        const formulario_id = "{{ $formularioLiquidacion->id }}";
                        const url = "{{ route('eliminar-descuento') }}";
                        axios.delete(url, {
                            data: {
                                formulario_id: formulario_id,
                                descuento_id: id
                            }
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                this.cargarDescuentosBonificaciones();
                                this.getResumen();
                            }
                        });
                    }
                },
                saveDocument() {
                    $('#formDocumento').ajaxForm({
                        uploadProgress: function (event, position, total, percentComplete) {
                            $("#documento").html('Cargando: ' + percentComplete + "% ...");
                        },
                        success: function () {
                            showDocument();
                        },
                        complete: function (xhr) {
                            var res = xhr.responseJSON;
                            if (res.res) {
                                toastr.success("Registro modificado correctamente!");
                                appFormulario.getResumen();

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
                showDocument() {
                    showDocument();
                },
                save() {
                    console.log("qui")
                    if (confirm('Seguro que quieres cambiar la fecha?'))
                        document.getElementById("frmEdicion").submit();
                },
                anular() {
                    alert('dasdsa')
                },
                finalizarFormulario() {
                    $("#btnFinalizar").prop("disabled", true);
                    var aporte = 0;
                    let msg1 = `¿Estas seguro de finalizar el formulario?`;

                    if (parseFloat(appFormulario.formulario.totales.total_final) < 0)
                        msg1 = msg1 + "\nEl valor neto pagable es NEGATIVO, la diferencia negativa se va abonará como anticipo en el próximo formulario del cliente o productor";
                    if (confirm(msg1)) {
                        if (this.formulario.calculo_aporte != 0 && this.formulario.calculo_aporte != 50) {
                            let msg2 = `¿Desea realizar un aporte de ` + this.formulario.calculo_aporte + ` centavos a la Fundación Colquechaca?`;
                            if (parseFloat(appFormulario.formulario.totales.total_final) > 0) {
                                if (confirm(msg2))
                                    aporte = this.formulario.calculo_aporte;
                            }
                        }
                        const url = "{{ url('finalizar-formulario/pid') }}".replace("pid", this.formulario.id);
                        axios.put(url, {
                            aporte_fundacion: aporte,
                            fecha_liquidacion: document.getElementById("fecha_liquidacion").value
                        }).then(response => {
                            if (response.data.res) {
                                toastr.success(response.data.message);
                                //setInterval('location.reload()', 2500);
                                window.onload = setTimeout("location.reload(true)", 3000)
                            } else {
                                toastr.error(response.data.message);
                                $("#btnFinalizar").prop("disabled", false);
                            }
                        }).catch(e => {
                            toastr.error(e.message);
                            $("#btnFinalizar").prop("disabled", false);
                        });
                    } else
                        $("#btnFinalizar").prop("disabled", false);
                },
            }
        });


        function showDocument() {
            let id = "{{ $formularioLiquidacion->id }}";
            axios.get("/documents/" + id).then(response => {
                $("#documento").html(response.data);
            });
        }

        function refrescarIframe() {
            var iframe = document.getElementById("iframe");
            iframe.src = '/imprimirFormulario/1';
        }


        function imprimirFormulario() {
            url = '/imprimirFormulario/' + "{{ $formularioLiquidacion->id }}" + "/" + document.getElementById('nombreImpresion').value;
            var win = window.open(url, '_blank');
            win.focus();
        }


        function retirar() {
            if (appFormulario.formulario.operacion_cuadrar == 0) {
                let msg = `¿Estás seguro? Vas a retirar el formulario`;
                $('#modalRetiro').modal('show')
            } else {
                alert('No puede retirar el producto mientras no se registren las devoluciones correctamente');
            }
        }

        function restablecer(accion) {
            // var name = prompt("¿cual es su nombre?");
            // if(name == null || name ==""){
            //     txt = "no name provided";
            // }
            // else{
            //     txt = "Hello, " + name;
            // }
            // alert(txt);
            let msg = `¿Estas seguro?\n\nVas a ${accion} el formulario`;

            return confirm(msg);

        }

        function finalizar(accion) {

            if (parseFloat(appFormulario.formulario.totales.total_final) < 0)
                msg = msg + "\nEl valor neto pagable es NEGATIVO, la diferencia negativa se va abonará como anticipo en el próximo formulario del cliente o productor";
            if (confirm(msg1)) {
                let msg2 = `¿Desea realizar un aporte de ` + appFormulario.formulario.calculo_aporte + ` centavos a la Fundación Colquechaca?`;
                confirm(msg2);
            }
        }

        $('#modalEditarDesc').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')
            var valor = button.data('txtvalor')

            var modal = $(this)
            modal.find('.modal-body #idDescuento').val(id);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #valor').val(valor);
        })


        $('#modalCuenta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')

            var modal = $(this)
            modal.find('.modal-body #idCuenta').val(id);
        })

        $('#modalDividir').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var motivo = button.data('txtmotivo')

            var modal = $(this)
            modal.find('.modal-body #idCuenta').val(id);
            modal.find('.modal-body #montoOriginal').val(monto);
            modal.find('.modal-body #motivo').val(motivo);

        })
        $('#modalCambioProducto').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')

            var modal = $(this)
            modal.find('.modal-body #idFormu').val(id);
        })

        $("#formularioModalCambio").on("submit", function () {
            $("#botonGuardarCambio").prop("disabled", true);
        });
    </script>
@endpush
