<?php
namespace App\Http\Controllers\XmlSource;

use App\Http\Controllers\ClienteDbfController;
use App\Http\Controllers\FirmaDigital\MiFirmador;
use Exception;
use Phar;
use PharData;
use Illuminate\Support\Facades\File;


class XMLOtroIngreso extends XMLbase
{
    /**
     * Conversor de JSON a XMLServicioBasico
     * @param String $name default "facturaElectronicaCompraVenta" modalidad de facturación
     * @param String $type default "facturasOtrosIngresos" Tipo de Ingreso: "facturasConsumos" | "FacturasOtrosIngresos"
     **/
    public static function generate($carpeta, $fecha, $request, $name = 'facturaElectronicaCompraVenta', $type = 'facturasOtrosIngresos')
    {
        $type = storage_path($type);

        $validation = new ValidacionXSD('otro', 'ingreso');
        $dom = self::getXmlDom($name, $name, $request);

        if (!File::isDirectory("$type/$carpeta")) {
            File::makeDirectory("$type/$carpeta", 0777, true, true);
        }

        $numeroFactura = $request['cabecera']['numeroFactura'];
        $fileName = "{$numeroFactura}.xml";
        $xmlFile = "$type/$carpeta/$fileName";
        $dom->save($xmlFile);

        $xmlFileSigned = self::firmarXml($xmlFile);

        if (!$validation->validate($xmlFileSigned, "$type/$name.xsd")) {
            unlink($xmlFile);
            unlink($xmlFileSigned);

            (new ClienteDbfController())->restorePagIngre($numeroFactura, $fecha);

            return [
                'success' => false,
                'errors' => $validation->showErrors()
            ];
        } else {
            return [
                'success' => true,
                'fileName' => $fileName
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
    public static function compress($carpeta, $files, $iteracion, $type = 'facturasOtrosIngresos')
    {
        $type = storage_path($type);

        $compressName = "$type/$carpeta/{$carpeta}_$iteracion.tar";
        if (file_exists("$compressName.gz")) {
            unlink("$compressName.gz");
        }
        if (file_exists("$compressName")) {
            unlink("$compressName");
        }
        try {
            $a = new PharData($compressName);
            foreach ($files as $file) {
                $file = (object)$file;
                $numeroFactura = (int)$file->pag_nren;
                $a->addFromString("$numeroFactura-signed.xml", file_get_contents("$type/$carpeta/$numeroFactura-signed.xml"));
            }
            $a->compress(Phar::GZ);
            $archivo = file_get_contents("$compressName.gz");
            $hash = hash("sha256", $archivo);

            unlink($compressName);
            return [
                'url' => "$compressName.gz",
                'comprimido' => "{$carpeta}_$iteracion.tar.gz",
                'hash' => $hash,
                'cantidad' => count($files)
            ];
        } catch (Exception $e) {
            return "Exception : " . $e;
        }
    }
}
