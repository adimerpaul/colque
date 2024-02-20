@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1>
            Editar {{$pedido->codigo_pedido}}
            {!! \App\Patrones\Fachada::estadoLaboratorio($pedido->estado)  !!}
        </h1>

    </section>
    <div class="content" id="appEditarRecepcion">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1-1">
                        <i class="fa fa-money"></i>
                        Cantidad y Monto </a>
                </li>
                <li><a data-toggle="tab" href="#tab_2-2"><i class="fa fa-list"></i> Lotes</a>
                </li>


            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    <div class="row" style="{{ $pedido->a_caja?'pointer-events: none;':'' }}">
                        <br>
                        @include('lab.recepcion.fields_edit')

                    </div>
                </div>
                <div class="tab-pane fade" id="tab_2-2">
                    <div class="row" style="{{ $pedido->onlyRead }}">

                        @include('lab.recepcion.fields_finalizar')
                    </div>
                </div>

            </div>
            <!-- /.tab-content -->

        </div>

        <div class="form-group col-sm-12">
            @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Recepcionado)
                @if(!$pedido->a_caja)
                    <button class="btn btn-primary btn-lg" @click="enviarCaja">Enviar a Caja</button>
                @endif
                <button class="btn btn-success btn-lg" @click="finalizar">Aceptar</button>
                <button class="btn btn-danger btn-lg" @click="rechazar">Rechazar</button>
            @endif
            <a href="{{ route('recepcion-lab.index') }}" class="btn btn-default btn-lg">Cancelar</a>
        </div>
        <div class="box-body">

        </div>


        @include("lab.clientes.modal_registro")

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        appEditarRecepcion = new Vue({
            el: "#appEditarRecepcion",
            data: {
                pedido: [],
                humedad: "{{$pedido->cantidad_humedad>0 ? true: false}}",
                estanio: "{{$pedido->cantidad_estanio>0 ? true: false}}",
                plata: "{{$pedido->cantidad_plata>0 ? true: false}}",
                cantidadEstanio: "{{$pedido->cantidad_estanio}}",
                cantidadHumedad: "{{$pedido->cantidad_humedad}}",
                cantidadPlata: "{{$pedido->cantidad_plata}}",
                montoPagado: "{{$pedido->anticipo}}",
                clienteId: '',
                nombre: '',
                nit: '',
                celular: '',
                direccion: '',
                complemento: '',
                esFormulario: '',
            },
            mounted() {
                this.getPedido();
            },
            computed: {
                montoHumedad() {
                    return this.cantidadHumedad * "{{$precioHumedad}}"
                },
                montoEstanio() {
                    return this.cantidadEstanio * "{{$precioEstanio}}"
                },
                montoPlata() {
                    return this.cantidadPlata * "{{$precioPlata}}"
                },
                montoTotal() {
                    if (!this.humedad && !this.estanio && !this.plata)
                        return 0;
                    else if (!this.plata && !this.humedad && this.estanio)
                        return this.montoEstanio;
                    else if (this.plata && !this.humedad && this.estanio)
                        return this.montoEstanio + this.montoPlata;
                    else if (this.humedad && !this.estanio && this.plata)
                        return this.montoHumedad + this.montoPlata;
                    else if (!this.humedad && !this.estanio && this.plata)
                        return this.montoPlata;
                    else if (this.humedad && !this.estanio && !this.plata)
                        return this.montoHumedad;
                    else if (this.humedad && this.estanio && !this.plata)
                        return this.montoHumedad + this.montoEstanio;
                    else
                        return this.montoEstanio + this.montoHumedad + this.montoPlata;
                },
                saldo() {
                    return this.montoTotal - this.montoPagado;
                }
            },
            methods: {
                enviarCaja() {
                    let url = "{{ url('lab/enviar-caja') }}";
                    axios.post(url, {
                        id: "{{ $pedido->id }}",
                        monto: this.montoPagado,
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            location.reload();
                        } else
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                saveCliente() {
                    let url = "{{ url('clientes-lab') }}";
                    axios.post(url, {
                        nombre: this.nombre,
                        nit: this.nit,
                        celular: this.celular,
                        direccion: this.direccion,
                        complemento: this.complemento,
                        esModal: true
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            this.nombre = '';
                            this.nit = '';
                            this.celular = '';
                            this.direccion = '';
                            this.complemento = '';
                            getClientes();
                            $('#modalClienteRegistro').modal('hide');
                        } else
                            toastr.error("Error! vuelve a intentarlo más tarde.");
                    }).catch(e => {
                        alert(formarListaDeErrores(e.response.data.errors));
                    });
                },
                getPedido() {
                    const pedidoId = "{{ $pedido->id }}";
                    axios.get("/recepcion-lab/" + pedidoId).then(response => {
                        this.pedido = response.data.data;
                    })
                },
                finalizar() {
                    const pedidoId = "{{ $pedido->id }}";
                    let url = "{{ url('finalizar-recepcion-lab/pid') }}".replace("pid", pedidoId);
                    axios.put(url).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            document.location.href = "/cajas-lab";
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        alert(formarListaDeErrores(e.response.data.errors));
                    });
                },
                rechazar() {
                    const pedidoId = "{{ $pedido->id }}";
                    let url = "{{ url('anular-recepcion-lab/pid') }}".replace("pid", pedidoId);
                    axios.put(url).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            document.location.href = "/recepcion-lab";
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        alert(formarListaDeErrores(e.response.data.errors));
                    });
                },
                actualizarLote(id, lote, tipo = 'Compra') {

                    const url = "{{ url('actualizar-lote-lab/pid') }}".replace('pid', id);
                    axios.put(url, {
                        lote: lote,
                        tipo: tipo.charAt(0)
                    }).then(response => {
                        if (response.data.res) {
                            if (response.data.cliente == 1) {
                                document.getElementById('ensayo' + id).style.backgroundColor = "white";
                                document.getElementById('ensayo' + id).style.color = "#37474F";
                            }
                            toastr.success(response.data.message);
                        } else {
                            document.getElementById('ensayo' + id).value = "";
                            document.getElementById('ensayo' + id).style.backgroundColor = "#EF5350";
                            document.getElementById('ensayo' + id).style.color = "white";
                            toastr.error(response.data.message);
                        }
                    }).catch(e => {
                        toastr.error(e.message);
                    });
                },
                updateRecepcion() {
                    if (this.estanio == false)
                        this.cantidadEstanio = 0;

                    if (this.humedad == false)
                        this.cantidadHumedad = 0;

                    if (this.plata == false)
                        this.cantidadPlata = 0;

                    const pedidoId = "{{ $pedido->id }}";
                    let url = "{{ url('ensayos-lab/pid') }}".replace("pid", pedidoId);
                    axios.put(url, {
                        cantidadEstanio: this.cantidadEstanio,
                        cantidadHumedad: this.cantidadHumedad,
                        cantidadPlata: this.cantidadPlata,
                        monto: this.montoPagado
                    }).then(response => {
                        if (response.data.res) {
                            toastr.success(response.data.message);
                            location.reload();
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        alert(formarListaDeErrores(e.response.data.errors));
                    });
                },

            }
        });

        function getClientes() {
            let url = "{{ url('get-clientes-lab') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="cliente_id"]').empty();
                    $('select[name="cliente_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="cliente_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }

    </script>
@endpush

<style>
    /*Switch*/
    .cl-toggle-switch {
        position: relative;
    }

    .cl-switch {
        position: relative;
        display: inline-block;
    }

    /* Input */
    .cl-switch > input {
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        z-index: -1;
        position: absolute;
        right: 6px;
        top: -8px;
        display: block;
        margin: 0;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        background-color: rgb(0, 0, 0, 0.38);
        outline: none;
        opacity: 0;
        transform: scale(1);
        pointer-events: none;
        transition: opacity 0.3s 0.1s, transform 0.2s 0.1s;
    }

    /* Track switch*/
    .cl-switch > span::before {
        content: "";
        float: right;
        display: inline-block;
        margin: 5px 0 5px 10px;
        border-radius: 7px;
        width: 36px;
        height: 14px;
        background-color: rgb(0, 0, 0, 0.38);
        vertical-align: top;
        transition: background-color 0.2s, opacity 0.2s;
    }

    /* Thumb switch*/
    .cl-switch > span::after {
        content: "";
        position: absolute;
        top: 2px;
        right: 16px;
        border-radius: 50%;
        width: 23px;
        height: 23px;
        background-color: #fff;
        box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
        transition: background-color 0.2s, transform 0.2s;
    }

    /* Checked switch*/
    .cl-switch > input:checked {
        right: -10px;
        background-color: #85b8b7;
    }

    .cl-switch > input:checked + span::before {
        background-color: #85b8b7;
    }

    .cl-switch > input:checked + span::after {
        background-color: #0f91bd;
        transform: translateX(16px);
    }

    /* Hover, Focus switch*/
    .cl-switch:hover > input {
        opacity: 0.04;
    }

    .cl-switch > input:focus {
        opacity: 0.12;
    }

    .cl-switch:hover > input:focus {
        opacity: 0.16;
    }

    /* Active switch */
    .cl-switch > input:active {
        opacity: 1;
        transform: scale(0);
        transition: transform 0s, opacity 0s;
    }

    .cl-switch > input:active + span::before {
        background-color: #8f8f8f;
    }

    .cl-switch > input:checked:active + span::before {
        background-color: #85b8b7;
    }

    /* Disabled */
    .cl-switch > input:disabled {
        opacity: 0;
    }

    .cl-switch > input:disabled + span::before {
        background-color: #ddd;
    }

    .cl-switch > input:checked:disabled + span::before {
        background-color: #bfdbda;
    }

    .cl-switch > input:checked:disabled + span::after {
        background-color: #0f91bd;
    }
</style>
