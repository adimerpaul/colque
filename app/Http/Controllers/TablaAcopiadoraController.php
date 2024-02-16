<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTablaAcopiadoraRequest;
use App\Http\Requests\UpdateTablaAcopiadoraRequest;
use App\Models\TablaAcopiadora;
use App\Models\TablaAcopiadoraDetalle;
use App\Patrones\Fachada;
use App\Repositories\TablaAcopiadoraRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Response;

class TablaAcopiadoraController extends AppBaseController
{
    /** @var  TablaAcopiadoraRepository */
    private $tablaAcopiadoraRepository;

    public function __construct(TablaAcopiadoraRepository $tablaAcopiadoraRepo)
    {
        $this->tablaAcopiadoraRepository = $tablaAcopiadoraRepo;
    }

    public function index(Request $request)
    {
        $tablaAcopiadoras = TablaAcopiadora::orderByDesc('id')->get();

        return view('tabla_acopiadoras.index')
            ->with('tablaAcopiadoras', $tablaAcopiadoras);
    }

    public function create()
    {
        return view('tabla_acopiadoras.create');
    }

    public function store(CreateTablaAcopiadoraRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            if(!$this->estaEnRango($input)){
                Flash::error('La cotización inicial y final no debe tener mas de 15 de diferencia');
                return redirect(route('tablaAcopiadoras.create'));
            }

            $input['fecha'] = Fachada::getFecha();
            if(!$request->has('gestion'))
                $input['gestion'] = date('Y');

            $tablaAcopiadora = $this->tablaAcopiadoraRepository->create($input);
            $this->llenarMatriz($tablaAcopiadora);

            DB::commit();
            Flash::success('Tabla Acopiadora guardada correctamente.');
            return redirect(route('tablaAcopiadoras.show', ['tablaAcopiadora' => $tablaAcopiadora->id]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Flash::success('Ha ocurrido un error, vuelva a intentarlo. ' . $e->getMessage());
            return redirect(route('tablaAcopiadoras.create'));
        }
    }

    private function estaEnRango($input){
        return (int)$input['cotizacion_final'] - (int)$input['cotizacion_inicial'] < 16;
    }

    public function show($id)
    {
        $tablaAcopiadora = TablaAcopiadora::with(['tablaAcopiadoraDetalles'])->find($id);

        if (empty($tablaAcopiadora)) {
            Flash::error('Tabla Acopiadora no encontrada');
            return redirect(route('tablaAcopiadoras.index'));
        }

        return view('tabla_acopiadoras.show')->with('ta', $tablaAcopiadora);
    }

    public function edit($id)
    {
        $tablaAcopiadora = $this->tablaAcopiadoraRepository->find($id);

        if (empty($tablaAcopiadora)) {
            Flash::error('Tabla Acopiadora no encontrada');

            return redirect(route('tablaAcopiadoras.index'));
        }

        return view('tabla_acopiadoras.edit')->with('tablaAcopiadora', $tablaAcopiadora);
    }

    public function update($id, UpdateTablaAcopiadoraRequest $request)
    {
        $tablaAcopiadora = $this->tablaAcopiadoraRepository->find($id);

        DB::beginTransaction();
        try {
            if (empty($tablaAcopiadora)) {
                Flash::error('Tabla Acopiadora no encontrada');
                return redirect(route('tablaAcopiadoras.index'));
            }

            $input = $request->all();

            if(!$this->estaEnRango($input)){
                Flash::error('La cotización inicial y final no debe tener mas de 15 de diferencia');
                return redirect(route('tablaAcopiadoras.edit', ['tablaAcopiadora' => $tablaAcopiadora->id]));
            }

            $input['fecha'] = Fachada::getFecha();
            $tablaAcopiadora = $this->tablaAcopiadoraRepository->update($input, $id);

            $this->llenarMatriz($tablaAcopiadora);

            DB::commit();
            Flash::success('Tabla Acopiadora actualizada correctamente.');
            return redirect(route('tablaAcopiadoras.show', ['tablaAcopiadora' => $tablaAcopiadora->id]));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            Flash::success('Ha ocurrido un error, vuelva a intentarlo. ' . $e->getMessage());
            return redirect(route('tablaAcopiadoras.edit', ['tablaAcopiadora' => $tablaAcopiadora->id]));
        }
    }

    public function destroy($id)
    {
        $tablaAcopiadora = $this->tablaAcopiadoraRepository->find($id);

        if (empty($tablaAcopiadora)) {
            Flash::error('Tabla Acopiadora no encontrada');

            return redirect(route('tablaAcopiadoras.index'));
        }

        $this->tablaAcopiadoraRepository->delete($id);

        Flash::success('Tabla Acopiadora borrada correctamente.');

        return redirect(route('tablaAcopiadoras.index'));
    }

    public function seleccionar($id)
    {
        TablaAcopiadora::where('es_seleccionado', true)->update(['es_seleccionado' => false]);
        TablaAcopiadora::whereId($id)->update(['es_seleccionado' => true]);
        Flash::success('Selección cambiada correctamente.');

        return redirect(route('tablaAcopiadoras.index'));
    }

    private function llenarMatriz(\Illuminate\Database\Eloquent\Model $ta)
    {
        TablaAcopiadoraDetalle::whereTablaAcopiadoraId($ta->id)->delete();
        $factor = 0;
        for ($i = $ta->cotizacion_inicial; $i <= $ta->cotizacion_final; $i += 0.01)
        {
            TablaAcopiadoraDetalle::create([
               'cotizacion' => round($i,2),
                'l_0' =>  isset($ta->l_0_inicial) && isset($ta->l_0_incremental) ? $ta->l_0_inicial + ($ta->l_0_incremental * $factor) : null,
                'l_5' =>  isset($ta->l_5_inicial) && isset($ta->l_5_incremental) ? $ta->l_5_inicial + ($ta->l_5_incremental * $factor) : null,
                'l_10' => isset($ta->l_10_inicial) && isset($ta->l_10_incremental) ? $ta->l_10_inicial + ($ta->l_10_incremental * $factor) : null,
                'l_15' => isset($ta->l_15_inicial) && isset($ta->l_15_incremental) ? $ta->l_15_inicial + ($ta->l_15_incremental * $factor) : null,
                'l_20' => isset($ta->l_20_inicial) && isset($ta->l_20_incremental) ? $ta->l_20_inicial + ($ta->l_20_incremental * $factor) : null,
                'l_25' => isset($ta->l_25_inicial) && isset($ta->l_25_incremental) ? $ta->l_25_inicial + ($ta->l_25_incremental * $factor) : null,
                'l_30' => isset($ta->l_30_inicial) && isset($ta->l_30_incremental) ? $ta->l_30_inicial + ($ta->l_30_incremental * $factor) : null,
                'l_35' => isset($ta->l_35_inicial) && isset($ta->l_35_incremental) ? $ta->l_35_inicial + ($ta->l_35_incremental * $factor) : null,
                'l_40' => isset($ta->l_40_inicial) && isset($ta->l_40_incremental) ? $ta->l_40_inicial + ($ta->l_40_incremental * $factor) : null,
                'l_45' => isset($ta->l_45_inicial) && isset($ta->l_45_incremental) ? $ta->l_45_inicial + ($ta->l_45_incremental * $factor) : null,
                'l_50' => isset($ta->l_50_inicial) && isset($ta->l_50_incremental) ? $ta->l_50_inicial + ($ta->l_50_incremental * $factor) : null,
                'l_55' => isset($ta->l_55_inicial) && isset($ta->l_55_incremental) ? $ta->l_55_inicial + ($ta->l_55_incremental * $factor) : null,
                'l_60' => isset($ta->l_60_inicial) && isset($ta->l_60_incremental) ? $ta->l_60_inicial + ($ta->l_60_incremental * $factor) : null,
                'l_65' => isset($ta->l_65_inicial) && isset($ta->l_65_incremental) ? $ta->l_65_inicial + ($ta->l_65_incremental * $factor) : null,
                'l_70' => isset($ta->l_70_inicial) && isset($ta->l_70_incremental) ? $ta->l_70_inicial + ($ta->l_70_incremental * $factor) : null,
                'l_75' => isset($ta->l_75_inicial) && isset($ta->l_75_incremental) ? $ta->l_75_inicial + ($ta->l_75_incremental * $factor) : null,
                'l_80' => isset($ta->l_80_inicial) && isset($ta->l_80_incremental) ? $ta->l_80_inicial + ($ta->l_80_incremental * $factor) : null,
                'tabla_acopiadora_id' => $ta->id
            ]);
            $factor++;
        }
    }
}
