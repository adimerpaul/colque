<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCooperativaRequest;
use App\Http\Requests\UpdateCooperativaRequest;
use App\Models\CampoReporte;
use App\Models\Contrato;
use App\Models\Cooperativa;
use App\Models\DescuentoBonificacion;
use App\Models\DocumentoCooperativa;
use App\Models\FormularioContabilidad;
use App\Models\FormularioKardex;
use App\Models\FormularioLiquidacion;
use App\Models\Laboratorio;
use App\Models\Material;
use App\Models\Producto;
use App\Patrones\ClaseDescuento;
use App\Patrones\Estado;
use App\Patrones\Fachada;
use App\Patrones\Rol;
use App\Patrones\TipoContrato;
use App\Patrones\TipoDescuentoBonificacion;
use App\Patrones\TipoPago;
use App\Patrones\TipoProductor;
use App\Repositories\CooperativaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Response;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CooperativaController extends AppBaseController
{
    /** @var  CooperativaRepository */
    private $cooperativaRepository;

    public function __construct(CooperativaRepository $cooperativaRepo)
    {
        $this->cooperativaRepository = $cooperativaRepo;
    }

    /**
     * Display a listing of the Cooperativa.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $cooperativas = Cooperativa::
            whereEsAprobado(true)
            ->where(function ($q) use ($txtBuscar) {
                $q->where('razon_social', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('nro_nim', 'ilike', '%' . $txtBuscar . '%');
            })
            ->orderBy('razon_social')->paginate(30);


        return view('cooperativas.index')
            ->with('cooperativas', $cooperativas);
    }

    /**
     * Show the form for creating a new Cooperativa.
     *
     * @return Response
     */
    public function create()
    {
        return view('cooperativas.create');
    }

    /**
     * Store a newly created Productor in storage.
     *
     * @param CreateCooperativaRequest $request
     *
     * @return Response
     */
    public function store(CreateCooperativaRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();

            $fecha = Fachada::setFormatoFecha($request->fecha_expiracion);
            $input['fecha_expiracion'] = $fecha;
            $input['user_registro_id'] = auth()->user()->id;

            $cooperativa = $this->cooperativaRepository->create($input);

            //registrano regalias, descuentos y bonificaciones
            $this->registrarDescuentoRegalia($cooperativa);

            $this->storeDocumentoCooperativa($cooperativa->id);


            DB::commit();
            Flash::success('Productor guardado correctamente.');


            echo "<script>
            window.location.href = '/cooperativas/'+'$cooperativa->id'+'/edit';
            window.open('/descuentosBonificaciones/lista/'+'$cooperativa->id', '_blank');

                </script>";


        } catch (Exception $e) {
            DB::rollBack();
            Flash::error('Ha ocurrido un error, revise los datos y vuelve a intentarlo.');
            return redirect(route('cooperativas.create'));
        }
    }

    private function storeDocumentoCooperativa($cooperativaId)
    {
        for ($i = 0; $i < count(Fachada::getTiposDocumentosCooperativas()); $i++) {
            $valor['descripcion'] = Fachada::getTiposDocumentosCooperativas()[$i];
            $valor['cooperativa_id'] = $cooperativaId;
            DocumentoCooperativa::create($valor);
        }

    }

    public function registrarDocumento(Request $request, $id)
    {
//        $files = $request->url_documento;
//        $contenido=(trim($files[0]->getContent()));
//
//        $contenido = str_replace('	', ', ', $contenido);
//        $contenido = explode (",", $contenido);
//
//        return ($contenido[0]);

        try {
            $cooperativa = Cooperativa::findOrFail($id);
            $res = $this->subirDocumento($request->url_documento, $cooperativa);
            if ($res === "error")
                return $this->error_message("Elija documentos en formato pdf válidos");

            $cooperativa->url_documento = $res;
            $cooperativa->user_registro_id= auth()->user()->id;
            $cooperativa->save();

            DocumentoCooperativa::where('cooperativa_id', $id)->where('descripcion', $request->descripcion)
                ->update(['agregado' => true]);

            return response()->json(['res' => true, 'cooperativa' => $cooperativa, 'message' => 'Documentos almacenados correctamente!']);
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }

    }

    private function subirDocumento($files, $cooperativa)
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
        if (!is_null($cooperativa->url_documento)) {
            $file_url = public_path() . "/documents/cooperativas/" . $cooperativa->url_documento;
            if (file_exists($file_url))
                $pdf->addPDF($file_url, 'all');
        }
        //juntando todos los documentos
        $nombreArchivo = $cooperativa->id . '_documento' . '.pdf';
        $pdf->merge('file', public_path() . "/documents/cooperativas/" . $nombreArchivo);

        return $nombreArchivo;
    }

    public function eliminarDocumento($id)
    {
        $cooperativa = Cooperativa::find($id);
        if (\File::exists(public_path('documents/cooperativas/' . $cooperativa->url_documento))) {
            \File::delete(public_path('documents/cooperativas/' . $cooperativa->url_documento));
            Cooperativa::where('id', $id)->update(['url_documento' => null]);
            DocumentoCooperativa::whereCooperativaId($id)->update(['agregado' => false]);
            Flash::success('Documento eliminado correctamente');
            return redirect()
                ->route('cooperativas.edit', ['cooperativa' => $cooperativa->id]);

        } else {
            Flash::error('Productor no encontrado');
            return redirect()
                ->route('cooperativas.edit', ['cooperativa' => $cooperativa->id]);
        }
    }

    private function registrarDescuentoRegalia($cooperativa)
    {
        $contrato=Contrato::first();
        if ($cooperativa->tipo == TipoProductor::Empresa) {
            DescuentoBonificacion::insert([
                ['nombre' => 'CAJA NACIONAL DE SALUD', 'valor' => '1.85', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => true],
                ['nombre' => 'BONO EQUIPAMIENTO', 'valor' => $contrato->bono_equipamiento, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                ['nombre' => 'BONO PRODUCTOR', 'valor' => $contrato->bono_productor, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                ['nombre' => 'BONO EPP', 'valor' => $contrato->bono_epp, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                ['nombre' => 'BONO CLIENTE', 'valor' => $contrato->bono_cliente, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
                ['nombre' => 'BONO CALIDAD', 'valor' => 20, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
            ]);
        }
        else{
            if ($cooperativa->tipo_contrato == TipoContrato::Administrativo) {
                DescuentoBonificacion::insert([
                   // ['nombre' => 'FENCOMIN', 'valor' => '0.40', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento', 'cooperativa_id' => $cooperativa->id, 'agregado_por_defecto' => true],
                    //['nombre' => 'FEDERACIÓN DEPARTAMENTAL O REGIONAL', 'valor' => '0.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento', 'cooperativa_id' => $cooperativa->id, 'agregado_por_defecto' => true],
                    ['nombre' => 'CAJA NACIONAL DE SALUD', 'valor' => '1.80', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO EQUIPAMIENTO', 'valor' => $contrato->bono_equipamiento, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO PRODUCTOR', 'valor' => $contrato->bono_productor, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO EPP', 'valor' => $contrato->bono_epp, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO CLIENTE', 'valor' => $contrato->bono_cliente, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
                    ['nombre' => 'BONO CALIDAD', 'valor' => 20, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
                ]);
            } else {
                DescuentoBonificacion::insert([
                    //['nombre' => 'FENCOMIN', 'valor' => '0.40', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento', 'cooperativa_id' => $cooperativa->id, 'agregado_por_defecto' => true],
                    //['nombre' => 'FEDERACIÓN DEPARTAMENTAL O REGIONAL', 'valor' => '0.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento', 'cooperativa_id' => $cooperativa->id, 'agregado_por_defecto' => true],
                    ['nombre' => 'CAJA NACIONAL DE SALUD', 'valor' => '1.80', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => true],
                    ['nombre' => 'COMIBOL', 'valor' => '1.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO EQUIPAMIENTO', 'valor' => $contrato->bono_equipamiento, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO PRODUCTOR', 'valor' => $contrato->bono_productor, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO EPP', 'valor' => $contrato->bono_epp, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::Acumulativo, 'agregado_por_defecto' => true],
                    ['nombre' => 'BONO CLIENTE', 'valor' => $contrato->bono_cliente, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
                    ['nombre' => 'BONO CALIDAD', 'valor' => 20, 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada, 'en_funcion' => \App\Patrones\EnFuncion::PesoNetoSeco, 'tipo' => 'Bonificacion', 'cooperativa_id' => $cooperativa->id, 'clase' => ClaseDescuento::EnLiquidacion, 'agregado_por_defecto' => false],
                ]);
            }
        }
    }

    /**
     * Show the form for editing the specified Cooperativa.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cooperativa = $this->cooperativaRepository->find($id);

        if (empty($cooperativa)) {
            Flash::error('Productor no encontrado');

            return redirect(route('cooperativas.index'));
        }

//        if(auth()->user()->rol== Rol::Comercial and   !is_null($cooperativa->es_finalizado)){
//            Flash::error('No tiene los permisos para realizar esta acción');
//
//            return redirect(route('cooperativas.index'));
//        }
//
//        if(auth()->user()->rol== Rol::Comercial and   (!is_null($cooperativa->user_registro_id) and $cooperativa->user_registro_id!== auth()->user()->id)){
//            Flash::error('No tiene los permisos para realizar esta acción');
//
//            return redirect(route('cooperativas.index'));
//        }
        $documentos = DocumentoCooperativa::whereCooperativaId($id)->get();
        $fecha = (date("Y-m-d", strtotime($documentos[0]->cooperativa->fecha_expiracion)));
        return view('cooperativas.edit')->with('cooperativa', $cooperativa)->with('documentos', $documentos)->with('fecha', $fecha);
    }

    public function show($id)
    {

        $cooperativa = Cooperativa::findOrFail($id);

        if (empty($cooperativa)) {
            Flash::error('Documento no encontrado');
        }
        return view('cooperativas.show_doc')->with('cooperativa', $cooperativa);
    }

    /**
     * Update the specified Productor in storage.
     *
     * @param int $id
     * @param UpdateCooperativaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCooperativaRequest $request)
    {
        $cooperativa = $this->cooperativaRepository->find($id);

        if (empty($cooperativa)) {
            Flash::error('Productor no encontrado');

            return redirect(route('cooperativas.index'));
        }

        $input = $request->all();
        $input['fecha_expiracion'] = Fachada::setFormatoFecha($request->fecha_expiracion);
        $input['user_registro_id'] = auth()->user()->id;
        $cooperativa = $this->cooperativaRepository->update($input, $id);

        Flash::success('Productor modificado correctamente.');

        return redirect(route('cooperativas.index'));

    }

    public function getByCliente(Request $request)
    {
        $filtro = '%' . $request->buscador . '%';
        $cooperativas = Cooperativa::join('cliente', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->where('cliente.nombre', 'ilike', $filtro)
            ->orWhere('cliente.nit', 'ilike', $filtro)
            ->select('cliente.nit', 'cliente.nombre', 'cooperativa.razon_social', 'cliente.celular')
            ->get();
        return $cooperativas;
    }

    public function kardex($idCooperativa, Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fechaFinal = $fechaFin;
        $tipo = $request->tipo;
        $productoLetra = $request->producto_id;
        $estado = $request->txtEstado;
        if (is_null($tipo))
            $tipo = 1;
        $campos = CampoReporte::whereTipoReporteId($tipo)->select('codigo', 'visible')->get();


        if ($fechaInicio == null or $fechaFin == null) {
            $formularios = FormularioLiquidacion::where('id', '>', '300000000')->get();
        } else {
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' + 1 days'));
            $formularios = FormularioLiquidacion::
            //where('es_cancelado', true)
            //->
            where([['fecha_liquidacion', '>=', $fechaInicio], ['fecha_liquidacion', '<', $fechaFin]])
                ->where(function ($q) use ($productoLetra) {
                    if(!is_null($productoLetra)){
                        if($productoLetra=='%'){
                            $q->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo']);
                        }
                        else{
                            $q->where('producto', 'like', "$productoLetra%");
                        }

                    }
                })
                ->where(function ($q) use ($estado) {
                    if($estado == 'comprado'){
                        $q->whereIn('estado',[Estado::Liquidado, Estado::Composito, Estado::Vendido]);
                    }
                    else {
                        $q->where('estado', 'ilike', "%{$estado}%");
                    }
                })
                //->where('estado', 'ilike', "%{$request->txtEstado}%")
                ->where('id', '<>', 561) // form pagado pero q se le devolvio
                ->where('id', '<>', 6420)
                //->where('cliente_id', '<>', 713) // sin don felix
                ->where('cliente_id', '<>', 1075) // sin daniel
                ->whereHas('cliente', function ($q) use ($idCooperativa) {
                    $q->WhereHas('cooperativa', function ($q) use ($idCooperativa) {
                        $q->where('id', $idCooperativa);
                    });
                })
                ->where(function ($q) use ($estado) {
                    if(($estado == Estado::Liquidado or $estado == Estado::Composito or $estado == Estado::Vendido or $estado == 'comprado')
                        and (auth()->user()->rol == Rol::Comercial or auth()->user()->id == 8)){
                        $q->where('regalia_minera', '>', '0.00');
                    }
                })
                ->orderBy('id')
                ->get();
        }

        $retenciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        $retencionesTotales = [];

        $i=0;
        foreach ($retenciones as $retencion) {
            $i = $i +1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }
        $nroRetenciones= $retenciones->count();

        $descuentos = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        $descuentosTotales = [];

        $j=0;
        foreach ($descuentos as $descuento) {
            $j = $j +1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos= $descuentos->count();

        $bonificaciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Bonificacion)
            ->where('clase', '<>', ClaseDescuento::Acumulativo)
            ->whereAlta(true)->get();
        $bonificacionesTotales = [];
        $k=0;
        foreach ($bonificaciones as $bonificacion) {
            $k = $k +1;
            ${"bonificacion" . $k} = 0;
            foreach ($formularios as $formulario) {
                ${"bonificacion" . $k} = ${"bonificacion" . $k} + $formulario->bonificaciones_cooperativa[$bonificacion->nombre];
            }
            $bonificacionesTotales += array($bonificacion->nombre => ${"bonificacion" . $k});
        }
        $nroBonificaciones= $bonificaciones->count();


        $bonificacionesAcumulativas = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Bonificacion)
            ->where('clase', ClaseDescuento::Acumulativo)
            ->whereAlta(true)->get();
        $bonificacionesAcumulativasTotales = [];
        $k=0;
        foreach ($bonificacionesAcumulativas as $bonificacion) {
            $k = $k +1;
            ${"bonificacion" . $k} = 0;
            foreach ($formularios as $formulario) {
                ${"bonificacion" . $k} = ${"bonificacion" . $k} + $formulario->bonificaciones_cooperativa[$bonificacion->nombre];
            }
            $bonificacionesAcumulativasTotales += array($bonificacion->nombre => ${"bonificacion" . $k});
        }
        $nroBonificacionesAcumulativas= $bonificacionesAcumulativas->count();

        $nroDescRet= $nroDescuentos + $nroRetenciones;

        $productor = Cooperativa::find($idCooperativa);
        return view('cooperativas.kardex', compact('formularios', 'idCooperativa', 'productor', 'fechaInicio', 'fechaFinal', 'productoLetra', 'campos', 'tipo',
            'retenciones', 'retencionesTotales', 'nroRetenciones', 'descuentos', 'descuentosTotales', 'nroDescuentos', 'nroDescRet',
            'bonificaciones', 'bonificacionesTotales', 'nroBonificaciones', 'bonificacionesAcumulativas', 'bonificacionesAcumulativasTotales', 'nroBonificacionesAcumulativas'));
    }

    public function getReporteRapido($idCooperativa, Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fechaFinal = $fechaFin;
        $productoLetra = $request->producto_id;
        $estado = $request->txtEstado;


        if ($fechaInicio == null or $fechaFin == null) {
            $formularios = FormularioLiquidacion::where('id', '>', '300000000')->get();
        } else {
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' + 1 days'));
            $formularios = FormularioKardex::
            //where('es_cancelado', true)
            //->
            where([['fecha_liquidacion', '>=', $fechaInicio], ['fecha_liquidacion', '<', $fechaFin]])
                ->where(function ($q) use ($productoLetra) {
                    if(!is_null($productoLetra)){
                        if($productoLetra=='%'){
                            $q->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo']);
                        }
                        else{
                            $q->where('producto', 'like', "$productoLetra%");
                        }

                    }
                })
                ->where(function ($q) use ($estado) {
                    if($estado == 'comprado'){
                        $q->whereIn('estado',[Estado::Liquidado, Estado::Composito, Estado::Vendido]);
                    }
                    else {
                        $q->where('estado', 'ilike', "%{$estado}%");
                    }
                })
                //->where('estado', 'ilike', "%{$request->txtEstado}%")
                ->where('id', '<>', 561) // form pagado pero q se le devolvio
                //->where('cliente_id', '<>', 713) // sin don felix
                ->whereHas('cliente', function ($q) use ($idCooperativa) {
                    $q->WhereHas('cooperativa', function ($q) use ($idCooperativa) {
                        $q->where('id', $idCooperativa);
                    });
                })
                ->where(function ($q) use ($estado) {
                    if(($estado == Estado::Liquidado or $estado == Estado::Composito or $estado == Estado::Vendido or $estado == 'comprado')
                        and (auth()->user()->rol == Rol::Comercial or auth()->user()->id == 8)){
                        $q->where('regalia_minera', '>', '0.00');
                    }
                })
                ->orderBy('id')
                ->get();
        }

        $retenciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        $retencionesTotales = [];

        $i=0;
        foreach ($retenciones as $retencion) {
            $i = $i +1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }
        $nroRetenciones= $retenciones->count();

        $descuentos = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        $descuentosTotales = [];

        $j=0;
        foreach ($descuentos as $descuento) {
            $j = $j +1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos= $descuentos->count();


        $nroDescRet= $nroDescuentos + $nroRetenciones;

        $productor = Cooperativa::find($idCooperativa);
        return view('cooperativas.reporte_rapido.index', compact('formularios', 'idCooperativa', 'productor', 'fechaInicio', 'fechaFinal', 'productoLetra',
            'retenciones', 'retencionesTotales', 'nroRetenciones', 'descuentos', 'descuentosTotales', 'nroDescuentos', 'nroDescRet'));
    }


    public function getReporteContabilidad($idCooperativa, Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fechaFinal = $fechaFin;
        $tipo = $request->tipo;
        $productoLetra = $request->producto_id;
        if (is_null($tipo))
            $tipo = 1;


        if ($fechaInicio == null or $fechaFin == null) {
            $formularios = FormularioContabilidad::where('id', '>', '300000000')->get();
        } else {
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' + 1 days'));
            $formularios = FormularioContabilidad::
            //where('es_cancelado', true)
            //->
            where([['fecha_liquidacion', '>=', $fechaInicio], ['fecha_liquidacion', '<', $fechaFin]])
                ->where(function ($q) use ($productoLetra) {
                    if(!is_null($productoLetra)){
                        if($productoLetra=='%'){
                            $q->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo']);
                        }
                        else{
                            $q->where('producto', 'like', "$productoLetra%");
                        }

                    }
                })
                ->whereIn('estado',[Estado::Liquidado, Estado::Composito, Estado::Vendido])
                ->where('id', '<>', 561) // form pagado pero q se le devolvio
                ->whereHas('cliente', function ($q) use ($idCooperativa) {
                    $q->WhereHas('cooperativa', function ($q) use ($idCooperativa) {
                        $q->where('id', $idCooperativa);
                    });
                })
              //  ->where('regalia_minera', '>', '0.00')
                ->orderBy('id')
                ->get();
        }

        $retenciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        $retencionesTotales = [];

        $i=0;
        foreach ($retenciones as $retencion) {
            $i = $i +1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }
        $nroRetenciones= $retenciones->count();

        $descuentos = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        $descuentosTotales = [];

        $j=0;
        foreach ($descuentos as $descuento) {
            $j = $j +1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos= $descuentos->count();

        $bonificaciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Bonificacion)->whereAlta(true)->get();
        $bonificacionesTotales = [];
        $k=0;
        foreach ($bonificaciones as $bonificacion) {
            $k = $k +1;
            ${"bonificacion" . $k} = 0;
            foreach ($formularios as $formulario) {
                ${"bonificacion" . $k} = ${"bonificacion" . $k} + $formulario->bonificaciones_cooperativa[$bonificacion->nombre];
            }
            $bonificacionesTotales += array($bonificacion->nombre => ${"bonificacion" . $k});
        }
        $nroBonificaciones= $bonificaciones->count();


        $nroDescRet= $nroDescuentos + $nroRetenciones;

        $productor = Cooperativa::find($idCooperativa);
        return view('cooperativas.reporte_contabilidad.index', compact('formularios', 'idCooperativa', 'productor', 'fechaInicio', 'fechaFinal', 'productoLetra', 'tipo',
            'retenciones', 'retencionesTotales', 'nroRetenciones', 'descuentos', 'descuentosTotales', 'nroDescuentos', 'nroDescRet',
            'bonificaciones', 'bonificacionesTotales', 'nroBonificaciones'));
    }

    public function mostrarDocumento($id)
    {
        $cooperativa = Cooperativa::findOrFail($id);

        if (empty($cooperativa)) {
            Flash::error('Documento no encontrado');
        }
        return view('cooperativas.mostrar_documento')->with('cooperativa', $cooperativa);
    }
    public function getResumenPdf($idCooperativa, Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fechaFinal = $fechaFin;
        $tipo = $request->tipo;
        $productoLetra = $request->producto_id;
        if (is_null($tipo))
            $tipo = 1;


        if ($fechaInicio == null or $fechaFin == null) {
            $formularios = FormularioContabilidad::where('id', '>', '300000000')->get();
        } else {
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' + 1 days'));
            $formularios = FormularioContabilidad::
            //where('es_cancelado', true)
            //->
            where([['fecha_liquidacion', '>=', $fechaInicio], ['fecha_liquidacion', '<', $fechaFin]])
                ->where(function ($q) use ($productoLetra) {
                    if(!is_null($productoLetra)){
                        if($productoLetra=='%'){
                            $q->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo']);
                        }
                        else{
                            $q->where('producto', 'like', "$productoLetra%");
                        }

                    }
                })
                ->whereIn('estado',[ Estado::Liquidado, Estado::Composito, Estado::Vendido])
                ->where('id', '<>', 561) // form pagado pero q se le devolvio
                ->whereHas('cliente', function ($q) use ($idCooperativa) {
                    $q->WhereHas('cooperativa', function ($q) use ($idCooperativa) {
                        $q->where('id', $idCooperativa);
                    });
                })
           //     ->where('regalia_minera', '>', '0.00')
                ->orderBy('id')
                ->get();
        }

        $retenciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)
            ->where('tipo', TipoDescuentoBonificacion::Retencion)->whereAlta(true)->get();
        $retencionesTotales = [];

        $i=0;
        foreach ($retenciones as $retencion) {
            $i = $i +1;
            ${"retencion" . $i} = 0;
            foreach ($formularios as $formulario) {
                ${"retencion" . $i} = ${"retencion" . $i} + $formulario->retenciones_cooperativa[$retencion->nombre];
            }
            $retencionesTotales += array($retencion->nombre => ${"retencion" . $i});
        }

        $nroRetenciones= $retenciones->count();

        $descuentos = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Descuento)->whereAlta(true)->get();
        $descuentosTotales = [];

        $j=0;
        foreach ($descuentos as $descuento) {
            $j = $j +1;
            ${"descuento" . $j} = 0;
            foreach ($formularios as $formulario) {
                ${"descuento" . $j} = ${"descuento" . $j} + $formulario->descuentos_cooperativa[$descuento->nombre];
            }
            $descuentosTotales += array($descuento->nombre => ${"descuento" . $j});
        }
        $nroDescuentos= $descuentos->count();

        $bonificaciones = DescuentoBonificacion::whereCooperativaId($idCooperativa)->where('tipo', TipoDescuentoBonificacion::Bonificacion)->whereAlta(true)->get();
        $bonificacionesTotales = [];
        $k=0;
        foreach ($bonificaciones as $bonificacion) {
            $k = $k +1;
            ${"bonificacion" . $k} = 0;
            foreach ($formularios as $formulario) {
                ${"bonificacion" . $k} = ${"bonificacion" . $k} + $formulario->bonificaciones_cooperativa[$bonificacion->nombre];
            }
            $bonificacionesTotales += array($bonificacion->nombre => ${"bonificacion" . $k});
        }
        $nroBonificaciones= $bonificaciones->count();


        $nroDescRet= $nroDescuentos + $nroRetenciones;
        $productor = Cooperativa::find($idCooperativa);


        $bnb=0;
        $efectivo=0;
        $economico=0;

        foreach ($formularios as $f){
            if($f->tipo_pago==TipoPago::Efectivo){
                $efectivo = $efectivo + $f->saldo_favor;
            }
            else if( str_contains($f->tipo_pago, 'BNB')){
                $bnb = $bnb + $f->saldo_favor;
            }
            else if( str_contains($f->tipo_pago, 'Economico'))
                $economico = $economico + $f->saldo_favor;
        }

        $totalNetoVenta = $formularios->sum('neto_venta');
        $totalSaldoDeuda= $formularios->where('saldo_favor', '<', 0.00)->sum('saldo_favor')*-1;
        $totalRegalia = $formularios->sum('regalia_minera');
        $totalAnticipos = $formularios->sum('total_anticipo');
        $totalPrestamos = $formularios->sum('cuentas_prestamo');
        $totalSaldoNegativo = $formularios->sum('cuentas_saldo_negativo');
        $totalRetiros = $formularios->sum('cuentas_retiro');
        $totalAporteFundacion = $formularios->sum('aporte_fundacion');
        $totalDevolucionAnticipo = $formularios->sum('devolucion_anticipo');
        $totalDevolucionLaboratorio = $formularios->sum('devolucion_laboratorio');
        $totalBonificaciones = $formularios->sum('total_bonificacion');
        $totalRetencionesDescuentos = $formularios->sum('total_retencion_descuento');
        $totalDebe= $totalNetoVenta + $totalBonificaciones +$totalSaldoDeuda;
        $totalHaber= $totalAnticipos + $totalPrestamos + $totalSaldoNegativo + $totalRetiros + $totalAporteFundacion +
                    $totalDevolucionAnticipo + $totalDevolucionLaboratorio  + $economico + $bnb + $efectivo + $totalRetencionesDescuentos;

        $diferenciaHaber=0.00;
        $diferenciaDebe=0.00;
        $diferencia = round(($totalHaber - $totalDebe), 2);
        if($diferencia<0.00){
            $diferenciaHaber= abs($diferencia);
        }
        else if($diferencia>0.00){
            $diferenciaDebe= abs($diferencia);
        }
        $totalDebe = $totalDebe + $diferenciaDebe;
        $totalHaber = $totalHaber + $diferenciaHaber;
        $vistaurl = "cooperativas.reporte_contabilidad.resumen_pdf";
        $view = \View::make($vistaurl, compact('formularios', 'idCooperativa', 'productor', 'fechaInicio', 'fechaFinal', 'productoLetra', 'tipo',
            'retenciones', 'retencionesTotales', 'nroRetenciones', 'descuentos', 'descuentosTotales', 'nroDescuentos', 'nroDescRet',
            'bonificaciones', 'bonificacionesTotales', 'nroBonificaciones', 'efectivo', 'bnb', 'economico',
            'totalNetoVenta', 'totalSaldoDeuda', 'totalRegalia', 'totalAnticipos', 'totalPrestamos', 'totalSaldoNegativo', 'totalRetiros',
             'totalAporteFundacion', 'totalDevolucionAnticipo', 'totalDevolucionLaboratorio', 'totalDebe', 'totalHaber', 'diferenciaDebe', 'diferenciaHaber'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $pdf->getDomPDF();

        return $pdf->stream('resumen.pdf');
    }

    public function imprimirInformeLaboratorio($productorId, $inicio, $fin, $mineralId)
    {
        $laboratorios = Laboratorio::
            where(function ($q) use($mineralId){
                if($mineralId==0){
                    $q->whereNull('mineral_id');
                }
                else{
                    $q->where('mineral_id', $mineralId);
                }
            })
            ->whereOrigen('Empresa')
            ->whereHas('formularioLiquidacion', function ($q) use($productorId, $inicio, $fin){
                $q->where('fecha_liquidacion', '>=', $inicio)->where('fecha_liquidacion', '<=', $fin)
                    ->where('estado','<>', 'Anulado')
                    ->whereHas('cliente', function ($q) use($productorId){
                        $q->where('cooperativa_id', $productorId );
                    });
            })
            ->orderBy('formulario_liquidacion_id')
            ->get();

        $cooperativa = Cooperativa::find($productorId);
        $elemento = 'Humedad';
        $mineral = Material::find($mineralId);
        if(!empty($mineral))
            $elemento = $mineral->nombre;
        //generador qr
        $urlQR = url("/imprimir-informe-laboratorio/$productorId/$inicio/$fin/$mineralId");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));


        $vistaurl = "cooperativas.informe_laboratorio";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('laboratorios', 'qrcode', 'inicio', 'fin', 'cooperativa', 'elemento'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);
        $pdf= \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $fechaImpresion = date('d/m/Y H:i');
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));

        return $pdf->stream('InformeEnsayoAnimas.pdf');
    }

    public function finalizar($id) {
        $cooperativa = Cooperativa::find($id);

        if (empty($cooperativa)) {
            Flash::error('Productor no encontrado');

            return redirect(route('cooperativas.index'));
        }

        $cooperativa->update(['es_finalizado'=>true]);

        Flash::success('Productor finalizado correctamente.');

        return redirect(route('cooperativas.index'));
    }

    public function getFinalizados(Request $request){

//        $txtBuscar = $request->txtBuscar;
//        if (is_null($txtBuscar))
//            $txtBuscar = '';
//            $parametro = $request->parametro;
        $cooperativas =
            \DB::select("
                select  c1.nit, c1.nro_nim, razon_social, c1.user_registro_id, c1.es_aprobado, c1.updated_at, personal.nombre_completo, c1.id
                 FROM
                cooperativa as c1
                inner join cliente  ON c1.id = cliente.cooperativa_id
                inner join formulario_liquidacion ON c1.id = formulario_liquidacion.cliente_id
                inner join users  ON c1.user_registro_id = users.id
                inner join personal  ON users.personal_id= personal.id
                where c1.es_finalizado=true and c1.es_aprobado=true and
                ((select count (*) from documento_cooperativa as d1 where d1.cooperativa_id= c1.id and agregado=false)=0)
                group by c1.nit, c1.id, c1.nro_nim, razon_social, c1.user_registro_id, c1.es_aprobado, c1.updated_at, personal.nombre_completo

                order by c1.updated_at desc
                                ");
        $cooperativas= $this->arrayPaginator($cooperativas, $request);
        return view('cooperativas.lista_finalizados')
            ->with('cooperativas', $cooperativas);

    }
    public function arrayPaginator($array, $request)
    {
        $page = $request->page;
        $perPage = 1;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, false), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
