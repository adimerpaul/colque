<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeyRequest;
use App\Http\Requests\UpdateLeyRequest;
use App\Models\Ley;
use App\Models\Material;
use App\Repositories\LeyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeyController extends AppBaseController
{
    /** @var  LeyRepository */
    private $leyRepository;

    public function __construct(LeyRepository $leyRepo)
    {
        $this->leyRepository = $leyRepo;
    }

    /**
     * Display a listing of the Ley.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function lista($id, Request $request)
    {
        $leys = Ley::whereMaterialId($id)->orderByDesc('id')->paginate(50);
        $material = Material::whereId($id)->first();
        return view('leys.index')
            ->with('leys', $leys)->with('material', $material);
    }

    /**
     * Show the form for creating a new Ley.
     *
     * @return Response
     */
    public function register($id)
    {
        return view('leys.create')->with('id', $id);
    }

    /**
     * Store a newly created Ley in storage.
     *
     * @param CreateLeyRequest $request
     *
     * @return Response
     */
    public function store(CreateLeyRequest $request)
    {
        $input = $request->all();

        $ley = $this->leyRepository->create($input);

        Flash::success('Ley guardada correctamente.');

        return redirect(route('leys.lista', $ley->material_id));

    }

    /**
     * Show the form for editing the specified Ley.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ley = $this->leyRepository->find($id);

        if (empty($ley)) {
            Flash::error('Ley no encontrado');

            return redirect(route('leys.index'));
        }

        return view('leys.edit')->with('ley', $ley);
    }

    /**
     * Update the specified Ley in storage.
     *
     * @param int $id
     * @param UpdateLeyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeyRequest $request)
    {
        $ley = $this->leyRepository->find($id);

        if (empty($ley)) {
            Flash::error('Ley no encontrada');

            return redirect(route('leys.index'));
        }

        $ley = $this->leyRepository->update($request->all(), $id);

        Flash::success('Ley modificada correctamente.');

        return redirect(route('leys.lista', $ley->material_id));
    }

    /**
     * Remove the specified Ley from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ley = $this->leyRepository->find($id);

        if (empty($ley)) {
            Flash::error('Ley no encontrada');

            return redirect(route('leys.index'));
        }

        $this->leyRepository->delete($id);

        Flash::success('Ley eliminada correctamente.');

        return redirect(route('leys.lista', $ley->material_id));

    }
}
