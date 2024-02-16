<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Comprador;
use App\Models\Cooperativa;
use App\Models\Proveedor;
use App\Models\PuntoCliente;
use App\Patrones\TipoCliente;
use App\Repositories\ClienteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Response;

class ClienteController extends AppBaseController
{
    /** @var  ClienteRepository */
    private $clienteRepository;

    public function __construct(ClienteRepository $clienteRepo)
    {
        $this->clienteRepository = $clienteRepo;
    }

    /**
     * Display a listing of the Cliente.
     *
     * @param Request $request
     *
     * @return Response
     */

    public function lista($id, Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $clientes = Cliente::whereCooperativaId($id)->whereEsAprobado(true)
            ->where(function ($q) use ($txtBuscar) {
                $q->where('nombre', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('nit', 'ilike', '%' . $txtBuscar . '%')
                    ->orWhere('celular', 'ilike', '%' . $txtBuscar . '%');
            })
            ->orderBy('nombre')->paginate(100);
        $cooperativa = Cooperativa::whereId($id)->first();
        return view('clientes.index')
            ->with('clientes', $clientes)->with('cooperativa', $cooperativa);
    }

    public function register($id)
    {

        return view('clientes.create')->with('id', $id);
    }


    /**
     * Store a newly created Cliente in storage.
     *
     * @param CreateClienteRequest $request
     *
     * @return Response
     */
    public function store(CreateClienteRequest $request)
    {
        $input = $request->all();
        $input['password'] = $request->nit;

        // MOMENTANEO
        $input['es_aprobado'] = true;
        if (isset($input['foto_input']))
            if(!$request->esFormulario)
                $input['firma'] = $this->subirArchivo($input);
            else
                $input['firma'] = $this->subirArchivoModal($input);
        else
            $input['firma'] = 'blanco.png';
        if(is_null($request->es_asociado) and $request->cooperativa_id==2)
            $input['es_asociado'] = false;

        if($request->cooperativa_id==44)
            $input['firma'] = '1699280941.jpg';//'1676485974.jpg';

        $cliente = $this->clienteRepository->create($input);

        if ($request->esFormulario) {
            return response()->json(['res' => true, 'message' => 'Cliente guardado correctamente.']);
        } else {

            Flash::success('Cliente guardado correctamente.');

            return redirect(route('clientes.lista', [$cliente->cooperativa_id]));

        }

    }

    private function subirArchivo($input)
    {
        $file = $input['foto_input'];

        $nombreArchivo = time().'.'.$file->getClientOriginalExtension();

        $file->move(public_path('firmas'), $nombreArchivo);
        return $nombreArchivo;
    }

    private function subirArchivoModal($input)
    {
        $imagen = $input['foto_input'];
        $imagen = str_replace('data:image/png;base64,', '', $imagen);
        $imagen = str_replace(' ', '+', $imagen);
        $nombreArchivo = time().'.png';
        \File::put(public_path(). '/firmas/' . $nombreArchivo, base64_decode($imagen));
        return $nombreArchivo;
    }

    /**
     * Show the form for editing the specified Cliente.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $storage = storage_path();
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('clientes.index'));
        }

        return view('clientes.edit')->with('cliente', $cliente)->with('id', $cliente->cooperativa_id)->with('storage', $storage);
    }

    /**
     * Update the specified Cliente in storage.
     *
     * @param int $id
     * @param UpdateClienteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClienteRequest $request)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('cooperativas.index'));
        }

        if (!$cliente->es_editable) {
            Flash::error('El Cliente ya fue editado anteriormente');

            return redirect(route('cooperativas.index'));
        }

        $clienteCi = Cliente::whereNit($request->nit)->where('id', '<>', $cliente->id)->count();

        if ($clienteCi>0) {
            Flash::error('Ya existe un carnet con otro registro');

            return redirect()
                ->route('clientes.edit', ['cliente' => $cliente->id]);
        }

        $input = $request->all();
        if (isset($input['foto_input']))
            $input['firma'] = $this->subirArchivo($input);

        if(is_null($request->es_asociado) and $request->cooperativa_id==2)
            $input['es_asociado'] = false;

        $cliente = $this->clienteRepository->update($input, $id);

        Flash::success('Cliente modificado correctamente.');

        return redirect(route('clientes.lista', [$cliente->cooperativa_id]));

    }

    public function cambiarEstado($id, $estado)
    {
        $cliente = Cliente::find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('clientes.lista', [$cliente->cooperativa_id]));
        }

        $cliente->update(['alta'=>$estado]);

        Flash::success('Estado cambiado correctamente.');

        return redirect(route('clientes.lista', [$cliente->cooperativa_id]));

    }

    public function getClientes()
    {
        $clientes = Cliente::orderBy('nombre')->whereEsAprobado(true)->whereAlta(true)->get()->pluck('info_cooperativa', 'id');
        return json_encode($clientes);
    }

    public function getClientesAndroid()
    {
        $clientes = DB::table('cooperativa')
            ->join('cliente', 'cooperativa.id', '=', 'cliente.cooperativa_id')
            ->where('cliente.alta', true)
            ->where('cliente.es_aprobado',true)
            ->select(DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as info_cooperativa"), "cliente.id")
            ->orderBy('cliente.nombre')
            ->get();
        //$clientes = Cliente::orderBy('nombre')->select('id','nit', 'nombre', 'cooperativa_id')->get();
        return ($clientes);
    }

    public function destroy($id)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Cliente no encontrado');

            return redirect(route('cooperativas.index'));
        }

        if (!$cliente->puede_eliminarse) {
            Flash::error('No es posible realizar esta acción');

            return redirect(route('clientes.lista', [$cliente->cooperativa_id]));
        }

        $this->clienteRepository->delete($id);

        Flash::success('Cliente eliminado correctamente.');

        return redirect(route('clientes.lista', [$cliente->cooperativa_id]));
    }

    public function createPuntos($id)
    {
        $cliente = Cliente::find($id);
        $puntos = PuntoCliente::whereClienteId($id)->orderByDesc('id')->paginate(50);
        return view('clientes.puntos')->with('puntos', $puntos)->with('cliente', $cliente);
    }


    public function canjearPuntos(Request $request)
    {
        $input = $request->all();

        $input['valor']=$request->valor*-1;
        $punto = PuntoCliente::create($input);

        Flash::success('Canje guardado correctamente.');
        return redirect(route('clientes.lista', [$punto->cliente->cooperativa_id]));

    }

    public function aprobar($id, Request $request)
    {
        $tipo = $request->tipo;

        switch ($tipo) {
            case TipoCliente::CLIENTE:
                Cliente::whereId($id)->update(['es_aprobado'=>true]);
                break;
            case TipoCliente::PROVEEDOR:
                Proveedor::whereId($id)->update(['es_aprobado'=>true]);
                break;
            case TipoCliente::COMPRADOR:
                Comprador::whereId($id)->update(['es_aprobado'=>true]);
                break;
            case TipoCliente::COOPERATIVA:
                Cooperativa::whereId($id)->update(['es_aprobado'=>true]);
                break;
        }
        Flash::success('Aprobación exitosa');

        return redirect(route('home'));
    }

    public function getCLientesRegistrados(Request $request){

//        $txtBuscar = $request->txtBuscar;
//        if (is_null($txtBuscar))
//            $txtBuscar = '';
//            $parametro = $request->parametro;
            $clientes =
                \DB::select("
                select  valores_nuevos, h1.registrado_id, c1.nombre, c1.nit, c1.celular, c1.created_at, personal.nombre_completo, c1.id
                 FROM
                cliente as c1
                inner join historial_cliente as h1 ON c1.id = h1.cliente_id
                inner join formulario_liquidacion ON c1.id = formulario_liquidacion.cliente_id
                inner join users  ON h1.registrado_id = users.id
                inner join personal  ON users.personal_id= personal.id

                where h1.tipo='Registro' and c1.firma <> 'blanco.png' and ((select count (*) from historial_cliente as h2 where h2.cliente_id = h1.cliente_id)>0)
                group by h1.valores_antiguos, valores_nuevos, h1.tipo, h1.registrado_id, h1.cliente_id, c1.nombre, c1.nit, c1.celular, c1.id, c1.created_at, personal.nombre_completo

                order by c1.created_at desc
                ");
            $clientes= $this->arrayPaginator($clientes, $request);
        return view('clientes.lista-registrados')
            ->with('clientes', $clientes);

    }

    public function getClientesEditados(Request $request){

//        $txtBuscar = $request->txtBuscar;
//        if (is_null($txtBuscar))
//            $txtBuscar = '';
//            $parametro = $request->parametro;
        $clientes =
            \DB::select("
                select valores_antiguos, valores_nuevos, h1.registrado_id, c1.nombre, c1.nit, c1.celular, c1.updated_at, personal.nombre_completo, c1.id
                 FROM
                cliente as c1
                inner join historial_cliente as h1 ON c1.id = h1.cliente_id
                inner join formulario_liquidacion ON c1.id = formulario_liquidacion.cliente_id
                inner join users  ON h1.registrado_id = users.id
                inner join personal  ON users.personal_id= personal.id

                where h1.tipo='Edición' and c1.firma <> 'blanco.png' and  h1.valores_antiguos <> h1.valores_nuevos
                group by h1.cliente_id, h1.valores_antiguos, valores_nuevos, h1.registrado_id, c1.nombre, c1.nit, c1.id, c1.celular, c1.updated_at, h1.valores_antiguos, personal.nombre_completo

                order by c1.updated_at desc
                                ");
        $clientes= $this->arrayPaginator($clientes, $request);
        return view('clientes.lista-editados')
            ->with('clientes', $clientes);

    }
    public function arrayPaginator($array, $request)
    {
        $page = $request->page;
        $perPage = 100;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, false), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
