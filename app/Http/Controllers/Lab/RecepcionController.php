<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\DocumentosVentaController;
use App\Http\Controllers\LaboratorioEnsayoController;
use App\Models\Concentrado;
use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\Lab\Ensayo;
use App\Models\Lab\FactorVolumetrico;
use App\Models\Lab\PagoMovimiento;
use App\Models\Lab\PrecioElemento;
use App\Models\Lab\Recepcion;
use App\Models\Laboratorio;
use App\Models\Venta;
use App\Models\VentaFactura;
use App\Patrones\Estado;
use App\Patrones\EstadoLaboratorio;
use App\Patrones\FachadaLab;
use Illuminate\Http\Request;
use Flash;
use DB;
class RecepcionController extends AppBaseController
{
    public function inicio()
    {
        $obj = new AccidenteController();
        $obj->registrarSinAccidentes();
        return view('lab.recepcion.inicio');
    }

    public function create()
    {
        return view('lab.recepcion.create');
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();

            $cantidadTotal = $request->cantidadEstanio + $request->cantidadHumedad;
            if ($cantidadTotal == 0)
                return response()->json(['res' => false, 'message' => 'La cantidad de ensayos no puede ser 0']);

            $input["numero"] = $this->getNumero();
            $input["anio"] = date('y');
            $input["mes"] = date('m');
            $input["anticipo"] = $request->monto;
            $recepcion = Recepcion::create($input);
            $valor["recepcion_id"] = $recepcion->id;
            $precioEstanio = PrecioElemento::whereElementoId(1)->first();
            $precioHumedad = PrecioElemento::whereElementoId(2)->first();
            $precioPlata = PrecioElemento::whereElementoId(3)->first();
            $factor = FactorVolumetrico::orderByDesc('id')->first();
            if($recepcion->cliente_id==1){
//estaño
                $form=FormularioLiquidacion::
                leftJoin('laboratorio', 'formulario_liquidacion.id', 'laboratorio.formulario_liquidacion_id')
                ->whereLetra('D')->whereEstado(Estado::EnProceso)
                    ->where(DB::raw("DATE(formulario_liquidacion.created_at)"), DB::raw("CAST(CURRENT_TIMESTAMP AS DATE)"))
                    ->whereNull('laboratorio.ensayo_id')
                    ->where('laboratorio.mineral_id', 4)
                    ->where('laboratorio.origen', 'Empresa')
                    ->select("formulario_liquidacion.*")
                    ->orderBy('formulario_liquidacion.created_at')
                    ->get();

                for ($i = 0; $i < $request->cantidadEstanio; $i++) {
                    $valor["elemento_id"] = 1;
                    $valor["precio_unitario"] = $precioEstanio->monto;
                    $valor["factor_volumetrico"] = $factor->valor;

                    try{
                        $valor["origen_id"] = $form[$i]->id;
                        $valor["origen_type"] = FormularioLiquidacion::class;
                        $valor["lote"] = $form[$i]->lote;

                    }
                    catch (\Exception $e) {
                        $valor["origen_id"] = null;
                        $valor["origen_type"] = null;
                        $valor["lote"] = null;
                    }
                    $ensayo= Ensayo::create($valor);

                    try{

                        $labo = Laboratorio::whereOrigen('Empresa')->whereFormularioLiquidacionId($form[$i]->id)
                            ->whereMineralId(4)->first();
                        $labo->update(['ensayo_id' => $ensayo->id]);
                    }
                    catch (\Exception $e) {
                    }
                }

 //plata
                $form=FormularioLiquidacion::
                leftJoin('laboratorio', 'formulario_liquidacion.id', 'laboratorio.formulario_liquidacion_id')
                    ->whereIn('letra', ['A', 'B', 'C', 'E'])->whereEstado(Estado::EnProceso)
                    ->where(DB::raw("DATE(formulario_liquidacion.created_at)"), DB::raw("CAST(CURRENT_TIMESTAMP AS DATE)"))
                    ->whereNull('laboratorio.ensayo_id')
                    ->where('laboratorio.mineral_id', 1)
                    ->where('laboratorio.origen', 'Empresa')
                    ->select("formulario_liquidacion.*")
                    ->orderBy('formulario_liquidacion.created_at')
                    ->get();

                for ($i = 0; $i < $request->cantidadPlata; $i++) {
                    $valor["elemento_id"] = 3;
                    $valor["precio_unitario"] = $precioPlata->monto;

                    try{
                        $valor["origen_id"] = $form[$i]->id;
                        $valor["origen_type"] = FormularioLiquidacion::class;
                        $valor["lote"] = $form[$i]->lote;

                    }
                    catch (\Exception $e) {
                        $valor["origen_id"] = null;
                        $valor["origen_type"] = null;
                        $valor["lote"] = null;
                    }
                    $ensayo= Ensayo::create($valor);

                    try{

                        $labo = Laboratorio::whereOrigen('Empresa')->whereFormularioLiquidacionId($form[$i]->id)
                            ->whereMineralId(1)->first();
                        $labo->update(['ensayo_id' => $ensayo->id]);
                    }
                    catch (\Exception $e) {
                    }
                }
//humedad
                $cantidadEstanio=$request->cantidadEstanio;
                $cantidadPlata=$request->cantidadPlata;
                $form=FormularioLiquidacion::
                leftJoin('laboratorio', 'formulario_liquidacion.id', 'laboratorio.formulario_liquidacion_id')
                    ->whereEstado(Estado::EnProceso)
                    ->where(function ($q) use ($cantidadEstanio, $cantidadPlata) {
                        if ($cantidadEstanio>0) {
                            $q->where('letra', 'D');
                        }
                        elseif ($cantidadPlata>0) {
                            $q->whereIn('letra', ['A', 'B', 'C', 'E']);
                        }

                    })
                    ->where(DB::raw("DATE(formulario_liquidacion.created_at)"), DB::raw("CAST(CURRENT_TIMESTAMP AS DATE)"))
                    ->whereNull('laboratorio.ensayo_id')
                    ->whereNull('laboratorio.mineral_id')
                    ->where('laboratorio.origen', 'Empresa')
                    ->select("formulario_liquidacion.*")
                    ->orderBy('formulario_liquidacion.created_at')
                    ->get();
                for ($i = 0; $i < $request->cantidadHumedad; $i++) {
                    $valor["elemento_id"] = 2;
                    $valor["precio_unitario"] = $precioHumedad->monto;

                    try{
                        $valor["origen_id"] = $form[$i]->id;
                        $valor["origen_type"] = FormularioLiquidacion::class;
                        $valor["lote"] = $form[$i]->lote;
                    }
                   catch (\Exception $e) {
                       $valor["origen_id"] = null;
                       $valor["origen_type"] = null;
                       $valor["lote"] = null;
                   }
                    $ensayo= Ensayo::create($valor);

                    try{

                        $labo = Laboratorio::whereOrigen('Empresa')->whereFormularioLiquidacionId($form[$i]->id)
                            ->whereNull('mineral_id')->first();
                        $labo->update(['ensayo_id' => $ensayo->id]);
                    }
                    catch (\Exception $e) {
                    }

                }
            }
            else{
                for ($i = 0; $i < $request->cantidadEstanio; $i++) {
                    $valor["elemento_id"] = 1;
                    $valor["precio_unitario"] = $precioEstanio->monto;
                    $valor["factor_volumetrico"] = $factor->valor;
                    Ensayo::create($valor);
                }
                for ($i = 0; $i < $request->cantidadHumedad; $i++) {
                    $valor["elemento_id"] = 2;
                    $valor["precio_unitario"] = $precioHumedad->monto;
                    Ensayo::create($valor);
                }
                for ($i = 0; $i < $request->cantidadPlata; $i++) {
                    $valor["elemento_id"] = 3;
                    $valor["precio_unitario"] = $precioPlata->monto;
                    Ensayo::create($valor);
                }
            }

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Recepción registrada correctamente', 'codigo' => $recepcion->codigo_pedido_corto]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function getNumero()
    {
        $anio = date('y');
        $mes = date('m');

        $recepcion = Recepcion::where('mes', $mes)->where('anio', $anio)->max('numero');
        return ($recepcion + 1);
    }

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $pedidos = Recepcion::
        where(function ($q) use ($txtBuscar) {
            $q->whereRaw("(CONCAT('CL',numero,'-',mes, anio) ilike ?)", ["%{$txtBuscar}%"])
                ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                    $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('nit', 'ilike', "%{$txtBuscar}%");
                });
        })
            ->whereBetween('created_at', [$fecha_inicial, $fechaFin])
            ->whereAlta(true)
            ->orderByDesc('id')
            ->paginate(25);

        return view('lab.recepcion.index')
            ->with('pedidos', $pedidos);
    }

    public function getInformeColquechaca(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fechaFin = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $pedidos = Recepcion::
        where(function ($q) use ($txtBuscar) {
            $q->whereRaw("(CONCAT('CL',numero,'-',mes, anio) ilike ?)", ["%{$txtBuscar}%"])
               ;
        })
            ->whereBetween('created_at', [$fecha_inicial, $fechaFin])
            ->whereClienteId(1)
            ->whereAlta(true)
            ->orderByDesc('id')
            ->paginate(100);

        return view('lab.recepcion.informe_colquechaca')
            ->with('pedidos', $pedidos);
    }

    public function edit($id)
    {
        $pedido = Recepcion::find($id);
        if (empty($pedido)) {
            Flash::error('Pedido no encontrado');
            return redirect(route('lab.recepcion.index'));
        }
        $ensayos = Ensayo::whereRecepcionId($id)->orderBy('elemento_id')->orderBy('id')->get();

        $precioEstanio = FachadaLab::getPrecioEstanio();
        $estanio = Ensayo::whereElementoId(1)->whereRecepcionId($id)->first();
        if ($estanio)
            $precioEstanio = $estanio->precio_unitario;

        $precioPlata = FachadaLab::getPrecioPlata();
        $plata = Ensayo::whereElementoId(3)->whereRecepcionId($id)->first();
        if ($plata)
            $precioPlata = $plata->precio_unitario;

        $precioHumedad = FachadaLab::getPrecioHumedad();
        $humedad = Ensayo::whereElementoId(2)->whereRecepcionId($id)->first();
        if ($humedad)
            $precioHumedad = $humedad->precio_unitario;
        return view('lab.recepcion.edit', compact('pedido', 'ensayos', 'precioHumedad', 'precioEstanio', 'precioPlata'));

    }

    public function update($id, Request $request)
    {
        $recepcion = Recepcion::find($id);


        $recepcion->update($request->all());

        Flash::success('Registro modificado correctamente.');

        return redirect(route('recepcion-lab.edit', ['recepcion_lab' => $id]));
    }

    public function show($id)
    {
        $pedido = Recepcion::find($id);
        return response()->json(['res' => true, 'data' => $pedido]);
    }

    public function finalizarRecepcion($id)
    {
        $recepcion = Recepcion::find($id);
        $sinLote = Ensayo::whereRecepcionId($id)->whereNull('lote')->count();
        if ($sinLote > 0) {
            return response()->json(['res' => false, 'message' => 'No se puede finalizar porque no están registrados todos los lotes']);
        }
        $recepcion->update(['a_caja' => true, 'estado' => EstadoLaboratorio::EnProceso, 'fecha_aceptacion' => date('Y-m-d H:i:s'), 'codigo' => $this->getCodigo()]);
        if ($recepcion->monto_pagado == 0) { //enviado a caja con 0 o sin enviar a caja
            $recepcion = Recepcion::find($id);

            if ((floatval($recepcion->precio_total) - floatval($recepcion->anticipo)) == 0) {
                $glosa = "PAGO FINAL DE " . $recepcion->glosa;
            } else {
                $glosa = "ANTICIPO DE " . $recepcion->glosa;
            }

            $obj = new PagoMovimientoController();
            $obj->storeAnalisis($recepcion->anticipo, $id, $glosa);
        }

        return response()->json(['res' => true, 'message' => 'Recepción finalizada correctamente']);
    }

    public function enviarCaja(Request $request)
    {
        \DB::beginTransaction();
        try {
            $recepcion = Recepcion::find($request->id);
            if ($recepcion->a_caja)
                return response()->json(['res' => false, 'message' => 'ERROR!! Ya se envió a caja anteriormente']);
            $recepcion->update(['a_caja' => true, 'anticipo' =>$request->monto]);

            if ((floatval($recepcion->precio_total) - floatval($request->monto)) == 0) {
                $glosa = "PAGO FINAL DE " . $recepcion->glosa;
            } else {
                $glosa = "ANTICIPO DE " . $recepcion->glosa;
            }

            $obj = new PagoMovimientoController();
            $obj->storeAnalisis($request->monto, $request->id, $glosa);

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Pago generado correctamente']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function anular($id)
    {
        \DB::beginTransaction();
        try {
            $recepcion = Recepcion::find($id);

            $recepcion->update(['estado' => EstadoLaboratorio::Anulado, 'fecha_rechazo' => date('Y-m-d H:i:s')]);

            $ensayos = Ensayo::whereRecepcionId($id)->get();

            $seleccionados = [];
            foreach($ensayos as $ensayo){
                array_unshift($seleccionados, $ensayo->id);
            }
            Laboratorio::whereIn('ensayo_id', $seleccionados)->update(['ensayo_id' => null]);
            Ensayo::whereRecepcionId($id)->update(['lote' => null, 'origen_id' => null, 'origen_type' => null]);
            \DB::commit();

            return response()->json(['res' => true, 'message' => 'Recepción anulada correctamente']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }

    }

    public function anularEnsayos($id)
    {
        \DB::beginTransaction();
        try {
            $recepcion = Recepcion::find($id);

            $recepcion->update(['estado' => EstadoLaboratorio::Anulado, 'fecha_rechazo' => date('Y-m-d H:i:s')]);

            $ensayos = Ensayo::whereRecepcionId($id)->get();

            $seleccionados = [];
            foreach($ensayos as $ensayo){
                array_unshift($seleccionados, $ensayo->id);
            }
            Laboratorio::whereIn('ensayo_id', $seleccionados)->update(['ensayo_id' => null]);
            Ensayo::whereRecepcionId($id)->update(['lote' => null, 'origen_id' => null, 'origen_type' => null]);
            \DB::commit();
            Flash::success('Pedido anulado correctamente');

            return redirect(route('recepcion-lab.index'));
        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error('Ocurrió un error, revise e intente nuevamente.');

            return redirect(route('recepcion-lab.index'));
        }

    }

    private function getCodigo()
    {
        $codigo = Recepcion::max('codigo');
        return ($codigo + 1);
    }

    private function finalizarEnsayosExternos(Request $request)
    {
        \DB::beginTransaction();
        try {
            $ensayosHumedad = Ensayo::whereRecepcionId($request->id)->whereNotNull('lote')->whereEsFinalizado(false)
                ->where('peso_seco', '<>',0.00)->whereElementoId(2)->get();
            foreach ($ensayosHumedad as $ensayo) {
                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
            }
            $ensayosEstanio = Ensayo::whereRecepcionId($request->id)->whereNotNull('lote')->whereEsFinalizado(false)
                ->where('mililitros_gastados', '<>',0.00)->whereElementoId(1)->get();
            foreach ($ensayosEstanio as $ensayo) {
                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
            }

            $ensayosPlata = Ensayo::whereRecepcionId($request->id)->whereNotNull('lote')->whereEsFinalizado(false)
                ->where('peso_oro', '<>',0.00)->whereElementoId(3)->get();
            foreach ($ensayosPlata as $ensayo) {
                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
            }

            $lab = Recepcion::find($request->id);
            if($lab->ensayos_sin_finalizar==0){
                $lab->update(['es_finalizado' => true, 'fecha_finalizacion' => date('Y-m-d H:i:s'), 'estado' => EstadoLaboratorio::Finalizado]);
                if($lab->saldo>0.00){
                    $glosa = "PAGO FINAL DE " . $lab->glosa;
                    $obj = new PagoMovimientoController();
                    $obj->storeAnalisis($lab->saldo, $lab->id, $glosa);
                }
            }

            \DB::commit();
            Flash::success('Ensayos finalizados.');

            return redirect(route('recepcion-lab.index'));

        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error('Ocurrió un error, revise e intente nuevamente.');

            return redirect(route('recepcion-lab.index'));

        }
    }
    public function finalizarEnsayos(Request $request) {
        $recepcion = Recepcion::find($request->id);
        if($recepcion->cliente_id==1)// si es colquechaca
            return $this->finalizarEnsayosColquechaca($request);
        else
            return $this->finalizarEnsayosExternos($request);
    }

    private function finalizarEnsayosColquechaca(Request $request)
    {
        \DB::beginTransaction();
        try {

            $lab = Recepcion::find($request->id);
            if($lab->ensayos_sin_finalizar==0){
                $lab->update(['es_finalizado' => true, 'fecha_finalizacion' => date('Y-m-d H:i:s'), 'estado' => EstadoLaboratorio::Finalizado]);
                if($lab->saldo>0.00){
                    $glosa = "PAGO FINAL DE " . $lab->glosa;
                    $obj = new PagoMovimientoController();
                    $obj->storeAnalisis($lab->saldo, $lab->id, $glosa);
                }
            }

            //HUMEDAD COMPRA
            $ensayosHumedad = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('peso_seco', '<>',0.00)->whereElementoId(2)->whereOrigenType(FormularioLiquidacion::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosHumedad as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor = ($ensayo->peso_humedo - $ensayo->peso_seco) / ($ensayo->peso_humedo - $ensayo->peso_tara) * 100;

                if ($valor < 0.10)
                    $valor = 0.10;

                $labo = Laboratorio::whereEnsayoId($ensayo->id)->whereNull('mineral_id')->whereOrigen("Empresa")->orderByDesc('id')->first();
                $labo->update(['valor' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objCaja = new CajaController();

                $objLaboratorio->imprimirInformeEnsayo($ensayo->origen_id, $ensayo->recepcion_id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($ensayo->origen_id);

                $objCaja->subirDocumento($formularioLiquidacion);
            }

            //HUMEDAD VENTA
            $ensayosHumedad = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('peso_seco', '<>',0.00)->whereElementoId(2)->whereOrigenType(Concentrado::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosHumedad as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor = ($ensayo->peso_humedo - $ensayo->peso_seco) / ($ensayo->peso_humedo - $ensayo->peso_tara) * 100;

                if ($valor < 0.10)
                    $valor = 0.10;

                $concentrado = Concentrado::whereId($ensayo->origen_id)->orderByDesc('id')->first();
                $concentrado->update(['humedad' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objLaboratorio->imprimirInformeEnsayo($concentrado->venta_id, $ensayo->recepcion_id);


                $venta = Venta::findOrFail($concentrado->venta_id);
                $objDoc = new DocumentosVentaController();
                $objDoc->adjuntarDocumento($venta);
            }

            //ESTANIO COMPRA
            $ensayosEstanio = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('mililitros_gastados', '<>',0.00)->whereElementoId(1)->whereOrigenType(FormularioLiquidacion::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosEstanio as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor = ($ensayo->factor_volumetrico * $ensayo->mililitros_gastados/ $ensayo->peso_muestra)*100;


                $labo = Laboratorio::whereEnsayoId($ensayo->id)->whereMineralId(4)->whereOrigen("Empresa")->orderByDesc('id')->first();
                $labo->update(['valor' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objCaja = new CajaController();

                $objLaboratorio->imprimirInformeEnsayo($ensayo->origen_id, $ensayo->recepcion_id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($ensayo->origen_id);

                $objCaja->subirDocumento($formularioLiquidacion);
                DocumentoCompra::whereFormularioLiquidacionId($ensayo->origen_id)->whereDescripcion(\App\Patrones\DocumentoCompra::LaboratorioEmpresa)
                        ->update(['agregado' => true]);

                $formularioLiquidacion->update(['ley_sn' => $formularioLiquidacion->ley_estanio]);
            }

            //ESTANIO VENTA
            $ensayosEstanio = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('mililitros_gastados', '<>',0.00)->whereElementoId(1)->whereOrigenType(Concentrado::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosEstanio as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor =( $ensayo->factor_volumetrico * $ensayo->mililitros_gastados/ $ensayo->peso_muestra)*100;


                $concentrado = Concentrado::whereId($ensayo->origen_id)->orderByDesc('id')->first();
                $concentrado->update(['ley_sn' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objLaboratorio->imprimirInformeEnsayo($concentrado->venta_id, $ensayo->recepcion_id);


                $venta = Venta::findOrFail($concentrado->venta_id);
                $objDoc = new DocumentosVentaController();
                $objDoc->adjuntarDocumento($venta);
            }

            //PLATA COMPRA
            $ensayosPlata = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('peso_oro', '<>',0.00)->whereElementoId(3)->whereOrigenType(FormularioLiquidacion::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosPlata as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor = (($ensayo->peso_dore - $ensayo->peso_oro)/ $ensayo->peso_muestra)*1000;


                $labo = Laboratorio::whereEnsayoId($ensayo->id)->whereMineralId(1)->whereOrigen("Empresa")->orderByDesc('id')->first();
                $labo->update(['valor' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objCaja = new CajaController();

                $objLaboratorio->imprimirInformeEnsayo($ensayo->origen_id, $ensayo->recepcion_id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($ensayo->origen_id);

                $objCaja->subirDocumento($formularioLiquidacion);
                DocumentoCompra::whereFormularioLiquidacionId($ensayo->origen_id)->whereDescripcion(\App\Patrones\DocumentoCompra::LaboratorioEmpresa)
                    ->update(['agregado' => true]);

                //$formularioLiquidacion->update(['ley_sn' => $formularioLiquidacion->ley_estanio]);
            }

            //PLATA VENTA
            $ensayosPlata = Ensayo::whereRecepcionId($request->id)->whereNotNull('origen_id')->whereEsFinalizado(false)
                ->where('peso_oro', '<>',0.00)->whereElementoId(3)->whereOrigenType(Concentrado::class)->get();
            $lotesSeleccionados = [];
            foreach ($ensayosPlata as $ensayo) {
                array_unshift($lotesSeleccionados, $ensayo->origen_id);
                //
                $valor =(($ensayo->peso_dore - $ensayo->peso_oro)/ $ensayo->peso_muestra)*1000;


                $concentrado = Concentrado::whereId($ensayo->origen_id)->orderByDesc('id')->first();
                $concentrado->update(['ley_sn' => $valor]);

                Ensayo::whereId($ensayo->id)->update(['es_finalizado' =>true, 'fecha_finalizacion' => date('Y-m-d H:i:s')]);
                //

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new EnsayoController();
                $objLaboratorio->imprimirInformeEnsayo($concentrado->venta_id, $ensayo->recepcion_id);


                $venta = Venta::findOrFail($concentrado->venta_id);
                $objDoc = new DocumentosVentaController();
                $objDoc->adjuntarDocumento($venta);
            }

            \DB::commit();
            Flash::success('Ensayos finalizados.');

            return redirect(route('recepcion-lab.index'));

        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error('Ocurrió un error, revise e intente nuevamente.');
            return redirect(route('recepcion-lab.index'));
        }
    }
}
