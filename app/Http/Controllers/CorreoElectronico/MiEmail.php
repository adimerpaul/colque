<?php

namespace App\Http\Controllers\CorreoElectronico;

use App\Mail\AnulacionMailable;
use App\Mail\ConsumoBasicoMailable;
use App\Mail\DocumentoAjusteMailable;
use App\Mail\OtroIngresoMailable;
use Illuminate\Support\Facades\Mail;

class MiEmail
{

    public static function enviarEmail(string $ciclo, $periodo, $factura): bool
    {
        $email = $factura->email;

        try {
            $emailContent = new ConsumoBasicoMailable($factura, $ciclo, $periodo);
            Mail::to($email)->send($emailContent);

            error_log("Enviado: " . $email);
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log("Rechazado: " . $email);
            return false;
        }
    }

    public static function enviarEmailOtroIngreso($factura): bool
    {
        $email = $factura->email;

        try {
            $emailContent = new OtroIngresoMailable($factura);
            error_log("Enviado: " . $email);
            Mail::to($email)->send($emailContent);
            return true;
        } catch (\Exception $e) {
            error_log("Rechazado" . $email);
            error_log($e->getMessage());
            return false;
        }
    }

    public static function enviarEmailAnulacion($factura)
    {
        $email = $factura['email'];

        try {
            $emailContent = new AnulacionMailable($factura);
            Mail::to($email)->send($emailContent);
            error_log("Enviado: " . $email);

            return true;
        } catch (\Exception $e) {
            error_log("Rechazado: " . $email);
            throw new \Exception($e->getMessage(), 926);
        }
    }

    public static function enviarEmailDocumentoAjuste($factura)
    {
        $email = $factura->factura_original['email'];

        try {
            $emailContent = new DocumentoAjusteMailable($factura);
            Mail::to($email)->send($emailContent);
            error_log("Enviado: " . $email);
            return true;
        } catch (\Exception $e) {
            error_log("Rechazado: " . $email);
            throw new \Exception($e->getMessage(), 926);
        }
    }
}
