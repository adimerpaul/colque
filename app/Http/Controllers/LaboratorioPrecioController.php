<?php

namespace App\Http\Controllers;

//use App\Http\Requests\UpdateCampoReporteRequest;
use App\Models\LaboratorioPrecio;
use App\Http\Controllers\AppBaseController;
use App\Models\LaboratorioQuimico;
use App\Models\Producto;
use Illuminate\Http\Request;
use Flash;
use Response;

class LaboratorioPrecioController extends AppBaseController
{
    public function index(Request $request)
    {
        $laboratorioId = $request->laboratorioId;
        $precios = LaboratorioPrecio::whereLaboratorioQuimicoId($laboratorioId)->with('producto')->orderBy('id')->get();

        return $precios;
    }

    public function getPorLaboratorioProducto($laboratorioId, $productoLetra)
    {
        $producto = Producto::whereLetra($productoLetra)->first();

        $precio = LaboratorioPrecio::whereLaboratorioQuimicoId($laboratorioId)->whereProductoId($producto->id)->first();

        return $precio->monto;
    }

    public function getPrecios($laboratorioId)
    {
        $lab = LaboratorioQuimico::whereProveedorId($laboratorioId)->first();
        try {
            $precios = LaboratorioPrecio::whereLaboratorioQuimicoId($lab->id)->orderBy('producto_id')->get();

            return $precios;
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    /**
     * Display a listing of the CampoReporte.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function edit($laboratorioId)
    {
        $laboratorio = LaboratorioQuimico::find($laboratorioId);
        return view('laboratorio_quimicos.precios')
            ->with('laboratorio', $laboratorio);
    }

    /**
     * Update the specified CampoReporte in storage.
     *
     * @param int $id
     * @param UpdateCampoReporteRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $precio = LaboratorioPrecio::find($id);
        if (empty($precio)) {
            Flash::error('Precio de laboratorio no encontrado');

            return redirect(route('laboratorio_quimicos.index'));
        }
        $precio->fill($request->all());
        $precio->save();

        return response()->json(['res' => true, 'message' => 'Precio guardado correctamente.']);
    }
}
