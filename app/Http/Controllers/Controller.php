<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function make_exception(\Exception $e)
    {
        return response()->json([
            'res' => false,
            "message" => "Ha ocurrido un error!",
            'e' => $e->getMessage()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error_message($message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['res' => false, 'message' => $message]);
    }
}
