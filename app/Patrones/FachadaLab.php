<?php

namespace App\Patrones;

use App\Models\Lab\PrecioElemento;

class FachadaLab
{
    public static function getPrecioEstanio()
    {
        $precioEstanio = PrecioElemento::whereElementoId(1)->first();
        return $precioEstanio->monto;
    }

    public static function getPrecioHumedad()
    {
        $precioHumedad = PrecioElemento::whereElementoId(2)->first();
        return $precioHumedad->monto;
    }
    public static function getPrecioPlata()
    {
        $precioHumedad = PrecioElemento::whereElementoId(3)->first();
        return $precioHumedad->monto;
    }

    public static function getTiposCalibraciones()
    {
        return [
            TipoCalibracion::Calidad => TipoCalibracion::Calidad,
            TipoCalibracion::Humedad => TipoCalibracion::Humedad
        ];
    }

    public static function getAmbientes()
    {
        return [
            'Ambiente 1' => 'Ambiente 1',
            'Ambiente 2' => 'Ambiente 2'
        ];
    }
    public static function getMedicionesAmbientes()
    {
        return [
            'Temperatura' => 'Temperatura',
            'Humedad' => 'Humedad'
        ];
    }

    public static function getUnidadesInsumos()
    {
        return [
            'Mililitros' => 'Mililitros',
            'Gramos' => 'Gramos'
        ];
    }


}
