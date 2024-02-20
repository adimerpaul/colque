<?php

namespace App\Http\Controllers;

use App\Models\Leyenda;
use Illuminate\Support\Facades\DB;

class LeyendaController extends Controller
{
    public function store($request)
    {
        DB::select("truncate leyendas restart identity");
        $data = $request['data'];
        foreach ($data as $fields) {
            Leyenda::create([
                'codigo' => $fields->codigoActividad,
                'descripcion' => $fields->descripcionLeyenda
            ]);
        }
        return true;
    }

    public static function getLeyenda(int $actividadEconomica)
    {
        $leyenda = Leyenda::whereCodigo($actividadEconomica)
            ->inRandomOrder()
            ->first();
        if($leyenda)
            return $leyenda->descripcion;
        else
            return "Ley Nro 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad";
    }
}
