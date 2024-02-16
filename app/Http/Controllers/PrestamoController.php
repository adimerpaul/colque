<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Models\CuentaCobrar;
use App\Http\Controllers\AppBaseController;
use App\Models\Cliente;
use App\Models\FormularioLiquidacion;
use App\Models\Historial;
use App\Models\Movimiento;
use App\Models\PagoMovimiento;
use App\Models\Prestamo;
use App\Patrones\AccionHistorialCuenta;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Flash;
use DB;
use Response;
use Luecano\NumeroALetras\NumeroALetras;

class PrestamoController extends AppBaseController
{
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 weeks'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $esCancelado = $request->txtEstado;

        if (is_null($esCancelado)) {
            $esCancelado = false;
        }

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $prestamos = Prestamo::
        where('es_cancelado', $esCancelado)
            ->where(function ($q) use ($txtBuscar) {
                $q->where(\DB::raw("concat(monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                    ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                        $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                            ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                            ->orWhereHas('cooperativa', function ($q) use ($txtBuscar) {
                                $q->where('razon_social', 'ilike', "%{$txtBuscar}%");
                            });
                    });
            })
            ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
            ->whereAprobado(true)
            ->orderByDesc('updated_at')
            ->paginate(100);
        return view('prestamos.index')->with('prestamos', $prestamos)->with('esCancelado', $esCancelado);
    }

    public function create()
    {
        return view('prestamos.create');
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();
            //if($request->monto<=2000.00)
                $input['aprobado'] = false;
            $input['user_id'] = auth()->user()->id;
            $prestamo = Prestamo::create($input);

            \DB::commit();

            Flash::success('Préstamo guardado correctamente.');
            return redirect(route('prestamos.create'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function imprimir($prestamoId)
    {
        $prestamo = PagoMovimiento::whereOrigenType(Prestamo::class)->whereOrigenId($prestamoId)->first();
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($prestamo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $prest = Prestamo::find($prestamoId);
        $vistaurl = "prestamos.imprimir";
        $view = \View::make($vistaurl, compact('prestamo', 'literal', 'prest'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPrestamo-' . $prestamo->codigo . '.pdf');
    }

    public function getMensajeFormulario($id)
    {
        $mensaje = 'DEVOLUCIÓN POR PRESTAMO: ';
        $cuentas = CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereClase(ClaseCuentaCobrar::Prestamo)->get();

        foreach ($cuentas as $cuenta) {
            $string = $cuenta->motivo;
            if (strpos($string, 'CE0') !== false) {
                $index = strpos($string, 'CE0') + strlen('CE0');
                $mensaje = $mensaje . 'CE0' . strtok(substr($string, $index), ' ') . ', ';
            }
        }
        return substr($mensaje, 0, -2);
    }

    public function dividir(Request $request)
    {
        \DB::beginTransaction();
        try {
            $id = $request->idCuenta;
            $cuenta = CuentaCobrar::find($id);
            $formulario = FormularioLiquidacion::find($cuenta->origen->id);

            $montoOriginal = $cuenta->monto;

            if ($montoOriginal < $request->monto) {
                \DB::rollBack();

                Flash::error('El monto nuevo no puede ser mayor al monto original');

                return redirect(route('formularioLiquidacions.edit', [$formulario]));
            }
            CuentaCobrar::whereId($id)->whereEsCancelado(false)->update(['monto' => $request->monto]);

            $input['monto'] = $montoOriginal - $request->monto;
            $input['motivo'] = $cuenta->motivo;
            $input['tipo'] = 'Ingreso';
            $input['origen_id'] = $formulario->cliente_id;
            $input['origen_type'] = Cliente::class;
            $input['clase'] = $cuenta->clase;

            $tipo = FormularioLiquidacion::class;
            if ($cuenta->clase == ClaseCuentaCobrar::Prestamo){
                $tipo = Prestamo::class;
                $input['prestamo_id'] = $cuenta->id_inicio;
            }
            else
                $input['formulario_liquidacion_id'] = $cuenta->id_inicio;

            CuentaCobrar::create($input);

            $objCuenta = new CuentaCobrarController();
            $objCuenta->registrarHistorialCuenta(AccionHistorialCuenta::DivisionCuenta,
                "Cuenta por cobrar dividida, del monto " . $montoOriginal .
                " al monto " . $request->monto, $cuenta->id_inicio, $tipo);

            $formulario->update(['total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);

            event(new AccionCompleta("Cuenta Dividida", "Cuenta por cobrar dividida, del monto " . $montoOriginal .
                " al monto " . $request->monto, $formulario->id));
            \DB::commit();

            Flash::success('Préstamo dividido correctamente.');

            return redirect(route('formularioLiquidacions.edit', [$formulario]));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }

    }

    public function show($id)
    {
        $prestamo = Prestamo::whereId($id)->whereAprobado(false)->first();

        if (empty($prestamo)) {
            Flash::error('Préstamo no encontrado');

            return redirect(route('prestamos.index'));
        }

        return view('prestamos.aprobar')->with('prestamo', $prestamo);
    }

    public function aprobar(Request $request)
    {
        $id = $request->id;
        $prestamo = Prestamo::whereId($id)->whereAprobado(false)->first();

        if (empty($prestamo)) {
            Flash::error('Préstamo no encontrado');

            return redirect(route('prestamos.index'));
        }
        $prestamo->update(['aprobado' => true, 'aprobado_id' => auth()->user()->id]);
        Flash::success('Préstamo aprobado');

        return redirect(route('prestamos.index'));
    }

    public function registrarPago(Request $request)
    {
        \DB::beginTransaction();
        try {

            $input = $request->all();
            $id = $request->idPrestamo;
            $prestamo = Prestamo::find($id);
            $prestamo->update(['es_cancelado' => true]);

            $obj = new MovimientoController();
            $input['codigo'] = $obj->getCodigo('Egreso');
            if ($request->metodo == TipoPago::CuentaBancaria){
                $input['banco'] = $request->banco;
                $input['glosa'] = 'PRÉSTAMO DE DINERO A '.$prestamo->cliente->nombre.' en transferencia bancaria con recibo ' . $request->numero_recibo;
            }
            else{
                $input['glosa'] = 'PRÉSTAMO DE DINERO A '.$prestamo->cliente->nombre;
                $input['banco'] = null;

            }

            $input['origen_id'] = $id;
            $input['es_cancelado'] = true;
            $input['origen_type'] = Prestamo::class;
            $input['metodo'] = $request->metodo;
            $input['anio'] = date('y');
            if(date('m')>=10)
                $input['anio'] =$input['anio'] +1;

            $objMov= new MovimientoController();
            $input['numero'] = $objMov->proximoOrden();
            $pago = PagoMovimiento::create($input);

            $input['es_cancelado'] = false;
            $input['motivo'] = 'CUENTA POR COBRAR POR PRÉSTAMO DE DINERO, CLIENTE: ' . $prestamo->cliente->nombre.', COMPROBANTE: ' . $prestamo->codigo_caja;
            $input['tipo'] = 'Ingreso';
            $input['origen_id'] = $prestamo->cliente_id;
            $input['origen_type'] = Cliente::class;
            $input['clase'] = ClaseCuentaCobrar::Prestamo;
            $input['prestamo_id'] = $id;
            CuentaCobrar::create($input);

            $objCuenta = new CuentaCobrarController();
            $objCuenta->registrarHistorialCuenta(AccionHistorialCuenta::CreacionPrestamo,
                "Creación de cuenta por cobrar por préstamo con comprobante " . $prestamo->codigo_caja, $prestamo->id, Prestamo::class);


            \DB::commit();

            echo "<script>
            window.location.href = '/prestamos';
            window.open('/prestamos/'+'$prestamo->id'+'/imprimir', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function getPrestamosEmitidos(Request $request)
    {
        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 6 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;
        $fechaFinal=$fecha_final;
        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $prestamos = Prestamo::
        where('es_cancelado', true)
            ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
            ->orderByDesc('updated_at')
            ->paginate(100);
        return view('prestamos.reporte_emitidos')->with('prestamos', $prestamos)->with('fechaInicial', $fecha_inicial)->with('fechaFinal', $fechaFinal);
    }
    public function rechazar($id)
    {
        $prestamo = Prestamo::whereId($id)->whereAprobado(false)->first();

        if (empty($prestamo)) {
            Flash::error('Préstamo no encontrado');

            return redirect(route('home'));
        }
        $prestamo->delete($id);
        Flash::success('Préstamo rechazado');
        return redirect(route('home'));
    }
}
