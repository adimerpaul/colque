<div class="form-group col-sm-12">
    {!! Form::open(['route' => 'cotizaciones-clientes.imprimir', 'target'=>'_blank']) !!}

    @include('cotizaciones_clientes.fields')

    <div class="form-group col-sm-6">
        {!! Form::label('pesoBruto', 'Peso Bruto (Kg): *') !!}
        {!! Form::number('pesoBruto', null, ['class' => 'form-control', 'v-model' =>'pesoBruto', 'required', 'min' => 0, 'step'=>'0.01']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('retenciones', 'Retenciones y descuentos (%): *') !!}
        {!! Form::number('retenciones', null, ['class' => 'form-control', 'v-model' =>'retenciones', 'required', 'min' => 0, 'step'=>'0.01']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('humedad', 'Humedad (%): *') !!}
        {!! Form::number('humedad', null, ['class' => 'form-control', 'v-model' =>'humedad', 'required', 'min' => 0, 'step'=>'0.01']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('merma', 'Merma: *') !!}
        {!! Form::number('merma', null, ['class' => 'form-control', 'v-model' =>'merma', 'required', 'min' => 0, 'step'=>'0.01']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('tara', 'Tara: *') !!}
        {!! Form::number('tara', null, ['class' => 'form-control', 'v-model' =>'tara', 'required', 'min' => 0, 'step'=>'0.0001']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('productor', 'Productor: *') !!}
        {!! Form::text('productor', null, ['class' => 'form-control', 'v-model' =>'productor', 'required', 'min' => 0, 'step'=>'0.01']) !!}
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Generar', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
</div>
