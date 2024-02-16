@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Precios de Laboratorio <b> {{$laboratorio->nombre }}</b>
        </h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" id="appPrecios">
                    <div v-for="(item, index) in precios" :key="index" >
                    <!-- Monto Field -->
                        <div class="form-group col-sm-6">
                            <input type="text" :value="item.producto.nombre" style="border:0; font-weight: bold; font-size: 18px" disabled></input>
                            <input min="1" :value="item.monto" class="form-control" :id="item.id" type="number" step="0.01"></input>
                        </div>
                        <!-- Submit Field -->
                        <div class="form-group col-sm-6" style="margin-top: 6px">
                            <br>
                            <button class="btn btn-primary" @click="actualizar(item.id)">Guardar</button>
                        </div>
                        <br><br><br>
                        <hr style="height: 1px; background-color: #969393; margin-left:5px">
                    </div>
                </div>
                <a href="{{ route('laboratorioQuimicos.index') }}" class="btn btn-default">Cancelar</a>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        appPrecios = new Vue({
            el: "#appPrecios",
            data: {
                precios: [],
            },

            mounted() {
                 this.getPrecios();
            },
            methods: {
                getPrecios() {
                    const laboratorioId = "{{ $laboratorio->id }}";
                    const url = "{{ url('laboratorioPrecios') }}";
                    return axios.get(url, {
                        params: {
                            laboratorioId: laboratorioId,
                        }
                    }).then(response => {
                        this.precios = response.data;
                    }).catch(e => {
                        toastr.error(e.response.message);
                    });
                },

                actualizar(id){
                    var monto = document.getElementById(id).value;
                    if(monto=='' || monto<0){
                        alert('Escriba el monto correspondiente de manera correcta');
                        return;
                    }
                        const url = "{{ url('laboratorioPrecios/pid') }}".replace('pid', id);
                    axios.put(url, {
                        monto: monto
                    }).then(response => {
                        if(response.data.res){
                            this.getPrecios();
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
