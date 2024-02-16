<?php

namespace App\Http\Controllers\Impuestos;

use App\Http\Controllers\LeyendaController;
use App\Models\TipoMoneda;
use App\Patrones\Env;

class ServicioSincronizacionController extends ControllerSoap
{
    use CodigoImpuestos;

    private $wsdl = Env::url . "FacturacionSincronizacion?WSDL";
    private $codigoPuntoVenta;

    public function __construct($codigoPuntoVenta)
    {
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    function stdToArray($obj)
    {
        $reaged = (array)$obj;
        foreach ($reaged as $key => &$field) {
            if (is_object($field)) $field = $this->stdToArray($field);
        }
        return $reaged;
    }

    private function array2csv($fileName, $header, $data)
    {
        $fp = fopen("./results_sincronizacion/" . $fileName, 'w');

        fputcsv($fp, $header, ';');
        foreach ($data as $fields) {
            if (is_object($fields))
                $fields = $this->stdToArray($fields);
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
    }

    private function getParams()
    {
        return array(
            "SolicitudSincronizacion" => [
                "codigoAmbiente" => Env::codigoAmbiente,
                "codigoPuntoVenta" => $this->codigoPuntoVenta,
                "codigoSistema" => Env::codigoSistema,
                "codigoSucursal" => Env::codigoSucursal,
                "cuis" => $this->getCui(),
                "nit" => Env::nit,
            ]
        );
    }

    public function sincronizarFechaHora()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarFechaHora($params);
        $data = $result->RespuestaFechaHora;
        return $data->fechaHora;
    }

    public function sincronizarActividades()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarActividades($params);
        $data = $result->RespuestaListaActividades->listaActividades;
        $this->array2csv("sincronizarActividades.csv", array("codigoCaeb", "descripcion", "tipoActividad"), array($data)[0]);
        return $data;
    }

    public function sincronizarListaActividadesDocumentoSector()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarListaActividadesDocumentoSector($params);
        $data = $result->RespuestaListaActividadesDocumentoSector->listaActividadesDocumentoSector;
        $this->array2csv("sincronizarListaActividadesDocumentoSector.csv", array("codigoActividad", "codigoDocumentoSector", "tipoDocumentoSector"), $data);
        return $data;
    }

    public function sincronizarListaLeyendasFactura()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarListaLeyendasFactura($params);
        $data = $result->RespuestaListaParametricasLeyendas->listaLeyendas;

        $leyendas['data'] = $data;
        (new LeyendaController())->store($leyendas);

        $this->array2csv("sincronizarListaLeyendasFactura.csv", array("codigoActividad", "descripcionLeyenda"), $data);
        return $data;
    }

    public function sincronizarListaMensajesServicios()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarListaMensajesServicios($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarListaMensajesServicios.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarListaProductosServicios()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarListaProductosServicios($params);
        $data = $result->RespuestaListaProductos->listaCodigos;

        $this->array2csv("sincronizarListaProductosServicios.csv", array("codigoActividad", "codigoProducto", "descripcionProducto"), $data);

        return $data;
    }

    public function sincronizarParametricaEventosSignificativos()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaEventosSignificativos($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaEventosSignificativos.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaMotivoAnulacion()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaMotivoAnulacion($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaMotivoAnulacion.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaPaisOrigen()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaPaisOrigen($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaPaisOrigen.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoDocumentoIdentidad()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoDocumentoIdentidad($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoDocumentoIdentidad.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoDocumentoSector()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoDocumentoSector($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoDocumentoSector.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoEmision()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoEmision($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoEmision.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoHabitacion()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoHabitacion($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoHabitacion.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoMetodoPago()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoMetodoPago($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoMetodoPago.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoMoneda()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoMoneda($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;

        foreach ($data as $fields) {
            TipoMoneda::create([
                'codigo' => $fields->codigoClasificador,
                'descripcion' => $fields->descripcion
            ]);
        }
        //return true;
        $this->array2csv("sincronizarParametricaTipoMoneda.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTipoPuntoVenta()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTipoPuntoVenta($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTipoPuntoVenta.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaTiposFactura()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaTiposFactura($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaTiposFactura.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }

    public function sincronizarParametricaUnidadMedida()
    {
        $client = $this->getClient($this->wsdl);
        $params = $this->getParams();

        $result = $client->sincronizarParametricaUnidadMedida($params);
        $data = $result->RespuestaListaParametricas->listaCodigos;
        $this->array2csv("sincronizarParametricaUnidadMedida.csv", array("codigoClasificador", "descripcion"), $data);
        return $data;
    }
}
