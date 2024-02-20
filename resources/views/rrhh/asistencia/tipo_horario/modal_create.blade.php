<div id="modalCreate" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tipo Horario</h4>
            </div>
            <div class="form-group col-sm-12">
                <form action="{{ route('tipo-horario.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-12">
                            {!! Form::label('descripcion', 'Descripcion:') !!}
                            {!! Form::text('descripcion', null, ['class' => 'form-control', 'required', 'maxlength' => '300','required']) !!}
                        </div>
                            <!-- hora inicio semana-->
                            <div class="form-group col-sm-3">
                                {!! Form::label('inicio_semana', 'Hora inicio semana : *') !!}
                                {!! Form::time('inicio_semana', null, ['class' => 'form-control', 'onchange' => 'validateFinSemana()']) !!}
                            </div>
                            <!-- hora fin semana-->
                            <div class="form-group col-sm-3">
                                {!! Form::label('fin_semana', 'Hora fin semana : *') !!}
                                {!! Form::time('fin_semana', null, ['class' => 'form-control', 'required', 'disabled', 'id' => 'fin_semana']) !!}
                            </div>
                            <!-- hora inicio sabado-->
                            <div class="form-group col-sm-3">
                                {!! Form::label('inicio_sabado', 'Hora inicio sabado : *') !!}
                                {!! Form::time('inicio_sabado', null, ['class' => 'form-control', 'onchange' => 'validateFinSabado()']) !!}
                            </div>
                        <!-- hora fin sabado-->
                            <div class="form-group col-sm-3">
                                {!! Form::label('fin_sabado', 'Hora fin sabado : *') !!}
                                {!! Form::time('fin_sabado', null, ['class' => 'form-control', 'required', 'disabled', 'id' => 'fin_sabado']) !!}
                            </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                </form>    
            </div>
            <div class="modal-footer" style="border-top: none"></div>
        </div>
    </div>
</div>

<script>
    function validateFinSemana() {
        var inicioSemana = document.getElementById('inicio_semana').value;
        var finSemanaField = document.getElementById('fin_semana');
        
        if (inicioSemana) {
            finSemanaField.removeAttribute('disabled');
            finSemanaField.setAttribute('required', 'required');
        } else {
            finSemanaField.setAttribute('disabled', 'disabled');
            finSemanaField.removeAttribute('required');
        }
    }
    function validateFinSabado() {
        var inicioSabado = document.getElementById('inicio_sabado').value;
        var finSabadoField = document.getElementById('fin_sabado');
        
        if (inicioSabado) {
            finSabadoField.removeAttribute('disabled');
            finSabadoField.setAttribute('required', 'required');
        } else {
            finSabadoField.setAttribute('disabled', 'disabled');
            finSabadoField.removeAttribute('required');
        }
    }
    
</script>