@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pagos / Cobros en dólares
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'pagos-dolares.store', 'id' => 'formularioModal']) !!}

                    <div class="form-group col-sm-6">
                        {!! Form::label('tipo', 'Tipo: *') !!}
                        {!! Form::select('tipo', [null => 'Seleccione...'] +  \App\Patrones\Fachada::listarTiposMovimientos(), null, ['class' => 'form-control', 'required', 'id'=> "tipo", 'onchange'=> "cambiarTipo()" ]) !!}
                    </div>

                    <div class="form-group col-sm-6" id="divProveedor">
                        {!! Form::label('proveedor_id', 'Proveedor: *') !!}
                        <a data-toggle="modal" data-target="#modalProveedor" title="Agregar"
                           class='btn btn-primary btn-xs pull-right'><i
                                class="glyphicon glyphicon-plus"></i></a>
                        <a onclick="getProveedores()" href="#" title="Refrescar" style="background-color: #d9dde0"
                           class='btn btn-xs pull-right'><i
                                class="glyphicon glyphicon-refresh"></i></a>

                        {!! Form::select('proveedor_id', [null => 'Seleccione...'] +  \App\Models\Proveedor::whereEsAprobado(true)->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select2', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('monto', 'Monto $us: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('factura', 'Factura:') !!}
                        {!! Form::text('factura',  null, ['class' => 'form-control',  'id' => 'factura', 'maxlength' => '80']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('glosa', 'Complemento Glosa: ') !!}
                        {!! Form::text('glosa', null, ['class' => 'form-control', 'maxlength' => '300', 'required']) !!}
                    </div>
                    <div class="form-group col-sm-6" >
                        {!! Form::label('nro_recibo', 'Número de comprobante bancario :*') !!}
                        {!! Form::text('numero_recibo', null, ['class' => 'form-control', 'maxlength' => '150', 'required', 'id' => 'numero_recibo_parcial']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @include("proveedores.modal")
    </div>
@endsection
@push('scripts')
    <script>
        function getProveedores() {
            let url = "{{ url('get_proveedores') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="proveedor_id"]').empty();
                    $('select[name="proveedor_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="proveedor_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }
    </script>
@endpush

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $("#formularioModal").on("submit", function() {--}}
{{--            $("#botonGuardar").prop("disabled", true);--}}
{{--        });--}}
{{--        $(document).ready(function () {--}}
{{--            $("#divLote").hide();--}}
{{--        });--}}

{{--        function cambiarTipo() {--}}
{{--            var tipo = document.getElementById('tipo').value;--}}
{{--            let url = "{{ url('get-catalogo-movimientos') }}" + "/" + tipo;--}}
{{--            $.ajax({--}}
{{--                url: url,--}}
{{--                type: "GET",--}}
{{--                dataType: "json",--}}
{{--                success: function (data) {--}}
{{--                    $('select[name="descripcion"]').empty();--}}
{{--                    $('select[name="descripcion"]').append('<option selected value="">Seleccione..</option>');--}}
{{--                    $.each(data, function (key, value) {--}}
{{--                        $('select[name="descripcion"]').append('<option value="' + key + '">' + value + '</option>');--}}
{{--                    });--}}
{{--                },--}}
{{--            });--}}
{{--        }--}}

{{--    </script>--}}
{{--@endpush--}}
