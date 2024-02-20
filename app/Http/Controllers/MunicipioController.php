<?php


namespace App\Http\Controllers;
use App\Http\Controllers\AppBaseController;
use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends AppBaseController
{
    public function getMunicipios($id)
    {
        $municipios = Municipio::
            whereHas('provincia', function ($q) use ($id) {
                $q->where('departamento_id', $id);
            })->orderBy('nombre', 'asc')->pluck("nombre","id");
        return json_encode($municipios);
    }
}
