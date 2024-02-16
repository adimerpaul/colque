@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1>
            Recepción de muestras
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body" id="appRecepcionMuestra">
                <div class="row">


                @include('lab.recepcion.fields')

                <!-- Submit Field -->
{{--                    <div class="form-group col-sm-12">--}}
{{--                        <button type="btn btn-primary" class="btnGuardar" @click="saveRecepcion">Guardar</button>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        appRecepcionMuestra = new Vue({
            el: "#appRecepcionMuestra",
            data: {
                minerales: [],
                humedad: false,
                estanio: false,
                plata: false,
                cantidadPlata: '',
                cantidadEstanio: '',
                cantidadHumedad: '',
                montoPagado: 0,
                buscador: '',
                clienteId: ''
            },
            computed: {
                montoHumedad() {
                    return this.cantidadHumedad * {{\App\Patrones\FachadaLab::getPrecioHumedad()}}
                },
                montoEstanio() {
                    return this.cantidadEstanio * {{\App\Patrones\FachadaLab::getPrecioEstanio()}}
                },
                montoPlata() {
                    return this.cantidadPlata * {{\App\Patrones\FachadaLab::getPrecioPlata()}}
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
                saveRecepcion() {
                    if (document.getElementById('cliente_id').value === '')
                        return toastr.error("Seleccione el Cliente").options = {
                            positionClass: 'toast-top-center',
                    };
                    if ((this.cantidadEstanio == '' && this.estanio) || (this.cantidadHumedad == '' && this.humedad)
                        || (this.cantidadPlata == '' && this.plata))
                        toastr.error("Llene todas las cantidades de muestras");
                    else {
                        $("#saveRecepcion").prop("disabled", true);
                        let url = "{{ url('recepcion-lab') }}";
                        axios.post(url, {
                            cliente_id: document.getElementById('cliente_id').value,
                            cantidadEstanio: this.estanio ? this.cantidadEstanio : 0,
                            cantidadPlata: this.plata ? this.cantidadPlata : 0,
                            cantidadHumedad: this.humedad ? this.cantidadHumedad : 0,
                            monto: this.montoPagado
                        }).then(response => {
                            if (response.data.res) {
                                Swal.fire({
                                    title: response.data.codigo,
                                    text: "Registro guardado",
                                    icon: 'warning',
                                    type: "success",
                                    confirmButtonText: "Aceptar",
                                    confirmButtonColor: "#3C8DBC"
                                }).then(function () {
                                    window.location.href ='/inicio-lab';
                                });
                                // this.cliente_id = '';
                                // this.cantidadEstanio = 1;
                                // this.cantidadHumedad = 1;
                            } else{
                                toastr.error("Error! vuelve a intentarlo más tarde.");
                                $("#saveRecepcion").prop("disabled", false);
                            }
                        }).catch(e => {
                            alert(formarListaDeErrores(e.response.data.errors));
                            $("#saveRecepcion").prop("disabled", false);
                        });
                    }
                },
                buscarCliente() {
                    const url = "{{ url('buscar-cliente-lab?parametro=variable') }}".replace('variable', this.buscador);
                    axios.get(url).then(response => {
                        if (response.data.res) {
                            document.getElementById("nombreCliente").value = response.data.data.nombre;
                            document.getElementById("nitCliente").value = response.data.data.nit;
                            this.clienteId = response.data.data.id;
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        document.getElementById("nombreCliente").value = "NO SE ENCONTRÓ EL CLIENTE";
                        document.getElementById("nitCliente").value = "NO SE ENCONTRÓ EL CLIENTE";
                        this.clienteId = '';
                    });
                }
            }
        });
        function onlyNumberKey(evt) {

            // Only ASCII character in that range allowed
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
        function cambiarBienvenida(e){
            var nom= e.target.getAttribute('name');
            document.getElementById('txtBienvenida').innerHTML = nom;
        }
        $(document).ready(function() {
            var element = $('#cliente_id');

            element.change(function() {
                var selection = $('option:selected', element); // $(':selected', element);
                var text = selection.text();
                var nombrecortado = text.split(" ");
                var primernombre = nombrecortado[0];
                document.getElementById('txtBienvenida').innerHTML="HOLA " + primernombre+ " ¿QUÉ HARÁS ANALIZAR HOY?"
                if(primernombre=="Seleccione...")
                    document.getElementById('txtBienvenida').innerHTML="HOLA, ¿QUÉ HARÁS ANALIZAR HOY?"
            });
        });
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

    /*Buscador*/
    .input[type=text] {
        max-width: 180px;
        height: 20px;
        background-color: #fff;
        color: #242424;
        padding: .15rem .5rem;
        min-height: 40px;
        border-radius: 4px;
        outline: none;
        border: none;
        box-shadow: 0px 10px 20px -18px #0f91bd;
        border-bottom: 3px solid #0f91bd;
        transition: .10s ease;
    }

    .input[type=text]:hover {
        outline: 2px solid #0f91bd;
        max-width: 200px;
    }

    .input[type=text]:focus {
        border-bottom: 3px solid #0f91bd;
        border-radius: 5px 5px 2px 2px;
        transform: scale(1.1);
    }

    /*boton de busqueda*/
    .btnGuardar {
        --color: #0f91bd;
        font-family: inherit;
        display: inline-block;
        width: 6em;
        height: 2.6em;
        line-height: 2.5em;
        overflow: hidden;
        margin: 20px;
        font-size: 17px;
        z-index: 1;
        color: var(--color);
        border: 2px solid var(--color);
        border-radius: 6px;
        position: relative;
    }


    .btnGuardar:hover {
        background: var(--color);
        color: white;
    }

    .btnGuardar:before {
        top: 100%;
        left: 100%;
        transition: .3s all;
    }

    .btnGuardar:hover::before {
        top: -30px;
        left: -30px;
    }

    /*fondo de campos de busqueda */
    .form {
        display: flex;
        flex-direction: column;
        background: #86d5f0;
        background: -webkit-linear-gradient(to right, #29454e, #2e6b7f);
        background: linear-gradient(to right, #40849b, #79d6f5);
        padding: 20px;
        border-radius: 10px;
        max-width: 350px;
    }

    /*fondo de datos de busqueda*/
    .search__input {
        font-family: inherit;
        font-size: inherit;
        background: #86d5f0;
        background: -webkit-linear-gradient(to right, #29454e, #2e6b7f);
        background: linear-gradient(to right, #40849b, #79d6f5);
        border: 0;
        color: #fff;
        padding: 0.7rem 1rem;
        border-radius: 30px;
        width: 100%;
        transition: all ease-in-out .5s;
        margin-right: -2rem;
    }

     .toast-top-center {
         top: 120px;
         left:50%;
         margin:0 0 0 -150px;
     }
</style>
