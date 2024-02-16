<div class="row">
{!! Form::open(['method' => 'POST', 'id'=>'frmVenta', 'v-on:submit.prevent' => 'savePesaje',
    'onkeydown'=>"return event.key != 'Enter';"]) !!}

<!-- Peso Bruto Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('peso_bruto_humedo', 'Peso bruto húmedo: (KG) *') !!}
        {!! Form::number('peso_bruto_humedo', null, ['class' => 'form-control', 'v-model'=> 'pesaje.peso_bruto_humedo', 'required', 'min'=>0, 'step'=>"any"]) !!}
    </div>

    <!-- Peso Neto Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('tara', 'Tara: (KG) *') !!}
        {!! Form::number('tara', null, ['class' => 'form-control', 'v-model'=> 'pesaje.tara', 'required', 'min'=>0, 'step'=>"any"]) !!}
    </div>

    <!-- Chofer Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('chofer_id', 'Conductor: *') !!}
        <a data-toggle="modal" data-target="#modalChofer" title="Agregar"
           class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
        <a onclick="getChoferes()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i
                class="glyphicon glyphicon-refresh"></i></a>

        {!! Form::select('chofer_id', [null => 'Seleccione...']  + \App\Models\Chofer::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select', 'required', 'id' =>'chofer_id', 'v-model'=> 'pesaje.chofer_id']) !!}
    </div>

    <!-- Vehiculo Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('vehiculo_id', 'Vehículo: *') !!}
        <a data-toggle="modal" data-target="#modalVehiculo" title="Agregar" class='btn btn-primary btn-xs pull-right'><i
                class="glyphicon glyphicon-plus"></i></a>
        <a onclick="getVehiculos()" href="#" title="Refrescar" style="background-color: #d9dde0"
           class='btn btn-xs pull-right'><i class="glyphicon glyphicon-refresh"></i></a>
        {!! Form::select('vehiculo_id', [null => 'Seleccione...']  + \App\Models\Vehiculo::get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control select', 'required', 'id' =>'vehiculo_id', 'v-model'=> 'pesaje.vehiculo_id']) !!}
    </div>

    <!-- Numero pesaje Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('numero_pesaje', 'Nro. pesaje: *') !!}
        {!! Form::number('numero_pesaje', null, ['class' => 'form-control', 'v-model'=> 'pesaje.numero_pesaje', 'required', 'min'=>0]) !!}
    </div>

    <!-- Submit Field -->
    @if($venta->estado==\App\Patrones\EstadoVenta::EnProceso )
{{--and \App\Patrones\Permiso::esOperaciones())--}}

        <div class="form-group col-sm-12">
            {!! Form::submit('Guardar pesaje', ['class' => 'btn btn-primary', 'id'=>'btnGuardarVenta']) !!}
        </div>
    @endif
    {!! Form::close() !!}
</div>

@push('scripts')
    <script>
        function getVehiculos() {
            let url = "{{ url('get_vehiculos') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="vehiculo_id"]').empty();
                    $('select[name="vehiculo_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="vehiculo_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function getChoferes() {
            let url = "{{ url('get_choferes') }}";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('select[name="chofer_id"]').empty();
                    $('select[name="chofer_id"]').append('<option selected value="">Seleccione..</option>');
                    $.each(data, function (key, value) {
                        $('select[name="chofer_id"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
            });
        }

        function getClientes() {
            let url = "{{ url('get_clientes') }}";
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
    </script>
@endpush
