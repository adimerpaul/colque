<div class="row" style="padding: 15px">
{!! Form::open(['route' => 'cotizacionOficials.storeMultiple', 'files' => 'true']) !!}
<!-- Fecha Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('fecha', 'Fecha: *') !!}
        {!! Form::text('fecha', date("d/m/Y"), ['class' => 'form-control datepicker', 'autocomplete' => 'off', 'required']) !!}
    </div>

    <div class="col-sm-6">
        <p style="margin-top: 30px">
            <input id="url_documento" class="url_documento" type="file" name="url_documento[]"
                   accept="application/pdf" multiple required/>
        </p>
    </div>
    <div class="col-sm-12">
        @foreach($minerales as $mineral)
            <hr>

            <h3>{{ $mineral->nombre }}</h3>
            <!-- Monto Field -->
            <div class="form-group col-sm-3">
                {!! Form::label('monto', 'Cotización: *') !!}
                {!! Form::number('monto[]', null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
            </div>

            <!-- Unidad Field -->
            <div class="form-group col-sm-3">
                {!! Form::label('unidad', 'Unidad: *') !!}
                {!! Form::select('unidad[]', [null => 'Seleccione...'] + \App\Patrones\Fachada::unidadesCotizacion(), $mineral->ultima_cotizacion_oficial ? $mineral->ultima_cotizacion_oficial->unidad : null, ['class' => 'form-control', 'step'=>'.01', 'required']) !!}
            </div>

            <!-- Diaria Field -->
            <div class="form-group col-sm-3">
                {!! Form::label('alicuota_exportacion', 'Alicuota exportación: *') !!}
                {!! Form::number('alicuota_exportacion[]', $mineral->ultima_cotizacion_oficial? $mineral->ultima_cotizacion_oficial->alicuota_exportacion : null,
                    ['class' => 'form-control', 'step'=>'.01', 'required', 'id' => $mineral->id, 'onblur'=> 'calcular(this.value, this.id)']) !!}
            </div>

            <!-- Diaria Field -->
            <div class="form-group col-sm-3">
                {!! Form::label('alicuota_interna', 'Alicuota ventas internas: *') !!}
                {!! Form::number('alicuota_interna[]', $mineral->ultima_cotizacion_oficial? $mineral->ultima_cotizacion_oficial->alicuota_interna : null,
                    ['class' => 'form-control', 'step'=>'.01', 'required', 'readonly', 'id' => 'interna'. $mineral->id]) !!}
            </div>

        {!! Form::hidden('mineral_id[]',$mineral->id, ['class' => 'form-control', 'maxlength' => '20']) !!}
    @endforeach
    <!-- Submit Field -->
        <div class="form-group col-sm-6" style="margin-top: 25px">
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('materials.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@push('scripts')
    <script type="text/javascript">
        function calcular (valor, id) {

            document.getElementById('interna'+id).value = (valor*60/100);
          //   $("#4").val = 9;


        }
    </script>
@endpush
