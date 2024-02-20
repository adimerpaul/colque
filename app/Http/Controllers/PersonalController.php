<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use App\Models\Empresa;
use App\Models\Personal;
use App\Repositories\PersonalRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;
class PersonalController extends AppBaseController
{
    /** @var  PersonalRepository */
    private $personalRepository;

    public function __construct(PersonalRepository $personalRepo)
    {
        $this->personalRepository = $personalRepo;
    }

    public function index(Request $request)
    {
        $personals = $this->personalRepository->all();

        return view('personals.index')
            ->with('personals', $personals);
    }


    public function create(Request $request)
    {
        $empresa = Empresa::find($request->empresa);
        return view('personals.create', compact('empresa'));
    }

    public function store(CreatePersonalRequest $request)
    {
        $input = $request->all();

        if (isset($input['foto_input']))
            $input['firma'] = $this->subirArchivo($input);
        else
            $input['firma'] = 'blanco.png';

        $personal = $this->personalRepository->create($input);
        $input['password'] = Hash::make($input['password']);
        $personal->user()->create($input);

        Flash::success('Personal guardado correctamente.');

        return redirect(route('empresas.show', [$personal->empresa_id]));
    }

    private function subirArchivo($input)
    {
        $file = $input['foto_input'];

        if (is_null($file)) {
            Flash::error('Elija imágenes validas. (*.jpg | *.jpeg | *.png)');
            return redirect(route('users.show'));
        }
        $nombreArchivo = time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('firmas'), $nombreArchivo);
        return $nombreArchivo;
    }

    public function edit($id, Request $request)
    {
        $empresa = Empresa::find($request->empresa);
        $personal = $this->personalRepository->find($id);

        if (empty($personal)) {
            Flash::error('Personal no encontrado');

            return redirect(route('personals.index'));
        }

        return view('personals.edit', compact('personal', 'empresa'));
    }

    public function update($id, UpdatePersonalRequest $request)
    {
        $personal = $this->personalRepository->find($id);

        if (empty($personal)) {
            Flash::error('Personal no encontrado');

            return redirect(route('personals.index'));
        }

        $input = $request->all();

        if (isset($input['foto_input']))
            $input['firma'] = $this->subirArchivo($input);
        else
            $input['firma'] = 'blanco.png';
        $personal->update($input);
        $input['password'] = Hash::make($input['password']);
        $personal->user->update($input);

        Flash::success('Personal modificado correctamente.');

        return redirect(route('empresas.show', [$personal->empresa_id]));

    }

    public function destroy($id)
    {
        $personal = $this->personalRepository->find($id);
        $user = $personal->user;

        if (empty($personal)) {
            Flash::error('Personal no encontrado');

            return redirect(route('personals.index'));
        }

        $user->alta = !$user->alta;
        $user->save();

        Flash::success('Personal dado de ' . ($user->alta ? 'Alta' : 'Baja') . ' correctamente.');

        return redirect(route('empresas.show', [$personal->empresa_id]));
    }

    public function getPersonal(){
        $personals = Personal::
        join('users', 'users.personal_id', '=', 'personal.id')
            ->orderBy('nombre_completo')
            ->select(DB::raw("concat(ci, ' | ',nombre_completo)   as info"), "users.id")
            ->get();
        return $personals;
    }
}
