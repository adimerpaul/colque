<?php

namespace App\Http\Controllers\Impuestos;

use App\Patrones\Fachada;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ControllerSoap extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @throws \Exception
     */
    protected function getClient($wsdl)
    {
        $client = ClientSoap::getClient($wsdl, Fachada::getToken());
        $resultClient = $client->verificarComunicacion();
        $result = [
            "respuesta" => $resultClient->return ?? $resultClient->RespuestaComunicacion,
        ];

        if ($result['respuesta']->transaccion == 1) {
            return $client;
        } else {
            throw new \Exception("Error en la verificacion de la comunicacion con el servicio web. Clase: ClientSoap", 926);
        }
    }

    protected function errorException(\Exception $e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            "message" => "Ha ocurrido un error!",
            'e' => $e->getMessage()], $e->getCode());
    }

    protected function errorMessage($message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], 401);
    }

    protected function successMessage($message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message], 200);
    }
}
