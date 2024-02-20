<div>
    <div id="modalAnalisisLaboratorio" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Reporte de Analisis de Laboratorio</strong></h4>
                </div>
                <div class="modal-body">

                    {!! Form::hidden('idCooperativa',  null, ['class' => 'form-control','id' => 'idCooperativa' ]) !!}


                    <div class="form-group col-sm-6">
                        {!! Form::label('inicio', 'Fecha Liq. Inicio: *') !!}
                        {!! Form::date('inicio',  null, ['class' => 'form-control', 'required', 'id' => 'inicio' ]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('fin', 'Fecha Liq. Fin: *') !!}
                        {!! Form::date('fin',  null, ['class' => 'form-control', 'required', 'id' => 'fin' ]) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('elemento', 'Elemento: *') !!}
                        {!! Form::select('elemento', [0 => 'Humedad'] +  \App\Models\Material::whereConCotizacion(true)->orderBy('nombre')->get()->pluck('nombre', 'id')->toArray(), null, ['class' => 'form-control', 'required', 'elemento']) !!}
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
        let inicio = document.getElementById('inicio').value;
        let fin = document.getElementById('fin').value;
        let productorId = document.getElementById('idCooperativa').value;
        let elemento = document.getElementById('elemento').value;

        window.open('/imprimir-informe-laboratorio/'+productorId+'/'+inicio+'/'+fin +'/'+elemento ,  '_blank')
    }

</script>




