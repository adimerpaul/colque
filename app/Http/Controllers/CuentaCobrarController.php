<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Models\Anticipo;
use App\Models\CuentaCobrar;
use App\Http\Controllers\AppBaseController;
use App\Models\Cliente;
use App\Models\FormularioDescuento;
use App\Models\FormularioLiquidacion;
use App\Models\Historial;
use App\Models\HistorialCuentaCobrar;
use App\Models\Movimiento;
use App\Models\PagoMovimiento;
use App\Models\Prestamo;
use App\Patrones\AccionHistorialCuenta;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\Estado;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use DB;
use Response;
use Luecano\NumeroALetras\NumeroALetras;

class CuentaCobrarController extends AppBaseController
{
    public function index(Request $request)
    {
        $formulario_id = $request->formulario_id;
        $cuentas = CuentaCobrar::whereOrigenId($formulario_id)->whereOrigenType(FormularioLiquidacion::class)
            ->orderByDesc('id')->get();
        return $cuentas;
    }

    public function transferir(Request $request)
    {
        \DB::beginTransaction();
        try {
            $id = $request->idCuenta;
            $cuenta = CuentaCobrar::find($id);
            $formulario = FormularioLiquidacion::find($cuenta->origen->id);

//
            if($request->tipo=='Lote'){
                CuentaCobrar::whereId($id)->whereEsCancelado(false)->update(['origen_id' => $request->destino]);
                $formularioDestino = FormularioLiquidacion::find($request->destino);

                event(new AccionCompleta("Cuenta Transferida", "Cuenta por cobrar transferida, del lote " . $formulario->lote . " al lote " . $formularioDestino->lote, $formulario->id));
                event(new AccionCompleta("Cuenta Transferida", "Cuenta por cobrar transferida, del lote " . $formulario->lote . " al lote " . $formularioDestino->lote, $request->destino));

                $tipo = FormularioLiquidacion::class;
                if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                    $tipo = Prestamo::class;
                $this->registrarHistorialCuenta(AccionHistorialCuenta::Transferencia, "Transferencia de cuenta por cobrar del lote  " . $formulario->lote . " al lote " . $formularioDestino->lote, $cuenta->id_inicio, $tipo);

                $this->actualizarSaldo($formularioDestino);
                $this->actualizarEnFormulario($formularioDestino->id);
            }
            else{
                CuentaCobrar::whereId($id)->whereEsCancelado(false)->update(['origen_id' => $request->cliente_destino, 'origen_type' => 'App\Models\Cliente']);
                $cliente = Cliente::find($request->cliente_destino);
                event(new AccionCompleta("Cuenta Transferida", "Cuenta por cobrar transferida, del lote " . $formulario->lote . " al cliente " . $cliente->nombre, $formulario->id));

                $tipo = FormularioLiquidacion::class;
                if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                    $tipo = Prestamo::class;
                $this->registrarHistorialCuenta(AccionHistorialCuenta::Transferencia, "Transferencia de cuenta por cobrar del lote  " . $formulario->lote . " al cliente " . $cliente->nombre, $cuenta->id_inicio, $tipo);

            }
//
            $this->actualizarEnFormulario($formulario->id);
            \DB::commit();

            Flash::success('Cuenta por cobrar transferida correctamente.');

            return redirect(route('formularioLiquidacions.edit', [$formulario]));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function actualizarSaldo($formulario)
    {
        if (round($formulario->totales['total_saldo_favor'], 2) != round(($formulario->saldo_favor), 2))
            FormularioLiquidacion::where('id', $formulario->id)->update(['saldo_favor' => $formulario->totales['total_saldo_favor']]);
    }

    public function getTotalFormulario($id, $clase)
    {
        if($clase==ClaseCuentaCobrar::SaldoNegativo)
            return CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereIn('clase', [ClaseCuentaCobrar::Retiro, ClaseCuentaCobrar::SaldoNegativo])->sum('monto');
        else
            return CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereClase($clase)->sum('monto');

    }

    public function getTotalPorClase($id, $clase)
    {
        return CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereClase($clase)->sum('monto');
    }

    public function getMensajeFormulario($id)
    {
        $mensaje = 'SALDO POR DEUDA: ';
        $cuentas = CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereIn('clase', [ClaseCuentaCobrar::SaldoNegativo, ClaseCuentaCobrar::Retiro] )->get();

        foreach ($cuentas as $cuenta) {
            $string = $cuenta->motivo;
            $index = strpos($string, 'CM') + strlen('CM');
            $mensaje = $mensaje . 'CM' . strtok(substr($string, $index), ' ') . ', ';
        }
        return substr($mensaje, 0, -2);
    }

    public function getCuentasClientes(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 3 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $esCancelado = $request->txtEstado;
        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }
        if ($esCancelado) {
            $cuentas = DB::table('cooperativa')
                ->join('cliente', 'cooperativa.id', '=', 'cliente.cooperativa_id')
                ->join('cuenta_cobrar', 'cliente.id', '=', 'cuenta_cobrar.origen_id')
                ->join('pago_movimiento', 'cuenta_cobrar.id', '=', 'pago_movimiento.origen_id')
                ->where('cuenta_cobrar.origen_type', Cliente::class)
                ->where('pago_movimiento.origen_type', CuentaCobrar::class)
                ->where('es_cancelado', true)
                ->whereBetween('pago_movimiento.created_at', [$fecha_inicial, $fecha_final])
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('cliente.nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('cliente.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('razon_social', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('codigo', 'ilike', "%{$txtBuscar}%");
                })
                ->select('cuenta_cobrar.id', 'cuenta_cobrar.updated_at', 'cuenta_cobrar.monto', 'motivo', 'nombre',
                    'codigo', 'cliente.nit', 'glosa', 'pago_movimiento.created_at', 'razon_social', 'pago_movimiento.alta')
                ->orderByDesc('cuenta_cobrar.id')
                ->paginate();
        } else {
            $cuentas = DB::table('cooperativa')
                ->join('cliente', 'cooperativa.id', '=', 'cliente.cooperativa_id')
                ->join('cuenta_cobrar', 'cliente.id', '=', 'cuenta_cobrar.origen_id')
                ->where('cuenta_cobrar.origen_type', Cliente::class)
                ->where('es_cancelado', false)
                ->whereBetween('cuenta_cobrar.updated_at', [$fecha_inicial, $fecha_final])
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('cliente.nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('cliente.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('razon_social', 'ilike', "%{$txtBuscar}%");
                })
                ->select('cuenta_cobrar.id', 'cuenta_cobrar.updated_at', 'cuenta_cobrar.monto', 'motivo', 'nombre',
                    'cliente.nit', 'motivo', 'razon_social')
                ->orderByDesc('cuenta_cobrar.id')
                ->paginate();
        }
        return view('cuentas_cobrar.caja_cuentas')->with('cuentas', $cuentas)->with('esCancelado', $esCancelado);
    }

