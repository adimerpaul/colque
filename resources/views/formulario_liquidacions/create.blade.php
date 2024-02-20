@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Nueva compra
        </h1>
    </section>
    <div class="content" id="appNuevoFormulario">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'formularioLiquidacions.store', 'id' => 'frmFormularioCreate', 'onsubmit' => "validarFormulario(event, 'frmFormularioCreate')"]) !!}
                    @include('formulario_liquidacions.fields_pesaje')

                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'name'=>'btnGuardar', 'id'=>'botonGuardar', 'v-if'=>"es_peso_valido"]) !!}
                        <a href="{{ route('formularioLiquidacions.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include("vehiculos.modal")

    @include("choferes.modal")

    @include("clientes.modal")

@endsection

@push('scripts')
    <script type="text/javascript">
        appNuevoFormulario = new Vue({
            el: "#appNuevoFormulario",
            data: {
                peso_bruto: null,
                tara: null,
                sacos: null,
                boletas: null,
                presentacion: null,
                es_peso_valido: true
            },
            mounted(){
              {{--this.peso_bruto = "{{$bruto}}";--}}
              {{--this.tara = "{{$tara}}";--}}
            },
            methods: {
                redondear(valor) {
                    return parseFloat(valor).toFixed(2);
                }
            },
            computed: {
                peso_neto(){
                    let peso =0;
                    if(this.presentacion==='Ensacado')
                        peso = this.peso_bruto - (0.2 * parseFloat(this.sacos));
                    else
                        peso = this.peso_bruto - this.tara
                    this.es_peso_valido = peso > 0
                    return peso
                }
            }
        });
        $("#frmFormularioCreate").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
    </script>
@endpush
