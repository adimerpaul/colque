<div class="row" style="padding: 15px">
{!! Form::open(['route' => 'cotizacions.storeMultiple']) !!}
<!-- Fecha Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('fecha', 'Fecha: *') !!}
        {!! Form::text('fecha', date("d/m/Y"), ['class' => 'form-control datepicker', 'autocomplete' => 'off', 'required']) !!}
    </div>
    <div class="col-sm-12">
        @foreach($minerales as $mineral)
            <div class="col-sm-5">
                <div class="row">
                    <h3>{{ $mineral->nombre }}</h3>

                    <!-- Monto Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('monto', 'Cotización: *') !!}
                        {!! Form::number('monto[]', null, ['class' => 'form-control', 'step'=>'.001', 'required']) !!}
                    </div>

                    <!-- Unidad Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('unidad', 'Unidad: *') !!}
                        @if($mineral->id===1 OR $mineral->id===7)
                            {!! Form::text('unidad[]', \App\Patrones\UnidadCotizacion::OT, ['class' => 'form-control', 'required', 'readonly']) !!}
                        @else
                            {!! Form::text('unidad[]', \App\Patrones\UnidadCotizacion::TM, ['class' => 'form-control', 'required', 'readonly']) !!}
                        @endif
                    </div>

                    {!! Form::hidden('mineral_id[]',$mineral->id, ['class' => 'form-control', 'maxlength' => '20']) !!}
                </div>
            </div>
            <div class="col-sm-1">
            </div>
    @endforeach
    <!-- Submit Field -->
        <div class="form-group col-sm-12" style="margin-top: 25px">
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('materials.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>
