<div id="modalPlanilla" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generar planilla de sueldos y salarios</h4>
            </div>
            <div class="modal-body">
             {!! Form::open(['route' => 'planillas-sueldos.store']) !!}   
                <div class="form-group">
                    {!! Form::label('fechaMesAnio', 'Fecha :*') !!}
                    {!! Form::month('fechaMesAnio', \Carbon\Carbon::now()->subMonth()->format('Y-m'), ['class' => 'form-control', 'required']) !!}
                </div>
                <hr>
                <div class="form-group">
                    {!! Form::label('tipo_planilla', 'Tipo de planilla:*') !!}
                    <div class="form-check">
                        {!! Form::label('eventuales', 'Eventuales') !!}
                        {!! Form::checkbox('eventuales', 'eventuales', false, ['class' => 'form-check-input', 'id' => 'eventuales']) !!} 

                        {!! Form::label('indefinido', 'Contrato') !!}
                        {!! Form::checkbox('contrato', 'contrato', false, ['class' => 'form-check-input', 'id' => 'indefinido']) !!}

                        {!! Form::label('ambos', 'Ambos') !!}
                        {!! Form::checkbox('ambos', 'ambos', true, ['class' => 'form-check-input', 'id' => 'ambos']) !!}
                    </div>

                </div>
                <hr>
                <div class="form-group">
                    {!! Form::label('parametros', 'Parametros Generales:*') !!}
                    <div class="row">
                        <div class="col-sm-4">
                            {!! Form::label('incremento', 'Incremento salarial (%)') !!}
                            {!! Form::number('incremento', 0.00, ['class' => 'form-control', 'min' => '0.00', 'max' => '100', 'step' => '0.01', 'required']) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! Form::label('sueldominimo', 'Sueldo Mínimo') !!}
                            {!! Form::number('sueldominimo', 2362.50, ['class' => 'form-control', 'min' => '2362.50', 'step' => '0.01', 'required']) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! Form::label('rc_iva', 'RC IVA (%)') !!}
                            {!! Form::number('rc_iva', 0.13, ['class' => 'form-control', 'min'=> '0.13', 'max' => '100','step' => '0.01', 'required']) !!}
                        </div>
                    </div>
                     
                </div>
            </div>

            <div class="modal-footer" style="border-top: none">

            <div class="form-group col-sm-12" style="text-align: right">
                    {!! Form::submit('Generar', ['class' => 'btn btn-primary', 'onclick' => "return confirm('¿Está seguro de generar la planilla?')"]) !!}
                    </div>
                    {!! Form::close() !!}  
            </div>
        </div>

    </div>
</div>
<script>
$(document).ready(function () {
    $('input[type="checkbox"]').on('change', function () {
        if ($(this).prop('checked')) {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        }
    });
});
</script>