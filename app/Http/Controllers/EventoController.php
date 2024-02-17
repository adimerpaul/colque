<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Impuestos\CodigoImpuestos;
use App\Http\Controllers\Impuestos\ControllerSoap;
use App\Http\Controllers\Impuestos\ServicioCodigoController;
use App\Models\Cufd;
use App\Models\Evento;
use App\Patrones\CodigoEmision;
use App\Patrones\Env;
use App\Patrones\Fachada;
use App\Patrones\TipoFactura;
use DateTime;
use Illuminate\Http\Request;

class EventoController extends ControllerSoap{
    use CodigoImpuestos;
    public function evento(){
        return view('evento.index');
    }
    public function eventos(){
        return Evento::all();
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
    private function getParams($codigoEmision)
    {
        return [
            "codigoAmbiente" => Env::codigoAmbiente,
            "codigoDocumentoSector" => $this->codigoDocumentoSector,
            "codigoEmision" => $codigoEmision,
            "codigoModalidad" => Env::codigoModalidad,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "codigoSistema" => Env::codigoSistema,
            "codigoSucursal" => Env::codigoSucursal,
            "cufd" => $this->getCufd()->codigo,
            "cuis" => $this->getCui(),
            "nit" => Env::nit,
            "tipoFacturaDocumento" => TipoFactura::FacturaConDerechoACreditoFiscal,
        ];
    }
    public function createEvento(Request $request){
        $this->codigoPuntoVenta = 0;
        $cufActual = $this->getCufd();
        $cuisActual = $this->getCui();
        $this->wsdl = Env::url . "FacturacionOperaciones?WSDL";
        $client = $this->getClient($this->wsdl);
        $cufd= Cufd::where('id', $request->cuf_id)->first();
        $codigoPuntoVenta = 0;
        $codigoMotivoEvento = $request->motivo;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $fecha1 = new DateTime($fecha_inicio);
        $fecha2 = new DateTime($fecha_fin);

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
}
