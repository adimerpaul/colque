<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Patrones\Rol;
use App\Repositories\EmpresaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Response;

class EmpresaController extends AppBaseController
{
    /** @var  EmpresaRepository */
    private $empresaRepository;

    public function __construct(EmpresaRepository $empresaRepo)
    {
        $this->empresaRepository = $empresaRepo;
    }

    /**
     * Display a listing of the Empresa.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $empresas = $this->empresaRepository->paginate(15);

        return view('empresas.index')
            ->with('empresas', $empresas);
    }

    /**
     * Show the form for creating a new Empresa.
     *
     * @return Response
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created Empresa in storage.
     *
     * @param CreateEmpresaRequest $request
     *
     * @return Response
     */
    public function store(CreateEmpresaRequest $request)
    {
        $input = $request->all();

        if(isset($input['foto_input']))
            $input['logo'] = $this->subirArchivo($input['foto_input'], false);
        else
            $input['logo'] = 'foto_base.png';

        if(isset($input['membrete_input']))
            $input['membrete'] = $this->subirArchivo($input['membrete_input'], true);
        else
            $input['membrete'] = 'foto_base.png';

        $empresa = $this->empresaRepository->create($input);


        Flash::success('Empresa guardada correctamente.');

        return redirect(route('empresas.show', $empresa->id));
    }

    /**
     * Display the specified Empresa.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (empty($empresa)) {
            Flash::error('Empresa no encontrada');

            return redirect(route('empresas.index'));
        }

        return view('empresas.show')->with('empresa', $empresa);
    }

    /**
     * Show the form for editing the specified Empresa.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (empty($empresa)) {
            Flash::error('Empresa no encontrada');

            return redirect(route('empresas.index'));
        }

        return view('empresas.edit')->with('empresa', $empresa);
    }

    /**
     * Update the specified Empresa in storage.
     *
     * @param int $id
     * @param UpdateEmpresaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmpresaRequest $request)
    {
        $empresa = $this->empresaRepository->find($id);

        if (empty($empresa)) {
            Flash::error('Empresa no encontrada');

            return redirect(route('empresas.index'));
        }

        $input = $request->all();
        if(isset($input['foto_input']))
            $input['logo'] = $this->subirArchivo($input['foto_input'], false);
        if(isset($input['membrete_input']))
            $input['membrete'] = $this->subirArchivo($input['membrete_input'], true);
        $empresa = $this->empresaRepository->update($input, $id);

        Flash::success('Empresa modificada correctamente.');

        return redirect(route('empresas.index'));
    }

    /**
     * Remove the specified Empresa from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (empty($empresa)) {
            Flash::error('Empresa no encontrada');

            return redirect(route('empresas.index'));
        }

        $this->empresaRepository->delete($id);

        Flash::success('Empresa eliminada correctamente.');

        return redirect(route('empresas.index'));
    }
    private function subirArchivo($file, $esMembrete)
    {
        if(is_null($file))
        {
            Flash::error('Elija imagenes validas. (*.jpg | *.jpeg | *.png)');
            return redirect(route('users.show'));
        }
        $nombreArchivo = time().'.'.$file->getClientOriginalExtension();
        if($esMembrete)
            $file->move(public_path('membretes'), $nombreArchivo);
        else
            $file->move(public_path('logos'), $nombreArchivo);
        return $nombreArchivo;
    }
}
