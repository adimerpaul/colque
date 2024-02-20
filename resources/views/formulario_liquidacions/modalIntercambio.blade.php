<div>
    <div id="modalIntercambio" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Intercambio de lotes</strong></h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'formularios.intercambiar']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('producto_letra', 'Producto: *') !!}
                        {!! Form::select('producto_letra', [null => 'Seleccione...'] +  \App\Models\Producto::get()->pluck('info', 'letra')->toArray(), null, ['class' => 'form-control', 'required', 'name' => 'producto_letra' ]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('lote1', 'Lote 1: *') !!}
                        {!! Form::select('lote1', [], null, ['class' => 'form-control', 'required', 'name' => 'lote1' ]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('lote2', 'Lote 2: *') !!}
                        {!! Form::select('lote2', [], null, ['class' => 'form-control', 'required', 'name' => 'lote2' ]) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Intercambiar', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
                <div class="modal-footer" style="border-top: none">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="producto_letra"]').on('change', function () {
            var productoLetra = $(this).val();
            if (productoLetra) {
                $.ajax({
                    url: '/get-lotes-activos/' + productoLetra,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="lote1"]').empty();
                        $('select[name="lote1"]').append('<option selected value="">Seleccione..</option>');
                        $('select[name="lote2"]').empty();
                        $('select[name="lote2"]').append('<option selected value="">Seleccione..</option>');
                        $.each(data, function (key, value) {
                            $('select[name="lote1"]').append('<option value="' + key + '">' + value + '</option>');
                            $('select[name="lote2"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('select[name="lote1"]').empty();
                $('select[name="lote2"]').empty();
            }
        });
    });

</script>




