<div class="form-group col-sm-12">
{!! Form::open(['route' => 'movimientos.store', 'id' => 'formularioModal']) !!}

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
        {!! Form::label('monto', 'Monto BOB: *') !!}
        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('descripcion', 'Descripción: *') !!}
        {!! Form::select('descripcion',  [null => 'Seleccione...'] , null, ['class' => 'form-control select2', 'required', 'id' => 'descripcion',  'onchange'=> "cambiarDescripcion()"]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('oficina', 'Oficina: *') !!}
        {!! Form::select('oficina', \App\Patrones\Fachada::getOficinasMovimiento(), null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('glosa', 'Complemento Glosa: ') !!}
        {!! Form::text('glosa', null, ['class' => 'form-control', 'maxlength' => '300']) !!}
    </div>

    <div class="form-group col-sm-6" id="divTipoLote">
        {!! Form::label('tipo', 'Tipo Lote: *') !!}
        {!! Form::select('tipoLote', [null => 'Seleccione...'] +  \App\Patrones\Fachada::listarTiposLotes(), null, ['class' => 'form-control', 'id'=> "tipoLote",  'onchange'=> "cambiarTipoLote()" ]) !!}
    </div>

    <div class="form-group col-sm-6" id="divLote">
        {!! Form::label('lote', 'Lote: *') !!}
        {!! Form::select('lote',  [null => 'Seleccione...'] , null, ['class' => 'form-control select2', 'id' => 'lote']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
    </div>

    {!! Form::close() !!}
</div>

@push('scripts')
    <script>
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroReciboTotal").hide();
            $("#divTipoLote").hide();
            $("#divLote").hide();

        });
        function cambiarMetodoTotal() {
            const input = document.getElementById('numero_recibo_total');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#bancoDiv").show();
                $("#nroReciboTotal").show();
                input.setAttribute('required', '');
            } else {
                $("#bancoDiv").hide();
                $("#nroReciboTotal").hide();
                input.removeAttribute('required', '');
            }
        }

        function cambiarTipo() {
            var tipo = document.getElementById('tipo').value;
            let url = "{{ url('get-catalogo-movimientos') }}" + "/" + tipo;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="descripcion"]').empty();
                    $('select[name="descripcion"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="descripcion"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function cambiarDescripcion() {
            var descripcion = document.getElementById('descripcion').value;
            const tipoLote = document.getElementById('tipoLote');
            const lote = document.getElementById('lote');

            if(descripcion.includes('EN LOTE')){
                $("#divTipoLote").show();
                $("#divLote").show();
                tipoLote.setAttribute('required', '');
                lote.setAttribute('required', '');
            }
            else{
                $("#divTipoLote").hide();
                $("#divLote").hide();
                tipoLote.removeAttribute('required', '');
                lote.removeAttribute('required', '');
                tipoLote.value="";
                lote.value="";
            }
        }

        function cambiarTipoLote() {
            var tipo = document.getElementById('tipoLote').value;
            let url = "{{ url('get-lotes-movimientos') }}" + "/" + tipo;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="lote"]').empty();
                    $('select[name="lote"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="lote"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }
    </script>
@endpush
