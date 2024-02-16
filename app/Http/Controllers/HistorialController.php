<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\User;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        return Historial::whereFormularioLiquidacionId($request->formulario_id)->orderBy('id', 'desc')->with('users')->get();
    }

    public function store(){

    }

    public function getUsuarioLiquidacion($formId){
        $usuario = User::find(auth()->user()->id);
        $historial = Historial::whereAccion('Finalizado')->whereFormularioLiquidacionId($formId)->first();
        if($historial){
            $usuarioId=$historial->users_id;
            $usuario = User::find($usuarioId);
        }
        return $usuario;

    }

    public function getUsuarioLiquidacionAndroid($formId){
        $usuario = User::find(2);
        $historial = Historial::whereAccion('Finalizado')->whereFormularioLiquidacionId($formId)->first();
        if($historial){
            $usuarioId=$historial->users_id;
            $usuario = User::find($usuarioId);
        }
        return $usuario;

    }

    public function getUsuarioCancelacion($formId){
        $usuario =null;
        $historial = Historial::whereAccion('Pagado')->whereFormularioLiquidacionId($formId)->first();
        if($historial){
            $usuarioId=$historial->users_id;
            $usuario = User::find($usuarioId);
        }
        return $usuario;

    }
}
