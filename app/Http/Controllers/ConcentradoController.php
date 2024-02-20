<?php

namespace App\Http\Controllers;

use App\Events\AccionCompletaVenta;
use App\Models\Concentrado;
use App\Models\Venta;
use App\Patrones\Fachada;
use App\Patrones\TipoConcentrado;
use App\Patrones\TipoLoteVenta;
use Illuminate\Http\Request;

class ConcentradoController extends Controller
{
    public function index(Request $request)
    {
        $pesoNetoSeco=0; $pesoFinoZn =0; $pesoFinoAg =0; $pesoFinoSn =0; $pesoFinoPb =0; $pesoFinoSb =0; $pesoFinoAu =0; $pesoFinoCu =0; $total=0;
        $concentrados = $this->getConcentrados($request);
        foreach ($concentrados as $row){
            $pesoNetoSeco = $pesoNetoSeco + $row->peso_neto_seco;
            $pesoFinoZn = $pesoFinoZn + $row->peso_fino_zn;
            $pesoFinoAg = $pesoFinoAg + $row->peso_fino_ag;
            $pesoFinoSn = $pesoFinoSn + $row->peso_fino_sn;
            $pesoFinoPb = $pesoFinoPb + $row->peso_fino_pb;
            $pesoFinoSb = $pesoFinoSb + $row->peso_fino_sb;
            $pesoFinoAu = $pesoFinoAu + $row->peso_fino_au;
            $pesoFinoCu = $pesoFinoCu + $row->peso_fino_cu;
            $total = $total + $row->total;
        }
        $leyZn=$concentrados->sum('ley_zn');
        $cotizacionZn=$concentrados->sum('cotizacion_zn');
        $leyAg=$concentrados->sum('ley_ag');
        $cotizacionAg=$concentrados->sum('cotizacion_ag');
        $leySn=$concentrados->sum('ley_sn');
        $cotizacionSn=$concentrados->sum('cotizacion_sn');
        $leyPb=$concentrados->sum('ley_pb');
        $cotizacionPb=$concentrados->sum('cotizacion_pb');
        $leySb=$concentrados->sum('ley_sb');
        $cotizacionSb=$concentrados->sum('cotizacion_sb');
        $leyAu=$concentrados->sum('ley_au');
        $cotizacionAu=$concentrados->sum('cotizacion_au');
        $leyCu=$concentrados->sum('ley_cu');
        $cotizacionCu=$concentrados->sum('cotizacion_cu');
        $valorTonelada=$concentrados->sum('valor_tonelada');

        return response()->json(['res' => true, 'concentrados' => $concentrados, 'pesoNetoSeco' => $pesoNetoSeco,
            'leyZn' => $leyZn, 'pesoFinoZn' => $pesoFinoZn, 'cotizacionZn' => $cotizacionZn,
            'leyAg' => $leyAg, 'pesoFinoAg' => $pesoFinoAg, 'cotizacionAg' => $cotizacionAg,
            'leySn' => $leySn, 'pesoFinoSn' => $pesoFinoSn, 'cotizacionSn' => $cotizacionSn,
            'leyPb' => $leyPb, 'pesoFinoPb' => $pesoFinoPb, 'cotizacionPb' => $cotizacionPb,
            'leySb' => $leySb, 'pesoFinoSb' => $pesoFinoSb, 'cotizacionSb' => $cotizacionSb,
            'leyAu' => $leyAu, 'pesoFinoAu' => $pesoFinoAu, 'cotizacionAu' => $cotizacionAu,
            'leyCu' => $leyCu, 'pesoFinoCu' => $pesoFinoCu, 'cotizacionCu' => $cotizacionCu,
            'valorTonelada' => $valorTonelada, 'total' => $total]);

    }
    public function getConcentrados($request){

        if($request->tipo==TipoConcentrado::Ingenio)
            return Concentrado::whereVentaId($request->venta_id)->whereTipoLote($request->tipo)->orderBy('id')->get();
        else
            return Concentrado::whereVentaId($request->venta_id)->whereIn('tipo_lote', [TipoConcentrado::Venta, TipoConcentrado::Sobrante])->orderBy('id')->get();

    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['fecha'] = date('Y-m-d');
        if($request->tipo_lote==TipoConcentrado::Sobrante){
            $venta=Venta::find($request->venta_id);
            $input['nombre'] = 'Sobrante ' . $venta->lote;

        }

        $concentrado = Concentrado::create($input);
        event(new AccionCompletaVenta("Concentrado creado", "Concentrado agregado", $concentrado->venta_id));

        return response()->json(['res' => true, 'concentrado' => $concentrado, 'message' => 'Concentrado registrado correctamente']);

    }

