<div id="modalDetalle" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg"> <!-- Se añadió la clase modal-lg para hacer el modal más grande en pantallas grandes -->

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-size: 16px; display: inline-block;">Añadir permisos:</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    {!! Form::label('descripcion', 'Descripcion: *') !!}
                    {!! Form::text('descripcion', null, ['class' => 'form-control', 'maxlength' => '200', 'required' => true, 'style' => 'text-transform:uppercase;']) !!}
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('cantidad_dia', 'Cantidad en días:') !!}
                    {!! Form::input('number', 'cantidad_dia', 0.00, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'required' => true]) !!}
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('cantidad_hora', 'Cantidad en Minutos:') !!}
                    {!! Form::input('number', 'cantidad_hora', 0.00, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'required' => true]) !!}
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('fecha_inicio', 'Fecha Inicial:') !!}
                    {!! Form::text('fecha_inicio', null, ['class' => 'form-control', 'placeholder' => 'Formato: YYYY-mm-dd" o "mm-dd"', 'pattern' => '(?:\d{4}-\d{2}-\d{2}|^\d{2}-\d{2}$)']) !!}
                    <span id="fechaInicioMensaje" class="formato-mensaje"></span>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('fecha_final', 'Fecha Final:') !!}
                    {!! Form::text('fecha_final', null, ['class' => 'form-control', 'placeholder' => 'Formato: "YYYY-mm-dd" o "mm-dd"', 'pattern' => '(?:\d{4}-\d{2}-\d{2}|^\d{2}-\d{2}$)']) !!}
                    <span id="fechaFinalMensaje" class="formato-mensaje"></span>
                </div>
            </div>
                <div class="modal-footer" style="border-top: none">
                    <!-- Puedes agregar botones u otros elementos en el pie del modal si es necesario -->
                    <div class="form-group col-sm-12 text-right">
                    {!! Form::submit('Crear', ['class' => 'btn btn-primary']) !!}
                    </div>  
                </div>
        </div>
    </div>
</div>
