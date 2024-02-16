<div>
    <div id="modalAnalisisAnimas" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Reporte de Analisis de Animas</strong></h4>
                </div>
                <div class="modal-body">



                    <div class="form-group col-sm-6">
                        {!! Form::label('lote1', 'Lote Plata 1: *') !!}
                        {!! Form::number('lote1',  null, ['class' => 'form-control', 'required', 'id' => 'loteUno' ]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('lote2', 'Lote Plata 2: *') !!}
                        {!! Form::number('lote2',  null, ['class' => 'form-control', 'required', 'id' => 'loteDos' ]) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="button" onclick="generarReporte()" class="btn btn-primary" id="botonGuardar">
                            Generar
                        </button>
                    </div>


                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function generarReporte(){
        let lote1 = document.getElementById('loteUno').value;
        let lote2 = document.getElementById('loteDos').value;
        window.open('/informe-animas/'+lote1+'/'+lote2 ,  '_blank')
    }

</script>