    public function actualizarMerma(Request $request)
    {
        $concentrado=Concentrado::find($request->id);
        if(!$concentrado->habilitado_ingenio)
            return response()->json(['res' => false,'message' => 'No se puede editar el registro']);

        Concentrado::whereId($request->id)->update(['merma_porcentaje' => $request->merma_porcentaje]);

        return response()->json(['res' => true,  'message' => 'Merma editada correctamente']);

    }

    public function enviarLote(Request $request)
    {
        $post=Concentrado::find($request->id);

        if($post->tipo_lote==TipoConcentrado::Sobrante){
            $post->update(['venta_id'=>$request->lote_destino, 'tipo_lote' => TipoConcentrado::Ingenio]);
            return response()->json(['res' => true,  'message' => 'Producto enviado correctamente']);
        }

        $destino = Concentrado::whereIngenioId($request->id)->count();
        if($destino>0)
            return response()->json(['res' => false,  'message' => 'El Producto ya fue enviado a un lote con anterioridad']);

        $newPost = $post->replicate();
        if($request->con_plomo){
            $newPost->ley_pb = $request->ley_pb;
            $newPost->cotizacion_pb = $request->cotizacion_pb;
        }

        if($request->con_zinc){
            $newPost->ley_zn = $request->ley_zn;
            $newPost->cotizacion_zn = $request->cotizacion_zn;
        }

        $newPost->created_at = date('Y-m-d H:i:s');
        $newPost->venta_id = $request->lote_destino;
        $newPost->tipo_lote = 'Ingenio';
        $newPost->ingenio_id = $post->id;
        $newPost->save();
        return response()->json(['res' => true,  'message' => 'Producto enviado correctamente']);
    }

    public function destroy($id)
    {
        $esIngenio=false;
        $concentrado = Concentrado::find($id);

        if (empty($concentrado)) {
            return response()->json(['res' => false, 'message' => 'Concentrado no encontrado']);
        }

        if(!$concentrado->habilitado_ingenio)
            return response()->json(['res' => false,'message' => 'No se puede eliminar el registro']);

        if($concentrado->tipo_lote==TipoLoteVenta::Ingenio)
            $esIngenio=true;
        $concentrado->delete($id);

        event(new AccionCompletaVenta("Concentrado eliminado", "Concentrado desagregado", $concentrado->venta_id));

        return response()->json(['res' => true, 'message' => 'Concentrado eliminado correctamente', 'esIngenio' => $esIngenio]);
    }

    public function update($id, Request $request)
    {
        $concentrado=Concentrado::find($id);
        if(!$concentrado->habilitado_ingenio)
            return response()->json(['res' => false,'message' => 'No se puede editar el registro']);

        $valor= $request->valor;
        $nombre = $request->nombre;

        if($nombre=='fecha')
            $valor=(Fachada::setFormatoFecha($request->valor));


        $concentrado=Concentrado::where('id', $id)->update([$nombre => $valor]);

        return response()->json(['res' => true, 'concentrado' => $concentrado, 'message' => 'Registro guardado correctamente']);

    }
}
