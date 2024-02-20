<div class="form-group col-sm-6">
    {!! Form::label('departamento', 'Departamento: *') !!}
    {!! Form::select('departamento', \App\Patrones\Fachada::listarDepartamentos(), isset($cooperativa->municipio) ? $cooperativa->municipio->provincia->departamento_id : null, ['class' => 'form-control select2', 'name'=>'departamento', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('municipio', 'Municipio: *') !!}
    {!! Form::select('municipio_id', isset($cooperativa->municipio_id)? \App\Patrones\Fachada::listarMunicipios($cooperativa->municipio->provincia->departamento_id):[],
        isset($cooperativa->municipio_id) ? $cooperativa->municipio_id : null, ['class' => 'form-control select2', 'name'=>'municipio_id', 'required']) !!}
</div>

<!-- Razon Social Field -->
<div class="form-group col-sm-6">
    {!! Form::label('razon_social', 'Razón Social / Denominación: *') !!}
    {!! Form::text('razon_social', null, ['class' => 'form-control', 'maxlength' => '100', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('nit', 'NIT: *') !!}
    {!! Form::number('nit', null, ['class' => 'form-control', 'maxlength' => '20', 'required']) !!}
</div>

<!-- Nim Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nro_nim', 'NIM: *') !!}
    {!! Form::text('nro_nim', null, ['class' => 'form-control', 'maxlength' => '20', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('fecha_expiracion', 'Fecha expiración NIM: *') !!}
    {!! Form::text('fecha_expiracion', isset($cooperativa) ? date('d/m/Y', strtotime($cooperativa->fecha_expiracion)) : date('d/m/Y', strtotime(date('Y-m-d'). ' + 1 years')), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('tipo', 'Tipo: *') !!}
    {!! Form::select('tipo', \App\Patrones\Fachada::getTiposProductores(), isset($cooperativa) ? $cooperativa : null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('tipoContrato', 'Tipo Contrato: *') !!}
    {!! Form::select('tipo_contrato', \App\Patrones\Fachada::getTiposContratos(), isset($cooperativa) ? $cooperativa->tipo_contrato : null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(auth()->user()->rol=='Comercial' and str_contains(url()->current(), 'edit'))
        @if((is_null($cooperativa->user_registro_id) or $cooperativa->user_registro_id == auth()->user()->id) and !$cooperativa->es_finalizado)

            {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id' => 'botonGuardar']) !!}


        @endif
    @else
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id' => 'botonGuardar']) !!}
    @endif

    <a href="{{ route('cooperativas.index') }}" class="btn btn-default">Cancelar</a>
</div>

<script type="text/javascript">
    $("#formularioGuardar").on("submit", function () {
        $("#botonGuardar").prop("disabled", true);
    });

    $(document).ready(function () {
        $('select[name="departamento"]').on('change', function () {
            var departamentoId = $(this).val();
            if (departamentoId) {
                $.ajax({
                    url: '/get_municipios/' + departamentoId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="municipio_id"]').empty();
                        $('select[name="municipio_id"]').append('<option selected value="">Seleccione..</option>');
                        $.each(data, function (key, value) {
                            $('select[name="municipio_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('select[name="municipio_id"]').empty();
            }
        });
    });

</script>
