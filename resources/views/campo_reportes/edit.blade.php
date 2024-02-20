@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Campos para reporte {{$tipo->nombre}}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body" id="appCampoReporte">
                <div class="row">
                    <div class="col-md-12" style="border-left: 1px solid #1b4b72">
                        @include('campo_reportes.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        appCampoReporte = new Vue({
            el: "#appCampoReporte",
            data: {
                campos: [],
            },
            created() {
            },
            mounted() {
                this.getCampos();
            },
            methods: {
                getCampos() {
                    const tipo_reporte_id = "{{ $tipo->id }}";
                    const url = "{{ url('campoReportes') }}";
                    return axios.get(url, {
                        params: {
                            tipo_reporte_id: tipo_reporte_id,
                        }
                    }).then(response => {
                        this.campos = response.data;
                    }).catch(e => {
                        toastr.error(e.response.message);
                    });
                },

                cambiar(id){
                    const url = "{{ url('campoReportes/pid') }}".replace('pid', id);
                    axios.put(url).then(response => {
                        if(response.data.res){
                            this.getCampos();
                            toastr.success(response.data.message);
                        }
                        else
                            toastr.error(response.data.message);
                    }).catch(e => {
                        toastr.error(e.message);
                    });
                }
            }
        });
    </script>
@endpush


