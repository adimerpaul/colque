<div id="modalFactura" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Anular Factura Compra y Venta</h4>
            </div>
            <div class="modal-body">
                <div>

                    {!! Form::hidden('idVenta', null, ['class' => 'form-control', 'name'=>'cuf', 'id'=>'idVenta']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('codigoMotivo', 'Motivo Anulaciòn :*') !!}
                        {!! Form::select('codigoMotivo', \App\ Patrones\Fachada::getMotivosAnulacionImpuestos(), null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="asa">
                            ANULAR
                        </button>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>

        $(document).ready(function () {
            $("#divExportacion").hide();
            document.getElementById('codigoMotivo').removeAttribute('required', '');
        });
    </script>
@endpush
