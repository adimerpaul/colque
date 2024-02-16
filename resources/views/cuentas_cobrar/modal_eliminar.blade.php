<div id="modalCuenta" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Transferir cuenta por cobrar</h4>
            </div>
            <div class="modal-body">
                <div>
                    {!! Form::hidden('idCuenta', null, ['class' => 'form-control', 'name'=>'idCuenta', 'id'=>'idCuenta']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('tipo', 'Tipo: *') !!}

                        {!! Form::select('tipo', \App\ Patrones\Fachada::getTransferenciasCuentas(), null,
                            ['class' => 'form-control', 'required', 'id'=>'tipo', 'onchange' => 'cambiarTipo()']) !!}
                    </div>

                    <div class="form-group col-sm-12" id="loteDiv">
                        {!! Form::label('lote', 'Lote: *') !!}

                        {!! Form::select('destino', \App\ Patrones\Fachada::listarLotesActivos(), null,
                            ['class' => 'form-control', 'required', 'id'=>'destino']) !!}
                    </div>

                    <div class="form-group col-sm-12" id="clienteDiv">
                        {!! Form::label('cliente', 'Cliente: *') !!}
                        {!! Form::select('cliente_destino', \App\Patrones\Fachada::listarCLientes(), null, [
                            'class' => 'form-control select2', 'id'=>'cliente_destino']) !!}

                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" >
                            Aceptar
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>
<script type="application/javascript">
    $(document).ready(function () {
        $("#loteDiv").show();
        $("#clienteDiv").hide();
    });
    function cambiarTipo() {
        const lote = document.getElementById('destino');

        const cliente = document.getElementById('cliente_destino');
        if (document.getElementById("tipo").value == 'Lote') {
            $("#loteDiv").show();
            $("#clienteDiv").hide();
            cliente.removeAttribute('required', '');
            lote.setAttribute('required', '');
        } else {
            $("#loteDiv").hide();
            $("#clienteDiv").show();
            lote.removeAttribute('required', '');
            cliente.setAttribute('required', '');
        }
    }
</script>
