<div id="modalImportar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Importar Archivo de Asistencia</h4>
            </div>
                <div class="form-group col-sm-12">
                    <form action="{{ route('asistencias.importar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {!! Form::label('doc_imp', 'Documento para importar:') !!}
                                    <input type="file" name="archivo" accept=".dat" class="form-control">
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {!! Form::label('importar', 'Importar:') !!}
                                    <button type="submit" class="fa fa-upload" title="Importar archivo" style="font-size: 24px; "></button>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: none"></div>
        </div>
    </div>
</div>