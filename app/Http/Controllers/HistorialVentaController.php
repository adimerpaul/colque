<?php

namespace App\Http\Controllers;

use App\Models\HistorialVenta;
use Illuminate\Http\Request;

class HistorialVentaController extends Controller
{
    public function index(Request $request)
    {
        return HistorialVenta::whereVentaId($request->venta_id)->orderBy('id', 'desc')->with('users')->get();
    }
}
