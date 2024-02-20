<div id="appDocumentosProductor">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Documentos escaneados</h4>
            </div>
            <div class="col-sm-12">
                {!! Form::model($cooperativa, ['route' => ['cooperativas.registrar-documento', $cooperativa->id],
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
                    {!! Form::select('descripcion', \App\Patrones\Fachada::getDocumentosCooperativas(), isset($_GET['descripcion']) ? $_GET['descripcion'] : null, ['class' => 'form-control', 'required' ]) !!}
                </div>
                @if((is_null($cooperativa->user_registro_id) or $cooperativa->user_registro_id == auth()->user()->id) and !$cooperativa->es_finalizado)

                <div class="form-group col-sm-2">
                    <br>

                    <button type="submit" class="btn btn-success" id="btnGuardarDocumento">
                        Agregar documento
                    </button>
                </div>
                {!! Form::close() !!}

                <div class="form-group col-sm-3" style="text-align: right">
                    <br>
                    {!! Form::open(['route' => ['eliminar-documento-cooperativa', $cooperativa->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        {!! Form::button('Eliminar documento', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                    </div>
                    {!! Form::close() !!}

                </div>
                @endif

            </div>
            <div class="col-sm-12">
                <hr>
                <div id="documento_escaneado"></div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        appDocumentosProductor = new Vue({
            el: "#appDocumentosProductor",
            data: {

            },
            mounted() {
                this.saveDocumento();
                this.showDocumento();
            },
            methods: {
                saveDocumento() {
                    $('#formDocumento').ajaxForm({
                        uploadProgress: function (event, position, total, percentComplete) {
                            $("#documento_escaneado").html('Cargando: ' + percentComplete + "% ...");
                        },
                        success: function () {
                            showDocumento();
                        },
                        complete: function (xhr) {
                            var res = xhr.responseJSON;
                            if (res.res) {
                                toastr.success("Registro modificado correctamente!");
                                setInterval('window.location.reload()', 4000);
                            } else {
                                alert(formarListaDeErrores(xhr.responseJSON));
                                alert('Por favor verifique que los archivos no esten corruptos');
                            }
                        },
                        error: function () {
                            toastr.error("Ha ocurrido un error, por favor verifique que los archivos no esten corruptos");
                        },
                        resetForm: true,
                    });
                },

                showDocumento() {
                    showDocumento();
                },

            }
        });

        function showDocumento() {
            let id = "{{ $cooperativa->id }}";
            axios.get("/cooperativas/" + id).then(response => {
                $("#documento_escaneado").html(response.data);
            });
        }


    </script>
@endpush
