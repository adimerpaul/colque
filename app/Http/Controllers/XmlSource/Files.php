<?php

namespace App\Http\Controllers\XmlSource;

use App\Patrones\Env;
use App\Patrones\TipoPdf;
use PharData;
use Illuminate\Support\Facades\File;

class Files
{
    public static $esValidadoEnImpuestos = true;

    /**
     *  Eliminacion de facturas digitales xml y migracion de archivos tar.gz a carpeta destino
     **/
    public static function getFilesAndPurge($filename, $carpeta = "C", $type = "facturasConsumos")
    {
        $type = storage_path($type);

        $filepath = "$type/$carpeta/$filename";
        $archive = new PharData($filepath);
        $files = [];
        foreach ($archive as $file) {
            if ($type == storage_path("facturasConsumos")) {
                //extrayendo el numero de matricula de los archivos xml
                $nombreFactura = substr($file, strlen($file) - 25, 6); //xml firmado

                $fileXmlDeleteSinFirmar = substr($file, strlen($file) - 25, 14); //xml firmar
                $fileXmlDeleteFirmado = substr($file, strlen($file) - 25, 21); //xml sin firmar

                self::deleteXmlFiles($carpeta, "$type/$carpeta/$fileXmlDeleteSinFirmar.xml", "$type/$carpeta/$fileXmlDeleteFirmado.xml", $fileXmlDeleteSinFirmar, TipoPdf::pdfServicioBasico);
            } else {
                //extrayendo el numero de factura de los archivos xml
                $fileExtract = preg_replace('/^.+[\\\\\\/]/', '', $file);
                $nombreFacturaFirmado = substr($fileExtract, 0, strlen($fileExtract) - 4); //xml firmado
                $nombreFactura = substr($fileExtract, 0, strlen($fileExtract) - 11); //xml sin firmar
                self::deleteXmlFiles($carpeta, "$type/$carpeta/$nombreFactura.xml", "$type/$carpeta/$nombreFacturaFirmado.xml", $nombreFacturaFirmado, TipoPdf::pdfOtrosIngresos);
            }
            $files[] = $nombreFactura;
        }
        if(File::isFile($filepath)) {
            rename($filepath, Env::carpetaBackups . $filename);
        }

        return $files;
    }

    private static function deleteXmlFiles($carpeta, $pathXmlFileSinFirmar, $pathXmlFileFirmado, $nombreFacturaFirmado, $tipoServicio)
    {
        if(self::$esValidadoEnImpuestos) {
            self::deleteXmlAndGeneratePdf($carpeta, $pathXmlFileFirmado, $tipoServicio, $nombreFacturaFirmado, $pathXmlFileSinFirmar);
        }
        else{
            if (File::isFile($pathXmlFileSinFirmar)) unlink($pathXmlFileSinFirmar);
            if (File::isFile($pathXmlFileFirmado)) unlink($pathXmlFileFirmado);
        }
    }

    public static function deleteXmlAndGeneratePdf($carpeta, $pathXmlFileFirmado, $tipoServicio, $nombreFacturaFirmado, $pathXmlFileSinFirmar): void
    {
        $carpetaBackups = Env::carpetaBackups . $carpeta;
        if (!File::isDirectory($carpetaBackups)) File::makeDirectory($carpetaBackups);

         //if(GeneratePDF::generate($pathXmlFileFirmado, $tipoServicio)) {
        if (true) {
//            $pathPdfFile = str_replace(".xml", ".pdf", $pathXmlFileFirmado);
//            rename($pathPdfFile, "$carpetaBackups/$nombreFacturaFirmado.pdf");

            if (File::isFile($pathXmlFileSinFirmar)) unlink($pathXmlFileSinFirmar);
            if (File::isFile($pathXmlFileFirmado)) rename($pathXmlFileFirmado, "$carpetaBackups/$nombreFacturaFirmado.xml");
        }
    }
}
