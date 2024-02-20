<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataXML\DataXML;
use App\Http\Controllers\FirmaDigital\MiFirmador;
use App\Http\Controllers\Impuestos\CodigoImpuestos;
use App\Http\Controllers\Impuestos\ControllerSoap;
use App\Http\Controllers\Impuestos\ServicioCodigoController;
use App\Models\Cufd;
use App\Models\Evento;
use App\Models\FacturasImpuestos;
use App\Patrones\CodigoEmision;
use App\Patrones\Env;
use App\Patrones\Fachada;
use App\Patrones\TipoFactura;
use DateTime;
use DOMDocument;
use Illuminate\Http\Request;
use Phar;
use PharData;
use SimpleXMLElement;

class EventoController extends ControllerSoap{
    use CodigoImpuestos, DataXML;
    public function evento(){
        return view('evento.index');
    }
    public function eventos(){
        return Evento::orderBy('id', 'desc')->get();
    }
    public function cufs(){
        return Cufd::orderBy('id', 'desc')->get();
    }
    public function motivoEvento(){
        $filePublic = public_path('results_sincronizacion/sincronizarParametricaEventosSignificativos.csv');
        $file = fopen($filePublic, "r");
        $data = [];
        $index = 0;
        while (($row = fgetcsv($file, 0, ";")) !== FALSE) {
            if ($index++ === 0) continue;
            $data[] = $row;
        }
        fclose($file);
        //convertor a json y ordenar
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                "codigo" => $item[0],
                "descripcion" => $item[1]
            ];
        }
        ///ordenar
        usort($result, function ($a, $b) {
            return $a['codigo'] <=> $b['codigo'];
        });
        return $result;
    }
    public function createEvento(Request $request){
        $this->codigoPuntoVenta = 0;
        $cufActual = $this->getCufd();
        $cuisActual = $this->getCui();

        $cufd= Cufd::where('id', $request->cuf_id)->first();
        $codigoPuntoVenta = 0;
        $codigoMotivoEvento = $request->motivo;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $fecha1 = new DateTime($fecha_inicio);
        $fecha2 = new DateTime($fecha_fin);

        $this->wsdl = Env::url . "FacturacionOperaciones?WSDL";
        $client = $this->getClient($this->wsdl);
        $params = array(
            "SolicitudEventoSignificativo" => [
                "codigoAmbiente"=>Env::codigoAmbiente,
                "codigoMotivoEvento"=>$codigoMotivoEvento,
                "codigoPuntoVenta"=>$codigoPuntoVenta,
                "codigoSistema"=>Env::codigoSistema,
                "codigoSucursal"=>Env::codigoSucursal,
                "cufd"=>$cufActual->codigo,
                "cufdEvento"=>$cufd->codigo,
                "cuis"=>$cuisActual,
                "descripcion"=>$codigoMotivoEvento,
                "fechaHoraFinEvento"=> $fecha2->format("Y-m-d\TH:i:s.000"),
                "fechaHoraInicioEvento"=> $fecha1->format("Y-m-d\TH:i:s.000"),
                "nit"=>Env::nit,
            ]
        );
        $result = $client->registroEventoSignificativo($params);
        error_log('result: '.json_encode($result));
        $codigo= $result->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
        $evento = new Evento();
        $evento->cufd = $cufd->codigo;
        $evento->fecha_inicio = $fecha_inicio;
        $evento->fecha_fin = $fecha_fin;
        $evento->codigo = $codigo;
        $evento->save();
        return $evento;
    }
    public function createCuf(Request $request){
        $codigoPuntoVenta = 0;

        if ($this->isCufdRegister($codigoPuntoVenta))
            return response()->json(['res' => false, 'message' => "El CUFD ya se encuentra registrado para el punto de venta: $codigoPuntoVenta"], 400);

        $data = (new ServicioCodigoController($codigoPuntoVenta))->cufd();

        $fecha = date("Y-m-d H:i:s", strtotime("$data->fechaVigencia"));
        $fecha1 = strtotime('-8 hour', strtotime($fecha));
        $fechaVigencia = date("Y-m-d H:i:s", $fecha1);

        $lol = Cufd::create([
            'transaccion' => $data->transaccion,
            'codigo_punto_venta' => $codigoPuntoVenta,
            'codigo' => $data->codigo,
            'codigo_control' => $data->codigoControl,
            'direccion' => $data->direccion,
            'fecha_vigencia' => $fechaVigencia
        ]);
        return $lol;
    }
    private function isCufdRegister($codigoPuntoVenta)
    {
        $actualDate = Fachada::getFecha()->format("Y-m-d H:i:s");
        $cufd = Cufd::where('fecha_vigencia', '>=', $actualDate)
            ->whereCodigoPuntoVenta($codigoPuntoVenta)
            ->orderByDesc('id')
            ->first();
        return !is_null($cufd);
    }
    public function verificar(Request $request){
        $this->codigoPuntoVenta = 0;

        error_log('verificar: '.json_encode($request->all()));
        $evento = Evento::where('id', $request->id)->first();
        error_log('evento: '.json_encode($evento));

        $cufActual = $this->getCufd();
        $cuisActual = $this->getCui();

        $this->wsdl = Env::url . "ServicioFacturacionCompraVenta?WSDL";
        $client = $this->getClient($this->wsdl);
        $result= $client->validacionRecepcionPaqueteFactura([
            "SolicitudServicioValidacionRecepcionPaquete"=>[
                "codigoAmbiente"=>Env::codigoAmbiente,
                "codigoDocumentoSector"=>1,
                "codigoEmision"=>2,//1 online 2 offline
                "codigoModalidad"=>1,//1 electronica 2 computarizada
                "codigoPuntoVenta"=>0,
                "codigoSistema"=>Env::codigoSistema,
                "codigoSucursal"=>0,
                "cufd"=>$cufActual->codigo,
                "cuis"=>$cuisActual,
                "nit"=>Env::nit,
                "tipoFacturaDocumento"=>1,
                "codigoRecepcion"=>$evento->codigo_recepcion,
            ]
        ]);
        return $result;
    }
    public function envioPaquetes(Request $request){
        $this->codigoPuntoVenta = 0;

        $id = $request->id;
        $cufActual = $this->getCufd();
        $cuisActual = $this->getCui();

        $evento = Evento::where('id', $id)->first();
        $fecha_inicio = $evento->fecha_inicio;
        $fecha_fin = $evento->fecha_fin;

        $facturas_impuestos = FacturasImpuestos::where('fechaEmision', '>=', $fecha_inicio)
            ->where('fechaEmision', '<=', $fecha_fin)
            ->where('es_enviado', 0)
            ->get();

        $this->generateXML($evento, $facturas_impuestos);

        $archiveName = "archivos.tar";
//        delete archivos

        $this->createZip($archiveName);
        $archivo=$this->getFileGzip($archiveName.".gz");
        $hashArchivo=hash('sha256', $archivo);

        $this->wsdl = Env::url . "ServicioFacturacionCompraVenta?WSDL";
        $client = $this->getClient($this->wsdl);

        $params = array(
            "SolicitudServicioRecepcionPaquete" => [
                "codigoAmbiente"=>Env::codigoAmbiente,
                "codigoDocumentoSector"=>1,
                "codigoEmision"=>2,//1 online 2 offline
                "codigoModalidad"=>1,//1 electronica 2 computarizada
                "codigoPuntoVenta"=>0,
                "codigoSistema"=>Env::codigoSistema,
                "codigoSucursal"=>0,
                "cufd"=>$cufActual->codigo,
                "cuis"=>$cuisActual,
                "nit"=>Env::nit,
                "tipoFacturaDocumento"=>1,
                "archivo"=>$archivo,
                "fechaEnvio"=>date("Y-m-d\TH:i:s.000"),
                "hashArchivo"=>$hashArchivo,
                "cantidadFacturas"=>count($facturas_impuestos),
                "codigoEvento"=>$evento->codigo,
//        "cafc"=>"101DB3D11742D",
            ]
        );
        $result = $client->recepcionPaqueteFactura($params);
//        error_log('result: '.json_encode($result));
        $evento=Evento::where('id', $id)->first();
        $evento->codigo_recepcion=$result->RespuestaServicioFacturacion->codigoRecepcion;
        $evento->save();
        return response()->json(['res' => true, 'message' => "Se envio el paquete de facturas con exito"], 200);

    }
    function getFileGzip($fileName)
    {
        $file = $fileName;

        $handle = fopen($file, "rb");
        $contents = fread($handle, filesize($fileName));
        fclose($handle);

        return $contents;
    }
    public function createZip($archiveName){
        if (file_exists(public_path('archivos.tar')))
            unlink(public_path('archivos.tar'));
        if (file_exists(public_path('archivos.tar.gz')))
            unlink(public_path('archivos.tar.gz'));

        $a = new PharData($archiveName);

        // ADD FILES TO archive.tar FILE
        $files = glob(public_path('archivos/*'));
        $count = 0;
        foreach($files as $file){
//            error_log('creando zip: '.$file);
            $a->addFile($file); //Agregamos el fichero
            $count++;
            echo $count."\n";
        }

        // COMPRESS archive.tar FILE. COMPRESSED FILE WILL BE archive.tar.gz
        $a->compress(Phar::GZ);
    }
    private function generateXML($evento, $facturas_impuestos){

        $cufdData= Cufd::where('codigo', $evento->cufd)->first();

        $this->deleteFieldsArchivos();
        foreach ($facturas_impuestos as $f) {
//            $miliSegundo=str_pad($i, 3, '0', STR_PAD_LEFT);
//            $fechaEnvio=date("Y-m-d\T$h:$m:$s").".$miliSegundo";
            $fecha1 = new DateTime($f->fecha_emision);
            $fechaEnvio = $fecha1->format("Y-m-d\TH:i:s.000");
            $fechaCuf = $fecha1->format("YmdHis.000");//            $cuf = $cuf->obtenerCUF($nit, date("Ymd".$h.$m.$s."$miliSegundo"), $codigoSucursal, $codigoModalidad, $codigoEmision, $cdf, $codigoDocumentoSector, $nf, $codigoPuntoVenta);
//            $cuf = $this->generateCUF($fechaActual, $numeroFactura, $cufActual->codigo_control, TipoFactura::FacturaConDerechoACreditoFiscal, CodigoEmision::EnLinea);
            $this->codigoDocumentoSector = 1;
            $this->codigoPuntoVenta = 0;
            $cuf= $this->generateCUF($f->fechaEmision, $f->nroFactura, $cufdData->codigo_control, TipoFactura::FacturaConDerechoACreditoFiscal, CodigoEmision::FueraDeLinea);
//            $cuf=$cuf.$codigoControl;
//            error_log('cuf: '.$cuf);
            $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
<facturaElectronicaCompraVenta xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:noNamespaceSchemaLocation='facturaElectronicaCompraVenta.xsd'>    <cabecera>
        <nitEmisor>".Env::nit."</nitEmisor>
        <razonSocialEmisor>".Env::razonSocial."</razonSocialEmisor>
        <municipio>Oruro</municipio>
        <telefono>67200160</telefono>
        <numeroFactura>".$f->nroFactura."</numeroFactura>
        <cuf>$cuf</cuf>
        <cufd>".$cufdData->codigo."</cufd>
        <codigoSucursal>0</codigoSucursal>
        <direccion>AV. JORGE LOPEZ #123</direccion>
        <codigoPuntoVenta>0</codigoPuntoVenta>
        <fechaEmision>$fechaEnvio</fechaEmision>
        <nombreRazonSocial>Mi razon social</nombreRazonSocial>
        <codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad>
        <numeroDocumento>5115889</numeroDocumento>
        <complemento xsi:nil='true'/>
        <codigoCliente>51158891</codigoCliente>
        <codigoMetodoPago>1</codigoMetodoPago>
        <numeroTarjeta xsi:nil='true'/>
        <montoTotal>99</montoTotal>
        <montoTotalSujetoIva>99</montoTotalSujetoIva>
        <codigoMoneda>1</codigoMoneda>
        <tipoCambio>1</tipoCambio>
        <montoTotalMoneda>99</montoTotalMoneda>
        <montoGiftCard xsi:nil='true'/>
        <descuentoAdicional>1</descuentoAdicional>
        <codigoExcepcion xsi:nil='true'/>
        <cafc xsi:nil='true'/>
        <leyenda>Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los
            servicios que utilices.
        </leyenda>
        <usuario>pperez</usuario>
        <codigoDocumentoSector>1</codigoDocumentoSector>
    </cabecera>
        <detalle>
        <actividadEconomica>466201</actividadEconomica>
        <codigoProductoSin>991009</codigoProductoSin>
        <codigoProducto>JN-131231</codigoProducto>
        <descripcion>JUGO DE NARANJA EN VASO</descripcion>
        <cantidad>1</cantidad>
        <unidadMedida>1</unidadMedida>
        <precioUnitario>100</precioUnitario>
        <montoDescuento>0</montoDescuento>
        <subTotal>100</subTotal>
        <numeroSerie>124548</numeroSerie>
        <numeroImei>545454</numeroImei>
    </detalle>
</facturaElectronicaCompraVenta>");
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            $nameFile=str_replace(' ', '', microtime());
//            $dom->save("archivos/".$nameFile.'.xml');
            $dom->save(public_path('archivos/'.$nameFile.'.xml'));

            $miFirmador = MiFirmador::getInstance();
            $miFirmador->toSign(public_path('archivos/'.$nameFile), public_path('archivos/'.$nameFile));
        }
    }

    /**
     * @return void
     */
    public function deleteFieldsArchivos(): void
    {
        $files = glob(public_path('archivos/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    }

}
