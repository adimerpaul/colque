<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdatePassRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }


    public function perfil()
    {
        $id = auth()->user()->id;
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User no encontrado');

            return redirect(route('users.index'));
        }

        $personal = $user->personal;
        return view('users.show', compact('user', 'personal'));
    }

    public function editPass()
    {
        return view('users.edit_pass');
    }

    public function updatePass(UpdatePassRequest $request)
    {
        $clave = $request->clave;
        $user = User::findOrFail(auth()->user()->id);

        if (auth()->attempt(['email' => $user->email, 'password' => $clave])) {
            $input =$request->all();
            $input['ultimo_cambio_password'] = date('Y-m-d');
            $input['password'] = bcrypt($request->password);
            $user->fill($input);
            $user->save();
            Flash::success('Password modificado correctamente.');

            return redirect()->route('users.perfil');
        } else {
            Flash::error('El password actual no es el correcto');
            return redirect()->route('users.editPass');
        }

    }
}
