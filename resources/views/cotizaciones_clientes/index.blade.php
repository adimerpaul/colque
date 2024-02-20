@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotizador
        </h1>
    </section>
    <div class="content" id="appCotizacionCliente">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1-1"
                       data-toggle="tab">
                        <i class="fa fa-money"></i>
                        Valor por tonelada </a>
                </li>
                <li><a href="#tab_2-2" data-toggle="tab"><i class="fa fa-file-text"></i> Proforma</a></li>

            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    <div class="row">
                    @include('cotizaciones_clientes.valor_tonelada')
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    <div class="row">
                        @include('cotizaciones_clientes.proforma')
                    </div>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        appCotizacionCliente = new Vue({
            el: "#appCotizacionCliente",
            data: {
                transporte: 0,
                leyAg: 0,
                leyPb: 0,
                leyZn: 0,
                leySn: 0,
                leyCu: 0,
                leySb: 0,
                productoId: '',
                valor: 0,
                pesoBruto: 0,
                tara: 0,
                merma: 0,
                humedad: 0,
                productor: '',
                retenciones: 0,
                diaria: '',
                fecha: {{date('Y-m-d')}},
                tipo_material: ''
            },
            methods: {
                getValorTonelada() {
                    let url = "{{ url('cotizaciones-clientes/valor-tonelada') }}";
                    axios.post(url, {
                        transporte: parseInt(this.transporte),
                        leyAg: this.leyAg,
                        leyZn: this.leyZn,
                        leyPb: this.leyPb,
                        leySn: this.leySn,
                        leyCu: this.leyCu,
                        leySb: this.leySb,
                        productoId: this.productoId,
                        fecha: this.fecha,
                        tipo_material: this.tipo_material,


                    }).then(response => {
                        if (response.data.res) {
                            this.valor = response.data.valor;
                            this.diaria = response.data.diaria;
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
                dosDecimales(numero) {
                    return parseFloat(numero).toFixed(2);
                },
                imprimir() {
                    let url = "{{ url('cotizaciones-clientes/imprimir') }}";
                    axios.post(url, {
                        transporte: parseInt(this.transporte),
                        leyAg: this.leyAg,
                        leyZn: this.leyZn,
                        leyPb: this.leyPb,
                        leySn: this.leySn,
                        leySb: this.leySb,
                        productoId: this.productoId,
                        pesoBruto: this.pesoBruto,
                        tara: this.tara,
                        merma: this.merma,
                        humedad: this.humedad,
                        productor: this.productor,
                        retenciones: this.retenciones,
                    }).then(response => {
                        if (response.data.res) {
                            this.valor = response.data.valor;
                        } else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error("Error! vuelve a intentarlo más tarde.");
                    });
                },
            },
        });
    </script>
@endpush
