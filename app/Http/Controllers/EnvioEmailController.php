<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CorreoElectronico\MiEmail;
use Illuminate\Http\Request;

class EnvioEmailController extends Controller
{
    protected ClienteDbfController $dbf;
    public function __construct(ClienteDbfController $dbf)
    {
        $this->dbf = $dbf;
    }

    public static function enviarEmailAnulacion($factura)
    {
        return MiEmail::enviarEmailAnulacion($factura);
    }

    public function enviarEmailConsumoBasico(Request $request)
    {
        $ciclo = $request['ciclo'];
        $periodo = $request['periodo'];

        //enviando los emails
        $facturasConEmail = $this->dbf->obtenerFacturablesValidadosEmail($ciclo, $periodo);
        $matriculasEnviadas = [];
        $totalFallas = 0;
        $totalEnviados = 0;
        foreach ($facturasConEmail as $factura) {
            $factura = (object)$factura;
            if ($factura->env_email == "0") {
                if (MiEmail::enviarEmail($ciclo, $periodo, $factura)) {
                    $matriculasEnviadas[] = $factura->pla_matr;
                    $totalEnviados++;
                } else
                    $totalFallas++;
            }
        }

        $this->dbf->updateEnvEmailConsumoAgua($matriculasEnviadas, $periodo);

        return response()->json(["success" => true, "message" => "$totalEnviados emails enviados<br>Fallas: $totalFallas"]);
    }

    public function enviarEmailOtroIngreso(Request $request)
    {
        $fecha = $request['fecha'];

        //enviando los emails
        $facturasConEmail = $this->dbf->obtenerOtrosIngresosValidadosEmail($fecha);

        $facturasEnviadas = [];
        $totalFallas = 0;
        $totalEnviados = 0;
        foreach ($facturasConEmail as $factura) {
            $factura = (object)$factura;
            if ($factura->env_email == "0") {
                if ($this->enviarOtroIngreso($factura)) {
                    $facturasEnviadas[] = $factura->pla_matr;
                    $totalEnviados++;
                } else
                    $totalFallas++;
            }
        }

        $this->dbf->updateEnvioEmailOtroIngreso($facturasEnviadas, $fecha);

        return response()->json(["success" => true, "message" => "$totalEnviados emails enviados<br>Fallas: $totalFallas"]);
    }

    private function enviarOtroIngreso($factura){
        if($factura->estado === 'C')
            return MiEmail::enviarEmailOtroIngreso($factura);
        else {
            $facturaArray = (array)$factura;
            $facturaArray['razon_social'] = $factura->nombre;
            $facturaArray['numero_factura--'] = $factura->pla_nume;
            $facturaArray['fecha_emision'] = $factura->pla_femi;
            $facturaArray['cuf'] = '';
            return MiEmail::enviarEmailAnulacion($facturaArray);
        }
    }

    public function enviarEmailDocumentoAjuste(Request $request)
    {
        $factura = (object)$request->factura;

        if (MiEmail::enviarEmailDocumentoAjuste($factura))
            return response()->json(["success" => true, "message" => "Email enviado correctamente!"]);
        else
            return response()->json(["success" => false, "message" => "Ha ocurrido un error al enviar el eamil, inténtelo mas tarde!"]);
    }
}
