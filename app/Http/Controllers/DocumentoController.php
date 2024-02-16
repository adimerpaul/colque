<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\LaboratorioEnsayo;
use App\Patrones\Accion;
use App\Patrones\Fachada;
use Illuminate\Http\Request;
use Karriere\PdfMerge\PdfMerge;
use Illuminate\Support\Str;
use Response;
use File;
use ZipArchive;

class DocumentoController extends Controller
{
    public function show($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);

        if (empty($formularioLiquidacion)) {
            Flash::error('Formulario no encontrado');
        }

        return view('documentos.show')->with('formularioLiquidacion', $formularioLiquidacion);
    }

    public function mostrar($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);

        if (empty($formularioLiquidacion)) {
            Flash::error('Formulario no encontrado');
        }

        return view('documentos.mostrar')->with('formularioLiquidacion', $formularioLiquidacion);
    }

    public function descargarDocumentos($id)
    {
        $formularioLiquidacions = FormularioLiquidacion::orderByDesc('id')->take(10)->get();
        $seleccionados = '';

//        for ($i = 0; $i < $formularioLiquidacions->count(); $i++) {
//            $seleccionados=$seleccionados .', '.public_path('documents/'.$formularioLiquidacions[$i]->id.'_document.pdf');
//            //array_unshift($seleccionados, public_path('documents/'.$formularioLiquidacions[$i]->id.'_document.pdf'));
////            $filepath = public_path('documents/'.$f->id.'_document.pdf');
////            return Response::download($filepath);
//
//
//        }
//                   Zipper::make('mydir/mytest12.zip')->add($seleccionados);
//    return response()->download(public_path('mydir/mytest12.zip'));
//        dd($seleccionados);

        return Response::download(
//            [
            public_path('documents/1461_document.pdf')
//            ]
        );
        // return response()->download([public_path('documents/'.$formularioLiquidacions[0]->id.'_document.pdf')]);
    }

    private function descargar($filepath)
    {
        $filepath1 = public_path($filepath);
        return Response::download($filepath1);
    }

    public function registrarDocumento(Request $request, $id)
    {
        try {
            $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);
            $res = $this->subirDocumento($request->url_documento, $formularioLiquidacion);

            if ($res === "error")
                return $this->error_message("Elija documentos en formato pdf válidos");

            $formularioLiquidacion->url_documento = $res;
            $formularioLiquidacion->save();

            DocumentoCompra::where('formulario_liquidacion_id', $id)->where('descripcion', $request->descripcion)
                ->update(['agregado' => true]);
            event(new AccionCompleta("Documentos", "Carga de documentos escaneados: " . $request->descripcion, $formularioLiquidacion->id));

            return response()->json(['res' => true, 'formularioLiquidacion' => $formularioLiquidacion, 'message' => 'Documentos almacenados correctamente!']);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }

    }

    private function subirDocumento($files, $formularioLiquidacion)
    {
        if (is_null($files)) {
            return "error";
        }

        //unir varios pdf's en uno
        $pdf = new \PDFMerger;
        foreach ($files as $key => $file) {
            $pdf->addPDF($file->getPathName(), 'all');
        }

        //adjuntando los documentos ya anteriormente registrados
        if (!is_null($formularioLiquidacion->url_documento)) {
            $file_url = public_path() . "/documents/" . $formularioLiquidacion->url_documento;
            if (file_exists($file_url))
                $pdf->addPDF($file_url, 'all');
        }

        //juntando todos los documentos
        $nombreArchivo = $formularioLiquidacion->id . '_document' . '.pdf';
        $pdf->merge('file', public_path() . "/documents/" . $nombreArchivo);


        return $nombreArchivo;
    }

    public function storeDocumentosCompras($formularioId)
    {
        for ($i = 0; $i < count(Fachada::getTiposDocumentosCompras()); $i++) {
            $valor['descripcion'] = Fachada::getTiposDocumentosCompras()[$i];
            $valor['formulario_liquidacion_id'] = $formularioId;
            DocumentoCompra::create($valor);
        }
    }

    public function eliminarDocumento($id)
    {
        \DB::beginTransaction();
        try {
            $form = FormularioLiquidacion::find($id);
            if (\File::exists(public_path('documents/' . $form->url_documento))) {
                \File::delete(public_path('documents/' . $form->url_documento));
                DocumentoCompra::whereFormularioLiquidacionId($id)->update(['agregado' => false]);
                event(new AccionCompleta("Documentos", "Eliminación de documentos escaneados", $id));


                $objDocumento = new DocumentoController();
                $objDocumento->storeDocumentosCompras($id);

                ///////////adjuntar boleta de pesaje a documentos
                $objRep = new ReporteController();
                $objCaja = new CajaController();
                $objRep->generarBoletaPesaje($id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);
                $res = $objCaja->subirDocumento($formularioLiquidacion);
                $formularioLiquidacion->url_documento = $res;
                $formularioLiquidacion->save();
                DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::BoletaPesaje)
                    ->update(['agregado' => true]);
                ///////////
                ///////////adjuntar boleta de anticipo a documentos
                $ensayo = LaboratorioEnsayo::whereFormularioLiquidacionId($id)->whereEsFinalizado(true)->get();
                if($ensayo->count()>0){
                    $objLaboratorio = new LaboratorioEnsayoController();
                    $objCaja = new CajaController();
                    $objLaboratorio->generarBoletaEnsayo($ensayo[0]->id);
                    $objCaja->subirDocumento($form);
                }
                ///
                /// \DB::commit();
                return response()->json(['res' => true, 'message' => 'Documentos eliminados correctamente!']);


            } else {
                return response()->json(['res' => false, 'message' => 'Documento no encontado']);

            }

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }
}