    public function registrarCuenta(Request $request)
    {
        $cuentaId = $request->idCuenta;
        $cuenta = CuentaCobrar::find($cuentaId);
        if ($cuenta->es_cancelado) {
            Flash::error('La cuenta ya fue pagada anteriormente.');

            return redirect(route('pagos.cuentas'));
        }
        CuentaCobrar::where('id', $cuentaId)->update(['es_cancelado' => true]);
        $obj = new MovimientoController();

        $campos['monto'] = $cuenta->monto;
        if ($request->metodo == TipoPago::CuentaBancaria) {
            $campos['banco'] = $request->banco;
            $campos['glosa'] = 'Pago de ' . $cuenta->motivo . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
        } else
            $campos['glosa'] = 'Pago de ' . $cuenta->motivo;
        $campos['origen_type'] = CuentaCobrar::class;
        $campos['origen_id'] = $cuentaId;
        $campos['metodo'] = $request->metodo;
        $campos['codigo'] = $obj->getCodigo('Ingreso');
        $campos['anio'] = date('y');
        if (date('m') >= 10)
            $campos['anio'] = $campos['anio'] + 1;

        $objMov= new MovimientoController();
        $campos['numero'] = $objMov->proximoOrden();
        $pago = PagoMovimiento::create($campos);
//        PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);

        Flash::success('Cuenta pagada correctamente.');

        echo "<script>
            window.location.href = '/pagos-cuentas-cobrar';
            window.open('/cuentas-cobrar/'+'$cuentaId'+'/imprimir', '_blank');
                </script>";
    }

