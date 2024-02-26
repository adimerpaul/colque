<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataXML\DataXML;
use App\Http\Controllers\DataXML\DataXMLComercialExportacionMinera;
use App\Http\Controllers\DataXML\DataXMLConsumoAgua;
use App\Http\Controllers\DataXML\DataXMLLey;
use App\Http\Controllers\DataXML\DataXMLLibreConsignacion;
use App\Http\Controllers\Impuestos\CodigoImpuestos;
use App\Http\Controllers\Impuestos\ServicioDocumentoAjusteController;
use App\Http\Controllers\Impuestos\ServicioFacturacionController;
use App\Http\Controllers\PDF\GeneratePDF;
use App\Http\Controllers\XmlSource\XMLDocumentoAjuste;
use App\Http\Controllers\XmlSource\XMLExportacionMineral;
use App\Http\Controllers\XmlSource\XMLLibreConsignacion;
use App\Http\Controllers\XmlSource\XMLServicioBasico;
use App\Mail\AnulacionMailable;
use App\Models\Comprador;
use App\Models\Cui;
use App\Models\FacturasImpuestos;
use App\Models\Venta;
use App\Patrones\ActividadEconomica;
use App\Patrones\CodigoEmision;
use App\Patrones\DocumentoSector;
use App\Patrones\Env;
use App\Patrones\Fachada;
use App\Patrones\TipoFactura;
use App\Patrones\TipoPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ExportacionMineralController extends Controller
{
    use CodigoImpuestos, DataXML;

    private $codigoPuntoventa = 0;
    private $codigoDocumentoSector = 20;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getExportacionMineral()
    {
        return json_encode([
            "success" => true,
            "mes" => date("m"),
            "gestion" => 2023,
            "nombreRazonSocial" => "TRAFIGURA PTE LTD",
            "numeroDocumento" => 7335394012,
            "domicilioCliente" => "OCEAN FINANCIAL CENTRE 65-63192960, 29-00, LIANYUNGANG, CHINA, 10 COLLYER QUAY",
            "montoTotal" => 50,
        ]);
    }

    public function index()
    {
        $compraVenta = FacturasImpuestos::where('tipo_factura', 'ExportacionMineral')->orderByDesc('id')->paginate();

        return view('facturas_exportacion.index')
            ->with('facturas', $compraVenta);
        return response()->json(['success' => true, "exportacionMineral" => $compraVenta]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function emitirExportacionMineral($request)
    {
        //return $request;
//        return Auth::user()->id;

//        $request =  [
//            "id" => 200,
//            "sigla" => "CMV",
//            "numero_lote" => 7,
//            "mes" => "06",
//            "letra" => "A",
//            "anio" => 2023,
//            "producto" => "A | Zinc Plata",
//            "comprador_id" => 12,
//            "monto" => null,
//            "monto_final" => null,
//            "monto_total" => "50",
//            "tipo_factura" => "Exportacion Minera",
//            "lote" => "CMV7A\/23",
//            "nit_emisor" => "370883022",
//            "razon_social_emisor" => "COLQUECHACA MINING LTDA.",
//            "telefono" => "67200160",
//            "direccion" => "AVENIDA MARIA BARZOLA ENTRE CORIHUAYRA Y CELESTINO GUTIERREZ NRO. S\/N ZONA\/BARRIO => ESTE",
//            "concentrado_granel" => "Zinc Plata",
//            "tipo_cambio" => 6.96,
//            "descuento_adicional" => 0,
//            "usuario" => "daoc",
//            "nombre_razon_social" => "TRAFIGURA PTE LTD",
//            "nit" => "7335394",
//            "direccion_comprador" => "OCEAN FINANCIAL CENTRE 65-63192960, 29-00, LIANYUNGANG, CHINA, 10  COLLYER QUAY",
//            "ruex" => "lol123",
//            "nim" => "lol123",
//            "origen" => "oruro",
//            "numero_documento" => "7335394012",
//            "codigo_cliente" => "990011",
//            "puerto_destino" => "JIUJIANG - CHINA",
//            "puerto_transito" => "ARICA - CHILE",
//            "pais_destino" => "CHINA",
//            "incoterm" => "FOB",
//            "kilos_netos_humedos" => 10,
//            "humedad_porcentaje" => 10,
//            "humedad_valor" => 10,
//            "merma_porcentaje" => 10,
//            "merma_valor" => 10,
//            "kilos_netos_secos" => 10,
//            "gastos_realizacion" => 10,
//            "minerales" => [
//                [
//                    "codigo_producto" => "Ag",
//                    "nandina" => "2616.10.00.00",
//                    "cantidad" => "5",
//                    "unidad_medida" => "OT",
//                    "descripcion" => "MINERALES DE PLATA Y SUS CONCENTRADOS LOTE CMV7A\/23",
//                    "precio_unitario" => "5",
//                    "descripcion_leyes" => "0.00",
//                    "cantidad_extraccion" => 10,
//                    "unidad_extraccion" => "KG",
//                    "descuento" => "0",
//                    "subtotal" => "25"
//                ],
//                [
//                    "codigo_producto" => "Zn",
//                    "nandina" => "2608.00.00.00",
//                    "cantidad" => "5",
//                    "unidad_medida" => "LF",
//                    "descripcion" => "MINERALES DE ZINC Y SUS CONCENTRADOS LOTE CMV7A\/23",
//                    "precio_unitario" => "5",
//                    "descripcion_leyes" => "0.00",
//                    "cantidad_extraccion" => 10,
//                    "unidad_extraccion" => "KG",
//                    "descuento" => "0",
//                    "subtotal" => "25"
//                ]
//            ],
//            "comprador" =>  [
//                "id" => 12,
//                "nit" => "990011",
//                "nro_nim" => "01-0001-01",
//                "razon_social" => "TRAFIGURA PTE LTD",
//                "direccion" => "OCEAN FINANCIAL CENTRE 65-63192960, 29-00, LIANYUNGANG, CHINA, 10  COLLYER QUAY",
//                "es_aprobado" => true,
//                "info" => "01-0001-01 | TRAFIGURA PTE LTD"
//            ]
//
//        ];
        $fechaCarpeta = date('Ymd');

        $this->codigoPuntoVenta = 0;
        $this->codigoDocumentoSector = DocumentoSector::ExportacionMineral;

//        DB::beginTransaction();
//        try {
            //$ley = $this->store($request);
             $fechaActual = date("Y-m-d\TH:i:s.v", time());

        $cufActual = $this->getCufd();
        $cuisActual = $this->getCui();
        $numeroFactura = $this->getNumeroLey($cuisActual);
        Cui::whereCodigo($cuisActual)->update(['numero_factura' => $numeroFactura]);
        $cuf = $this->generateCUF($fechaActual, $numeroFactura, $cufActual->codigo_control, TipoFactura::FacturaSinDerechoACreditoFiscal, CodigoEmision::EnLinea);


            $result = $this->generarXML($request, $fechaCarpeta, $fechaActual, $numeroFactura, $cuf, $cufActual);
            //return $result;
            if (!$result['success']) {
                DB::rollback();
                return response()->json([
                    "success" => false,
                    "errors" => $result['errors']
                ]);
            }
            else {
                DB::commit();
                //return "facturasLey/20221118/" . $result['fileName'];

                $comprimido = XMLDocumentoAjuste::compress( $result['fileSigned']);
                //enviar a impuestos nacionales
                $ley = [];
                $ley['fileName'] = $comprimido;
                $ley['fechaEmision'] = $fechaActual;
                $ley['nroFactura'] = $numeroFactura;
                //return $ley;
                $resultImpuestos = (new ServicioFacturacionController($this->codigoDocumentoSector, $this->codigoPuntoVenta))->recepcionEnLineaExportacionMineral($ley);

                if (!$resultImpuestos) {
                    $compraVenta = $this->storeOffLine($request, $cufActual, $cuisActual, $cuf, $numeroFactura, $fechaActual);
                    $this->createPdfOffLine($result, $numeroFactura, $fechaCarpeta);
                } else {
                    var_dump($resultImpuestos->RespuestaServicioFacturacion);
                    // exit;
                    if ($resultImpuestos->RespuestaServicioFacturacion->transaccion) {

                        $exportacionMineral = $this->store($request, $cufActual, $cuisActual, $cuf, $numeroFactura, $fechaActual);

                        $this->purgeFiles($result, $comprimido, $numeroFactura, $fechaCarpeta);
                        DB::commit();
                        $carpetaPadre = "colquechaca";
                        return response()->json([
                            "success" => true,
                            "message" => "factura documento ajuste generado y emitido correctamente",
                            "factura" => $ley,
                            "url" => "$carpetaPadre/" . $fechaCarpeta . "/" . $numeroFactura . ".pdf"
                        ]);
                    } else {
                        DB::rollback();
                        return response()->json([
                            "success" => false,
                            "message" => "A ocurrido un error, revise los datos y vuelva a intentarlo",
                            "errors" => $resultImpuestos
                        ]);
                    }
                }
            }

//        } catch (\Exception $e) {
//            DB::rollback();
//            return response()->json(["success" => false, 'error' => $e->getMessage()], 500);
//        }

    }

    private function generarXML($ley, $fechaCarpeta, $fechaActual, $numeroFactura, $cuf, $cufActual)
    {
        //return $ley;
        $meses = Fachada::$meses;
        $rowDbf = (object)$ley;
        //return $rowDbf;
        $dataXML = new  DataXMLComercialExportacionMinera(DocumentoSector::ExportacionMineral, $this->codigoPuntoVenta);
        $type = 'facturasExportacion';
        $name = 'facturaElectronicaComercialExportacionMinera';




        $detalles = $dataXML->makeDetails($rowDbf->minerales);
        $item = [
            "cabecera" => $dataXML->makeHead($rowDbf, $cuf, $cufActual->codigo, $numeroFactura, $fechaActual),
            "detalle" => $detalles
        ];

        $gestion = date('Y');
        $mes = date('m');
        //return $meses[(int)$rowDbf->mes - 1];
        return XMLExportacionMineral::generate($item, $fechaCarpeta, $meses[(int)$mes - 1], $gestion, $name, $type);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($request,  $cufActual, $cuisActual, $cuf, $numeroFactura, $fechaActual)
    {
        $ley = FacturasImpuestos::create([
            'mes' => $request['mes'],
            'gestion' => $request['anio'],
            'nroFactura' => $numeroFactura,
            'cuf' => $cuf,
            'cufd' => $cufActual->codigo,
            'fechaEmision' => $fechaActual,
            'nombreRazonSocial' => 'SIN RAZON SOCIAL',
            'numeroDocumento' => $request['numero_documento'],
            'montoTotal' => $request['monto_total'],
            'leyenda' => "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.",
            'cuis' => $cuisActual,
            'es_enviado' => true,
            'es_anulado' => false,
            'tipo_factura' => 'ExportacionMineral',
            'user_id' => 1,
            'venta_id' => $request['id']
        ]);

        return $ley;
    }

    public function storeOffLine($request,  $cufActual, $cuisActual, $cuf, $numeroFactura, $fechaActual)
    {
        $ley = FacturasImpuestos::create([
            'mes' => $request['mes'],
            'gestion' => $request['anio'],
            'nroFactura' => $numeroFactura,
            'cuf' => $cuf,
            'cufd' => $cufActual->codigo,
            'fechaEmision' => $fechaActual,
            'nombreRazonSocial' => 'SIN RAZON SOCIAL',
            'numeroDocumento' => $request['numero_documento'],
            'montoTotal' => $request['monto_total'],
            'leyenda' => "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.",
            'cuis' => $cuisActual,
            'es_enviado' => false,
            'es_anulado' => false,
            'tipo_factura' => 'ExportacionMineral',
            'user_id' => 1,
            'venta_id' => $request['id']
        ]);

        return $ley;
    }

    public function getNumeroLey($cuis)
    {
        //$maximo = LeyPrivilegio::whereCuis($cuis)->max('nroFactura');
        $maximo =  Cui::whereCodigo($cuis)->first();
        return $maximo->numero_factura + 1;
    }

    public function anularExportacionMineral(Request $request)
    {

        $cuf = $request['cuf'];
        $codigoPuntoVenta = 0;
        $codigoDocumentoSector = DocumentoSector::ExportacionMineral;
        $codigoMotivo = $request['codigoMotivo'];


        $facturacion = new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta);

        $resultImpuestos = $facturacion->anulacionFacturaExportacion($cuf, $codigoMotivo);
        $this->AnulacionFactura($cuf);

        if ($resultImpuestos->transaccion) {
//            var_dump($resultImpuestos);

            FacturasImpuestos::whereCuf($cuf)->update(['es_anulado' => true]);

            return response()->json(["success" => true, "message" => $resultImpuestos]);
        } else {
            $seEnvioemail = false;
            return response()->json(["success" => false, "message" => $resultImpuestos, "error" => $resultImpuestos->mensajesList, "email" => $seEnvioemail ? "Correo enviado al cliente" : "NO se ha enviado correo al cliente, vuelva a intentarlo o revise el email del cliente"]);
        }
    }
    public function AnulacionFactura($cuf): void
    {
        $factura = FacturasImpuestos::whereCuf($cuf)->first();
        $venta = Venta::whereId($factura->venta_id)->first();
        $comprador = Comprador::whereId($venta->comprador_id)->first();
        Mail::to($comprador->email)->send(new AnulacionMailable($factura));
    }

    public function revertirExportacionMineral($id)
    {

        $cuf = $id;
        $codigoPuntoVenta = 0;
        $codigoDocumentoSector = DocumentoSector::ExportacionMineral;
        //$codigoMotivo = $request['codigoMotivo'];


        $facturacion = new ServicioFacturacionController($codigoDocumentoSector, $codigoPuntoVenta);

        $resultImpuestos = $facturacion->revertirFacturaExportacion($cuf);

        if ($resultImpuestos->transaccion) {
//            var_dump($resultImpuestos);

            FacturasImpuestos::whereCuf($cuf)->update(['es_anulado' => false]);

            return response()->json(["success" => true, "message" => $resultImpuestos]);
        } else {
            $seEnvioemail = false;
            return response()->json(["success" => false, "message" => $resultImpuestos, "error" => $resultImpuestos->mensajesList, "email" => $seEnvioemail ? "Correo enviado al cliente" : "NO se ha enviado correo al cliente, vuelva a intentarlo o revise el email del cliente"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\LeyPrivilegio $leyPrivilegio
     * @return \Illuminate\Http\Response
     */
    public function show(LeyPrivilegio $leyPrivilegio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\LeyPrivilegio $leyPrivilegio
     * @return \Illuminate\Http\Response
     */
    public function edit(LeyPrivilegio $leyPrivilegio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\LeyPrivilegio $leyPrivilegio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeyPrivilegio $leyPrivilegio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\LeyPrivilegio $leyPrivilegio
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeyPrivilegio $leyPrivilegio)
    {
        //
    }
    public function purgeFiles(array $result, string $comprimido, $numeroFactura, $fechaCarpeta): void
    {
        $carpetaPadre = Env::carpetaBackups;

        $carpetaPadre .= "colquechaca";
        $rutaPdf = GeneratePDF::generate($result['fileSigned'], TipoPdf::pdfExportacionMineral);

        if (!File::isDirectory($carpetaPadre)) File::makeDirectory($carpetaPadre);
        $fechaCarpeta = "$carpetaPadre/$fechaCarpeta";
        if (!File::isDirectory($fechaCarpeta)) File::makeDirectory($fechaCarpeta);

        if (File::isFile($result['fileSigned'])) {
            rename($result['fileSigned'], "$fechaCarpeta/$numeroFactura.xml");
            rename($rutaPdf, "$fechaCarpeta/$numeroFactura.pdf");
        }
//
        unlink( substr($result['fileSigned'], 0, -11) . ".xml");
        unlink($comprimido);
    }

    public function createPdfOffLine(array $result, $numeroFactura, $fechaCarpeta): void
    {
        $carpetaPadre = Env::carpetaBackups;

        $carpetaPadre .= "colquechaca";
        $rutaPdf = GeneratePDF::generate($result['fileSigned'], TipoPdf::pdfExportacionMineral);

        if (!File::isDirectory($carpetaPadre)) File::makeDirectory($carpetaPadre);
        $fechaCarpeta = "$carpetaPadre/$fechaCarpeta";
        if (!File::isDirectory($fechaCarpeta)) File::makeDirectory($fechaCarpeta);

        if (File::isFile($result['fileSigned'])) {
            rename($result['fileSigned'], "$fechaCarpeta/$numeroFactura.xml");
            rename($rutaPdf, "$fechaCarpeta/$numeroFactura.pdf");
        }
    }
}
