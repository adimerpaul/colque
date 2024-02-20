<?php

namespace App\Http\Controllers;

use App\Models\DescuentoCatalogo;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DescuentoCatalogoController extends AppBaseController
{

    public function store(Request $request)
    {
        $input = $request->all();

        DescuentoCatalogo::create($input);

        return response()->json(['res' => true, 'message' => 'Registro guardado correctamente.']);
    }

}