    public function imprimir($cuentaId)
    {
        $cuenta = PagoMovimiento::whereOrigenType(CuentaCobrar::class)->whereOrigenId($cuentaId)->orderByDesc('id')->first();
//        $cuenta = CuentaCobrar::find($cuentaId);
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($cuenta->monto, 2, 'BOLIVIANOS', 'CENTAVOS');


        $vistaurl = "cuentas_cobrar.imprimir";
        $view = \View::make($vistaurl, compact('cuenta', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboCuentaPorCobrar-' . $cuenta->codigo . '.pdf');
    }

    public function puedeBorrarseCliente($id)
    {
        $sePuede = true;
        $form = FormularioLiquidacion::whereClienteId($id)->count();
        $cuenta = CuentaCobrar::whereOrigenId($id)->whereOrigenType(Cliente::class)->count();
        $prestamo = Prestamo::whereClienteId($id)->count();
        if ($form > 0 or $cuenta > 0 or $prestamo > 0)
            $sePuede = false;

        return $sePuede;
    }

    public function puedeDarBajaCliente($id)
    {
        $sePuede = true;
        $cuenta = CuentaCobrar::whereOrigenId($id)->whereOrigenType(Cliente::class)->count();
        $prestamo = Prestamo::whereClienteId($id)->count();
        if ($cuenta > 0 or $prestamo > 0)
            $sePuede = false;

        return $sePuede;
    }

    public function esDeudor($id)
    {
        $esDeudor = false;
        $cuenta = CuentaCobrar::whereOrigenId($id)->whereOrigenType(Cliente::class)->whereEsCancelado(false)->count();
        $lote = FormularioLiquidacion::whereClienteId($id)
            ->where('total_cuenta_cobrar', '<>', 0.00)
            ->whereEsCancelado(false)->whereIn('estado',[Estado::EnProceso, Estado::Anulado])->count();
        if ($cuenta > 0 or $lote > 0)
            $esDeudor = true;

        return $esDeudor;
    }

    public function getCuentasCobrarCliente($clienteId)
    {
        return CuentaCobrar::whereOrigenId($clienteId)->whereOrigenType(Cliente::class)->get();

    }


    private function transferirAFormulario($idCuenta, $idFormulario)
    {

        $cuenta = CuentaCobrar::whereId($idCuenta)->whereEsCancelado(false)->first();

        $cuenta->update(['origen_id' => $idFormulario, 'origen_type' => FormularioLiquidacion::class]);

        $this->actualizarEnFormulario($idFormulario);

        $formulario = FormularioLiquidacion::find($idFormulario);
        $this->actualizarSaldo($formulario);

        $tipo = FormularioLiquidacion::class;
        if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
            $tipo = Prestamo::class;
        $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " . $formulario->lote . " monto " . $cuenta->monto, $cuenta->id_inicio, $tipo);

        event(new AccionCompleta("Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto, $idFormulario));
    }

    public function transferirDeClienteAFormulario(Request $request)
    {
        \DB::beginTransaction();
        try {
            $idCuenta = $request->cuenta_id;
            $idFormulario = $request->formulario_liquidacion_id;

            $this->transferirAFormulario($idCuenta, $idFormulario);

            \DB::commit();
//        $formulario=FormularioLiquidacion::find($idFormulario);
//        $this->actualizarSaldo($formulario);

            return response()->json(['res' => true, 'message' => 'Cuenta por cobrar agregada correctamente']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function transferirDePendienteAFormulario(Request $request)
    {
        \DB::beginTransaction();
        try {
            $idCuenta = $request->idCuenta;
            $idFormulario = $request->destino;

            $this->transferirAFormulario($idCuenta, $idFormulario);

            \DB::commit();
//        $formulario=FormularioLiquidacion::find($idFormulario);
//        $this->actualizarSaldo($formulario);

            Flash::success('Cuenta transferida correctamente');
            return redirect()
                ->route('cuentas-cobrar-pendientes');

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function actualizarEnFormulario($formId)
    {
        $formulario = FormularioLiquidacion::find($formId);
        $formulario->update(['total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
    }

    public function getPendientesDePago(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $estado = $request->txtEstado;

        $cuentas = CuentaCobrar::
        where('cuenta_cobrar.es_cancelado', false)
            ->where('monto', '<>', 0.00)
            ->where('motivo', 'ilike', "%{$txtBuscar}%")
            ->where('clase', 'ilike', "%{$estado}%")
            ->orderByDesc('cuenta_cobrar.id')->paginate(100);




        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 365 days'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $anticipos = Anticipo::
        whereBetween('created_at', [$fecha_inicial, $fecha_final])
            ->whereHas('formularioLiquidacion', function ($q) {
                $q->whereEstado(Estado::EnProceso)
                ->orWhere(function ($q) {
                    $q->where('estado',Estado::Anulado)->where('es_retirado',false);
                });
            })
            ->where('id', '<>', 2777)//cuenta creada por lote anulado
            ->whereEsCancelado(true)
           // ->whereNotIn('formulario_liquidacion_id', [2171, 2481, 3356])
            ->orderByDesc('created_at')
            ->paginate(100);
        //return view('anticipos.reporte_en_proceso')
            //->with('anticipos', $anticipos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final);




        return view('cuentas_cobrar.pendientes_pago')->with('cuentas', $cuentas)
            ->with('anticipos', $anticipos)->with('fecha_inicial', $fecha_inicial)->with('fecha_final', $fecha_final)
            ->with('estado', $estado);

    }

    public function registrarHistorialCuenta($accion, $observacion, $origen_id = null, $origen_type = null)
    {
        $req["accion"] = $accion;
        $req["observacion"] = $observacion;
        if ($origen_id != 0) {
            $req["origen_id"] = $origen_id;
            $req["origen_type"] = $origen_type;
        }
        $req["users_id"] = auth()->user()->id;
        HistorialCuentaCobrar::create($req);
    }

    public function getCuentasTotal(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $esCancelado = $request->txtEstado;
        if (is_null($esCancelado)) {
            $esCancelado = false;
        }

        $fechaInicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 3 months'));
        if (isset($request->fecha_inicial))
            $fechaInicial = $request->fecha_inicial;

        $fechaFinal = date('Y-m-d');
        if (isset($request->fecha_final))
            $fechaFinal = $request->fecha_final;

        $fechaFinal = date('Y-m-d', strtotime($fechaFinal . ' + 1 days'));

        $cuentas = CuentaCobrar::
        where(function ($q) use ($txtBuscar) {
            $q->where('monto', 'ilike', "%{$txtBuscar}%")
                ->orWhere('motivo', 'ilike', "%{$txtBuscar}%");
        })
            ->whereEsCancelado($esCancelado)
            ->whereBetween('updated_at', [$fechaInicial, $fechaFinal])
            ->orderByDesc('id')->paginate();
        return view('cuentas_cobrar.cuentas_total')->with('cuentas', $cuentas)->with('esCancelado', $esCancelado);

    }

    public function getHistorial($id)
    {
        $cuenta = CuentaCobrar::find($id);


        $origen = FormularioLiquidacion::class;
        if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
            $origen = Prestamo::class;
        $historial = HistorialCuentaCobrar::whereOrigenId($cuenta->id_inicio)->whereOrigenType($origen)->orderBy('id')->get();

        return view('cuentas_cobrar.historial')->with('historial', $historial);
    }

    public function agregarAntigua()
    {
        return view('cuentas_cobrar.create');
    }

    public function storeAntigua(Request $request){
        $input = $request->all();
        $input['origen_type'] = Cliente::class;
        $cliente = Cliente::find($request->origen_id);
        if ($request->clase == ClaseCuentaCobrar::Prestamo){
            $input['motivo'] = 'CUENTA POR COBRAR POR PRÉSTAMO DE DINERO, CLIENTE: ' .$cliente->nombre .', COMPROBANTE: ';
        }
        else{
            $input['motivo'] = 'CUENTAS POR COBRAR A ' .$cliente->nombre .' POR SALDO NEGATIVO NRO LOTE: ';
        }
        CuentaCobrar::create($input);

        Flash::success('Cuenta por cobrar creada correctamente.');

        return redirect(route('agregar-cuenta-antigua'));
    }
}
