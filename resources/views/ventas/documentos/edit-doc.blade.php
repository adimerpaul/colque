<div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Documentos escaneados</h4>
            </div>
            @if(\App\Patrones\Permiso::esComercial())
                <div class="col-sm-12">
                    {!! Form::model($venta, ['route' => ['documentos-ventas.registrar', $venta->id],
                                'method' => 'patch', 'files' => 'true', 'id' => 'formDocumento']) !!}

                    <div class="form-group col-sm-4">
                        <strong>Elija los documentos que va a escanear:</strong>
                        <p style="margin-top: 10px">
                            <input id="url_documento" class="url_documento" type="file" name="url_documento[]"
                                   accept="application/pdf" multiple required/>
                        </p>

                    </div>

                    <div class="form-group col-sm-3">
                        <strong>Tipo:</strong>
                        {!! Form::select('descripcion', \App\Patrones\Fachada::getDocumentosVentas($venta->letra, $venta->sigla), isset($_GET['descripcion']) ? $_GET['descripcion'] : null, ['class' => 'form-control', 'required' ]) !!}
                    </div>
                    <div class="form-group col-sm-3">
                        <br>
                        <button type="submit" class="btn btn-success" id="btnGuardarDocumento">
                            Agregar documento
                        </button>
                    </div>
                    {!! Form::close() !!}

                </div>
            @endif
            <div class="col-sm-12">
                <hr>
                <div id="documento_escaneado"></div>
            </div>
        </div>

    </div>
</div>
