    <!-- Descricion-->
    <div class="form-group col-sm-12">
        {!! Form::label('descripcion', 'Descripción :*') !!}
        {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
    </div>
    <!--tipo-->
    <div class="form-group col-sm-4">
        {!! Form::label('tipo', 'Tipo :*') !!}
        {!! Form::select('tipo', \App\Patrones\Fachada::getTiposMovimientosIE(), nulL, ['class' => 'form-control', 'required', 'id' =>'tipo_id',
            'onchange'=>'getProximoCodigo()'])!!}
    </div>

    <!-- si pertenece al lote-->
    @if(isset($movimientoCatalogo))
    <div class="form-group col-sm-12">
        {!! Form::label('es_lote', 'Lote :*') !!}
        {!! Form::checkbox('es_lote', '1', $movimientoCatalogo->es_lote) !!}
    </div>
    @else
    <div class="form-group col-sm-12">
        {!! Form::label('es_lote', 'Lote :*') !!}
        {!! Form::checkbox('es_lote', '1', false) !!}
    </div>
    @endif
    <!--Guardar y Cancelar-->        
    <div class="form-group col-sm-12">
        <br>
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('movimientos-catalogos.index') }}" class="btn btn-default">Cancelar</a>
    </div>



