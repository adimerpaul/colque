<?php
namespace App\Http\Controllers\XmlSource;

class CUF
{
    /**
     *  Método que permite la generación del código CUF
     *  req:
     *         nit_emisor: number(13) = 123456789
     *         fecha_hora: number(17) = 20190113163721231
     *         susursal: number(4) = 0
     *         modalidad: number[1..3] = 1
     *         tipo_emision: number[1..3]  = 1
     *         tipo_factura_documento_ajuste: number[1..3] = 1
     *         tipo_documento_sector: number(2) [1..24] = 1
     *         numero_factura: number(10)[1..10] = 1
     *         pos: number(4)[0..n] = 0
     * codigoControl: string
     **/
    public static function generate($request, $codigoControl = '')
    {
        $reqFixed = [
            CUF::fix($request['nit_emisor'], 13),
            CUF::fix($request['fecha_hora'], 17),
            CUF::fix($request['sucursal'], 4),
            $request['modalidad'],
            $request['tipo_emision'],
            $request['tipo_factura_documento_ajuste'],
            CUF::fix($request['tipo_documento_sector'], 2),
            CUF::fix($request['numero_factura'], 10),
            CUF::fix($request['pos'], 4)
        ];
        $resZeros = implode("", $reqFixed);
        $resMod11 = CUF::mod11($resZeros);
        $number = $resZeros . $resMod11;
        return CUF::big_base_convert($number, 16) . $codigoControl;
    }

    private static function fix($field, $length)
    {
        $zeros = $length - strlen($field);
        for ($i = 1; $i <= $zeros; $i++) {
            $field = "0{$field}";
        }
        return $field;
    }

    private static function mod11($number)
    {
        $reverse = strrev($number);
        $digits = str_split($reverse);
        $weight = 2;
        $acc = 0;
        foreach ($digits as $digit) {
            $acc += $weight * (int)$digit;
            if (++$weight > 9) {
                $weight = 2;
            }
        }

        $mod = $acc % 11;
        $resMod = $mod;

        if ($resMod === 11) {
            $response = 0;
        }
        if ($resMod === 10) {
            $response = 1;
        }
        if ($resMod < 10) {
            $response = $resMod;
        }
        return $response;
    }

    /**
     * Conversión de números en base 10 a base 64 o 16
     * @param string $numero : numero a convertir
     * @param string $base : Opcional, convierte a la base indicada
     * @return string: numero convertido
     */
    private static function big_base_convert($numero, $base = "64")
    {
        $dic = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
            'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
            'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '+', '/');
        $cociente = "1";
        $resto = "";
        $palabra = "";
        while (bccomp($cociente, "0")) {
            $cociente = bcdiv($numero, $base);
            $resto = bcmod($numero, $base);
            $palabra = $dic[0 + $resto] . $palabra;
            $numero = "" . $cociente;
        }
        return $palabra;
    }
}
