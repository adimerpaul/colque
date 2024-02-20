<div class="form-group col-sm-12">
    {!! Form::open(['method' => 'POST', 'v-on:submit.prevent' => 'getValorTonelada']) !!}
    <div class="form-group col-sm-6">
        {!! Form::label('fecha', 'Fecha: *') !!}
        {!! Form::date('fecha', date('Y-m-d'), ['class' => 'form-control','id' => 'fecha', 'v-model' =>'fecha', 'required']) !!}
    </div>
    @include('cotizaciones_clientes.fields')

    <div class="col-sm-12 text-center">

        <div class="alert alert-success text-center">
            <strong>Valor Por Tonelada: </strong>
            <h2>@{{ dosDecimales(valor) }}</h2>
        </div>

        <div class="alert alert-info text-center">
            <strong>Cotización Diaria: </strong>
            <h2>@{{ diaria }}&nbsp;</h2>
        </div>
    </div>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Calcular', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
</div>
