<?php
namespace App\Http\Controllers\XmlSource;

use App\Http\Controllers\FirmaDigital\MiFirmador;
use Illuminate\Support\Facades\File;


class XMLDocumentoAjuste extends XMLbase
{
    /**
     * Conversor de JSON a XMLServicioBasico
     * @param String $name default "facturaElectronicaCompraVenta" modalidad de facturación
     * @param String $nameXsd default "facturaElectronicaCompraVenta" modalidad de facturación
     * @param String $type default "facturasOtrosIngresos" Tipo de Ingreso: "facturasConsumos" | "FacturasOtrosIngresos"
     **/
    public static function generate($carpeta, $numeroFactura, $request, $name, $nameXsd, $type)
    {
        $type = storage_path($type);

        $validation = new ValidacionXSD('documento', 'ajuste');
        $dom = self::getXmlDom($name, $nameXsd, $request);

        if (!File::isDirectory("$type/$carpeta")) {
            File::makeDirectory("$type/$carpeta", 0777, true, true);
        }

        $fileName = "{$numeroFactura}.xml";
        $xmlFile = "$type/$carpeta/$fileName";
        $dom->save($xmlFile);

        $xmlFileSigned = self::firmarXml($xmlFile);

        if (!$validation->validate($xmlFileSigned, "$type/$nameXsd.xsd")) {
            unlink($xmlFile);
            unlink($xmlFileSigned);

            return [
                'success' => false,
                'errors' => $validation->showErrors()
            ];
        } else {
            return [
                'success' => true,
                'file' => $xmlFile,
                'fileSigned' => $xmlFileSigned
            ];
        }
    }

    private static function firmarXml($nombreXML): string
    {
        $direccionXml = substr($nombreXML, 0, -4);
        return MiFirmador::getInstance()->toSign($direccionXml);
    }

    /**
     * Compresion masiva de hasta 2000 archivos
     * @param $files array archivos para comprimir en tar.gz
     **/
    public static function compress($filePath)
    {
        $file = $filePath;
        $gzfile = $filePath . '.gz';
        $fp = gzopen($gzfile, 'w9');
        gzwrite($fp, file_get_contents($file));
        gzclose($fp);
        return $gzfile;
    }
}
