<?php

namespace App\Http\Controllers\Impuestos;

use App\Models\Cufd;
use App\Models\Cui;
use App\Patrones\Fachada;

trait CodigoImpuestos
{
    /**
     * @throws \Exception
     */
    protected function getCui()
    {
        $actualDate = Fachada::getFecha()->format("Y-m-d H:i:s");
        $cui = Cui::where('fecha_vigencia', '>=', $actualDate)
            ->whereCodigoPuntoVenta($this->codigoPuntoVenta)
            ->orderByDesc('id')
            ->first();
        if (is_null($cui)) {
            throw new \Exception("No se encontro el CUI para el punto de venta: $this->codigoPuntoVenta", 926);
        }
        else
            return $cui->codigo;
    }

    protected function getCufd()
    {
        $actualDate = Fachada::getFecha()->format("Y-m-d H:i:s");
        $cufd = Cufd::where('fecha_vigencia', '>=', $actualDate)
            ->whereCodigoPuntoVenta($this->codigoPuntoVenta)
            ->orderByDesc('id')
            ->first();
        if (is_null($cufd)) {
            throw new \Exception("No se encontro el CUFD para el punto de venta: $this->codigoPuntoVenta", 926);
        }
        else
            return $cufd;
    }
}
