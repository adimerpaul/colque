<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Events\AccionCompletaVenta;
use App\Models\Venta;
use App\Patrones\Accion;
use App\Models\DocumentoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Karriere\PdfMerge\PdfMerge;
use Illuminate\Support\Str;


class DocumentosVentaController extends Controller
{
    public function show($id)
    {
        $venta = Venta::findOrFail($id);

        if (empty($venta)) {
            Flash::error('Documento no encontrado');
        }
        return view('ventas.documentos.show_doc')->with('venta', $venta);
    }

    public function mostrar($id)
    {
        $venta = Venta::findOrFail($id);

        if (empty($venta)) {
            Flash::error('Documento no encontrado');
        }
        return view('documentos.mostrar_venta')->with('venta', $venta);
    }

    public function registrar(Request $request, $id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $res = $this->subirDocumento($request->url_documento, $venta);
            if ($res === "error")
                return $this->error_message("Elija documentos en formato pdf válidos");

            $venta->url_documento = $res;
            $venta->save();

            DocumentoVenta::where('venta_id', $id)->where('descripcion', $request->descripcion)
                ->update(['agregado' => true]);

            event(new AccionCompletaVenta("Documentos", "Carga de documentos escaneados de " . $request->descripcion, $id));

            return response()->json(['res' => true, 'venta' => $venta, 'message' => 'Documentos almacenados correctamente!']);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }

    }

    private function subirDocumento($files, $venta)
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
        if (!is_null($venta->url_documento)) {
            $file_url = public_path() . "/documents/ventas/" . $venta->url_documento;
            if (file_exists($file_url))
                $pdf->addPDF($file_url, 'all');
        }

        //juntando todos los documentos
        $nombreArchivo = $venta->id . '_documento' . '.pdf';
        $pdf->merge('file', public_path() . "/documents/ventas/" . $nombreArchivo);


        return $nombreArchivo;
    }


    public function adjuntarDocumento($venta)
    {
        $venta->url_documento = $venta->id . '_documento.pdf';
        $venta->save();

        $nombreArchivoForm= public_path() .'/documents/'.$venta->id.'.pdf';
        //unir varios pdf's en uno
        $pdf = new \PDFMerger;
        $pdf->addPDF($nombreArchivoForm, 'all');

        //adjuntando los documentos ya anteriormente registrados
      //  if (!is_null($venta->url_documento)) {
            $file_url = public_path() . "/documents/ventas/" . $venta->id . '_documento' . '.pdf';
            if (file_exists($file_url))
                $pdf->addPDF($file_url, 'all');
        //}

        //juntando todos los documentos
        $nombreArchivo = $venta->id . '_documento' . '.pdf';
        $pdf->merge('file', public_path() . "/documents/ventas/" . $nombreArchivo);
        File::move($nombreArchivoForm, public_path() .'/documents/'.$venta->id.'.pdf');

        File::delete($nombreArchivoForm);
        return $nombreArchivo;
    }
}
