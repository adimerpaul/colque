@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Minerales del producto [Pagables y Penalizables]
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body" id="appProductoMineral">
                <div class="row">
                    <div class="col-md-3">
                        @include('productos.show_fields')
                        <a href="{{ route('productos.index') }}" class="btn btn-default">Volver</a>
                    </div>
                    <div class="col-md-9" style="border-left: 1px solid #1b4b72">
                        @include('produto_minerales.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        appProductoMineral = new Vue({
            el: "#appProductoMineral",
            data: {
                minerales: [],
            },
            created() {
            },
            mounted() {
                this.getMinerales();
            },
            methods: {
                getMinerales() {
                    const producto_id = "{{ $producto->id }}";
                    const url = "{{ url('producto_minerales') }}";
                    return axios.get(url, {
                        params: {
                            producto_id: producto_id,
                        }
                    }).then(response => {
                        this.minerales = response.data;
                    }).catch(e => {
                        toastr.error(e.response.message);
                    });
                },
                eliminar(id) {
                    if (confirm("Seguro que quire quitar el mineral del producto")) {
                        const url = "{{ url('producto_minerales/pid') }}".replace('pid', id);
                        axios.delete(url).then(response => {
                            if(response.data.res)
                                location.reload();
                            else
                                toastr.error(response.data.message);
                        }).catch(e => {
                            toastr.error(e.message);
                        });
                    }
                },
                cambiar(id){
                    const url = "{{ url('producto_minerales/pid') }}".replace('pid', id);
                    axios.put(url).then(response => {
                        if(response.data.res)
                            this.getMinerales();
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


