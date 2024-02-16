<div class="box box-warning">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Documentos escaneados </h4>
                <b v-if="formulario.documento_que_falta">Faltan Documentos: </b>@{{formulario.documento_que_falta}}
            </div>

            {{--    @if($formularioLiquidacion->es_escritura)--}}
            <div class="col-sm-12">
                <br><br>
                {!! Form::model($formularioLiquidacion, ['route' => ['registrar_documento', $formularioLiquidacion->id], 'method' => 'patch', 'files' => 'true', 'id' => 'formDocumento']) !!}
                <div class="form-group col-sm-4">
                    <strong>Elija los documentos que va a escanear:</strong>
                    <p>
                        <input id="url_documento" class="url_documento" type="file" name="url_documento[]"
                               accept="application/pdf" multiple required/>
                    </p>
                </div>
                <div class="form-group col-sm-3">
                    <strong>Tipo:</strong>
                    {!! Form::select('descripcion', \App\Patrones\Fachada::getDocumentosCompras(), isset($_GET['descripcion']) ? $_GET['descripcion'] : null, ['class' => 'form-control', 'required' ]) !!}
                </div>
                <div class="form-group col-sm-5">
                    <button type="submit" class="btn btn-success" id="btnGuardarDocumento" style="margin-top: 15px">
                        Agregar documentos
                    </button>

                    <button type="button" class="btn btn-danger" style="margin-top: 15px" @click="eliminarDocumentos()">
                        Eliminar documentos
                    </button>
                </div>
                {!! Form::close() !!}
            </div>

            {{--    @endif--}}

            <div class="col-sm-12">
                <hr>
                <div id="documento"></div>
            </div>
        </div>

    </div>
</div>
