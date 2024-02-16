<?php
namespace App\Http\Controllers\XmlSource;

use App\Http\Controllers\ClienteDbfController;
use App\Http\Controllers\FirmaDigital\MiFirmador;
use Exception;
use Illuminate\Support\Facades\File;
use Phar;
use PharData;

class XMLServicioBasico extends XMLbase
{
    /**
     * Conversor de JSON a XMLServicioBasico
     * @param Object $json Cabecera y detalle de la factura
     * @param String $ciclo Cabecera y detalle de la factura
     * @param String $name default "facturaElectronicaServicioBasico" modalidad de facturación
     * @param String $type default "facturasConsumos" Tipo de Ingreso: "facturasConsumos" | "FacturasOtrosIngresos"
     **/
    public static function generate($request, $ciclo, $mes, $gestion, $name = 'facturaElectronicaServicioBasico', $type = 'facturasConsumos')
    {


        $type = storage_path($type);

        $month = XMLServicioBasico::toMonthNumber($mes);
        $tarifa = "$gestion$month";
        $validation = new ValidacionXSD($ciclo, $tarifa);

        $dom = self::getXmlDom($name, $name, $request);

        if (!File::isDirectory("$type/$ciclo")) {
            File::makeDirectory("$type/$ciclo", 0777, true, true);
        }

        $cuenta = $request['cabecera']['codigoCliente'];
        $tarifa = "$gestion$month";
        $fileName = "{$cuenta}_$tarifa.xml";
        $xmlFile = "$type/$ciclo/$fileName";
        error_log($xmlFile);
        $dom->save($xmlFile);

        $xmlFileSigned = self::firmarXml($xmlFile);

        if (!$validation->validate($xmlFileSigned, "$type/$name.xsd")) {
            unlink($xmlFile);
            unlink($xmlFileSigned);

            $matricula = substr($cuenta, 0, -1);
            (new ClienteDbfController())->restorePlanilla($matricula, $tarifa);


            return [
                'success' => false,
                'errors' => $validation->showErrors()
            ];
        } else {
            return [
                'success' => true,
                'fileName' => $fileName,
                'fileSigned' => $xmlFileSigned
            ];
        }
    }

    private static function firmarXml($nombreXML): string{
        $direccionXml = substr($nombreXML, 0, -4);
        return MiFirmador::getInstance()->toSign($direccionXml);
    }

    public static function toMonthNumber($month)
    {
        $months = [
            "Enero" => "01",
            "Febrero" => "02",
            "Marzo" => "03",
            "Abril" => "04",
            "Mayo" => "05",
            "Junio" => "06",
            "Julio" => "07",
            "Agosto" => "08",
            "Septiembre" => "09",
            "Octubre" => "10",
            "Noviembre" => "11",
            "Diciembre" => "12"
        ];
        return $months[$month];
    }

    /**
     * Compresion masiva de hasta 2000 archivos
     * @param $files array archivos para comprimir en tar.gz
     **/
    public static function compress($files, $ciclo, $tarifa, $iteracion, $type = 'facturasConsumos')
    {
        $type = storage_path($type);

        $compressName = "$type/$ciclo/{$ciclo}_{$tarifa}_$iteracion.tar";
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
                $codigoCliente = "$file->pla_matr$file->pla_dive";
                $a->addFromString("{$codigoCliente}_$tarifa-signed.xml", file_get_contents("$type/$ciclo/{$codigoCliente}_$tarifa-signed.xml"));
            }
            $a->compress(Phar::GZ);
            $archivo = file_get_contents("$compressName.gz");
            $hash = hash("sha256", $archivo);

            unlink($compressName);
            return [
                'url' => "$compressName.gz",
                'comprimido' => "{$ciclo}_{$tarifa}_$iteracion.tar.gz",
                'hash' => $hash,
                'cantidad' => count($files)
            ];
        } catch (Exception $e) {
            return "Exception : " . $e;
        }
    }
}
