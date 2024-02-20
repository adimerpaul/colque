<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MaterialController extends AppBaseController
{
    /** @var  MaterialRepository */
    private $materialRepository;

    public function __construct(MaterialRepository $materialRepo)
    {
        $this->materialRepository = $materialRepo;
    }

    /**
     * Display a listing of the Material.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $materials = Material::orderBy('id')->paginate();

        return view('materials.index')
            ->with('materials', $materials);
    }

    /**
     * Show the form for creating a new Material.
     *
     * @return Response
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created Mineral in storage.
     *
     * @param CreateMaterialRequest $request
     *
     * @return Response
     */
    public function store(CreateMaterialRequest $request)
    {
        $input = $request->all();

        $material = $this->materialRepository->create($input);

        Flash::success('Mineral guardado correctamente.');

        return redirect(route('materials.index'));
    }

    /**
     * Show the form for editing the specified Material.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $material = $this->materialRepository->find($id);

        if (empty($material)) {
            Flash::error('Mineral no encontrado');

            return redirect(route('materials.index'));
        }

        return view('materials.edit')->with('material', $material);
    }

    /**
     * Update the specified Mineral in storage.
     *
     * @param int $id
     * @param UpdateMaterialRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMaterialRequest $request)
    {
        $material = $this->materialRepository->find($id);

        if (empty($material)) {
            Flash::error('Mineral no encontrado');

            return redirect(route('materials.index'));
        }

        $material = $this->materialRepository->update($request->all(), $id);

        Flash::success('Mineral modificado correctamente.');

        return redirect(route('materials.index'));
    }
}
