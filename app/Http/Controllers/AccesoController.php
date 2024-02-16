<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccesoRequest;
use App\Http\Requests\UpdateAccesoRequest;
use App\Repositories\AccesoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccesoController extends AppBaseController
{
    /** @var  AccesoRepository */
    private $accesoRepository;

    public function __construct(AccesoRepository $accesoRepo)
    {
        $this->accesoRepository = $accesoRepo;
    }

    /**
     * Display a listing of the Acceso.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accesos = $this->accesoRepository->all();

        return view('accesos.index')
            ->with('accesos', $accesos);
    }

    /**
     * Show the form for creating a new Acceso.
     *
     * @return Response
     */
    public function create()
    {
        return view('accesos.create');
    }

    /**
     * Store a newly created Acceso in storage.
     *
     * @param CreateAccesoRequest $request
     *
     * @return Response
     */
    public function store(CreateAccesoRequest $request)
    {
        $input = $request->all();

        $acceso = $this->accesoRepository->create($input);

        Flash::success('Acceso guardado correctamente.');

        return redirect(route('accesos.index'));
    }

    /**
     * Display the specified Acceso.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $acceso = $this->accesoRepository->find($id);

        if (empty($acceso)) {
            Flash::error('Acceso no encontrado');

            return redirect(route('accesos.index'));
        }

        return view('accesos.show')->with('acceso', $acceso);
    }

    /**
     * Show the form for editing the specified Acceso.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $acceso = $this->accesoRepository->find($id);

        if (empty($acceso)) {
            Flash::error('Acceso no encontrado');

            return redirect(route('accesos.index'));
        }

        return view('accesos.edit')->with('acceso', $acceso);
    }

    /**
     * Update the specified Acceso in storage.
     *
     * @param int $id
     * @param UpdateAccesoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccesoRequest $request)
    {
        $acceso = $this->accesoRepository->find($id);

        if (empty($acceso)) {
            Flash::error('Acceso no encontrado');

            return redirect(route('accesos.index'));
        }

        $acceso = $this->accesoRepository->update($request->all(), $id);

        Flash::success('Acceso modificado correctamente.');

        return redirect(route('accesos.index'));
    }

    /**
     * Remove the specified Acceso from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $acceso = $this->accesoRepository->find($id);

        

        if (empty($acceso)) {
            Flash::error('Acceso no encontrado');

            return redirect(route('accesos.index'));
        }

        $this->accesoRepository->delete($id);

        Flash::success('Acceso eliminado correctamente.');

        return redirect(route('accesos.index'));
    }
}
