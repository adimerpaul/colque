<?php


namespace App\Http\Controllers;
use App\Models\EmpresaMineral;
use App\Models\Material;
use App\Models\UnidadCotizacion;
use Illuminate\Http\Request;
use Flash;
use Response;


class EmpresaMineralController extends AppBaseController
{
    public function index(Request $request)
    {
        $minerales = Material::
        join('empresa_mineral', 'empresa_mineral.mineral_id', '=', 'mineral.id')
            ->join('unidad_cotizacion', 'unidad_cotizacion.empresa_mineral_id', '=', 'empresa_mineral.id')
            ->where('empresa_id', auth()->user()->personal->empresa->id)
            ->paginate(15);
        return view('empresa_minerales.index')
            ->with('minerales', $minerales);
    }

    public function store(Request $request)
    {
        $minerales = Material::
        join('empresa_mineral', 'empresa_mineral.mineral_id', '=', 'mineral.id')
            ->join('unidad_cotizacion', 'unidad_cotizacion.empresa_mineral_id', '=', 'empresa_mineral.id')
            ->where('empresa_id', auth()->user()->personal->empresa->id)
            ->paginate(15);
        $valores = $request->all();

        $consulta=EmpresaMineral::whereEmpresaId(auth()->user()->personal->empresa->id)->whereMineralId($request->mineral_id)->count();
        if($consulta>0)
        {
            Flash::error('El mineral ya se agregó anteriormente');
            return view('empresa_minerales.index')
                ->with('minerales', $minerales);
        }
        $valores['empresa_id']=auth()->user()->personal->empresa->id;
        $empresa_mineral=EmpresaMineral::create($valores);
        $valores['empresa_mineral_id']=$empresa_mineral->id;
        UnidadCotizacion::create($valores);

        $minerales = Material::
        join('empresa_mineral', 'empresa_mineral.mineral_id', '=', 'mineral.id')
            ->join('unidad_cotizacion', 'unidad_cotizacion.empresa_mineral_id', '=', 'empresa_mineral.id')
            ->where('empresa_id', auth()->user()->personal->empresa->id)
            ->paginate(15);
        Flash::success('Mineral guardado correctamente.');

        return view('empresa_minerales.index')
            ->with('minerales', $minerales);
    }
}
