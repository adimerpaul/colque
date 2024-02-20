<?php
declare(strict_types=1);
namespace App\Http\Controllers\XmlSource;

use DOMDocument;

class ValidacionXSD
{

    private $errors;
    private $doc;
    private $fileXML = '';
    private $ciclo;
    private $tarifa;

    /**
     * @param String $ciclo Ciclo con observaciones Ej. A, B, C
     * @param String $tarifa Periodo que corresponde la observacion Ej. 202106 = {GESTION}{MES}
     **/
    function __construct(string $ciclo, string $tarifa)
    {
        //Representa un documento HTML o XMLServicioBasico en su totalidad;
        $this->doc = new DOMDocument('1.0', 'utf-8');
        $this->ciclo = $ciclo;
        $this->tarifa = $tarifa;
    }
    /**
     * @param String $filexml Archivo XMLServicioBasico a validar
     * @param String $xsd Esquema de validacion
     *
     * @return bool TRUE El arcivo XMLServicioBasico es valido
     *              FALSE El archiv XMLServicioBasico no es valido
     */
    public function validate(string $filexml, string $xsd): bool
    {
        $this->fileXML = $filexml;
        if (!file_exists($filexml) || !file_exists($xsd)) {
            $messageAlert = "Archivo $filexml o $xsd no existe.";
            $this->writeLog($messageAlert);
            return false;
        }

        //Habilita/Deshabilita errores libxml y permite al usuario extraer
        //información de errores según sea necesario
        libxml_use_internal_errors(true);
        //lee archivo XMLServicioBasico
        $myfile = fopen($filexml, "r");
        $contents = fread($myfile, filesize($filexml));
        $this->doc->loadXML($contents, LIBXML_NOBLANKS);
        fclose($myfile);
        // Valida un documento basado en un esquema
        if (!$this->doc->schemaValidate($xsd)) {
            //Recupera un array de errores
            $this->errors = libxml_get_errors();
            return false;
        }
        return true;
    }

    /**
     * Write Log
     * @param {string} message mensaje
     **/
    function writeLog($message)
    {
        $file = "{$this->ciclo}_$this->tarifa.csv";
        $dateTime = date('Y-m-d H:i:s');
        $txt = "$dateTime;$this->fileXML;$message";
        file_put_contents($file, $txt, FILE_APPEND | LOCK_EX);
    }

    /**
     * Retorna un string con los errores de validacion si es que existieran
     */
    public function showErrors(): string
    {
        $msg = '';
        if ($this->errors == NULL) {
            return '';
        }
        foreach ($this->errors as $error) {
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $nivel = 'Warning';
                    break;
                case LIBXML_ERR_ERROR :
                    $nivel = 'Error';
                    break;
                case LIBXML_ERR_FATAL:
                    $nivel = 'Fatal Error';
                    break;
            }
            $provisional = "Error $error->code [$nivel], linea: $error->line, mensaje: $error->message";
            $msg .= preg_replace("/\n/", "<br/>", $provisional);
        }
        //Limpia el buffer de errores de libxml
        libxml_clear_errors();
        $this->writeLog($msg);
        return $msg;
    }

    function getErrors()
    {
        return $this->errors;
    }

    function setErrors($errors)
    {
        $this->errors = $errors;
    }
}
