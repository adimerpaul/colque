<?php

namespace App\Http\Controllers;

use App\Events\AccionCompletaVenta;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Anticipo;
use App\Models\AnticipoVenta;
use App\Models\Cliente;
use App\Models\Concentrado;
use App\Models\FormularioLiquidacion;
use App\Models\LiquidacionMineral;
use App\Models\PagoDolar;
use App\Models\PagoMovimiento;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaFactura;
use App\Models\VentaFormularioLiquidacion;
use App\Models\DocumentoVenta;
use App\Models\VentaMineral;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Patrones\TipoFactura;
use App\Patrones\TipoLoteVenta;
use App\Patrones\TipoMaterial;
use App\Patrones\TipoPago;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use DB;
use Luecano\NumeroALetras\NumeroALetras;
use PhpParser\Node\Expr\Cast\Double;
use function MongoDB\BSON\toJSON;
use function PHPUnit\Framework\isEmpty;

class VentaController
{

    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 365 days'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' 23:59:59'));
        if (isset($request->fecha_final))
            $fecha_final = date('Y-m-d H:i:s', strtotime($request->fecha_final . ' 23:59:59'));

//            $fecha_final = $request->fecha_final;

        $ventas = Venta::where('estado', 'ilike', "%{$request->txtEstado}%")
            ->where(function ($q) use ($txtBuscar) {
                $q->where('producto', 'ilike', "%{$txtBuscar}%")
                    ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)",
                        ["%{$txtBuscar}%"])
                    ->orWhereHas('comprador', function ($q) use ($txtBuscar) {
                        $q->where('razon_social', 'ilike', "%{$txtBuscar}%");
                    });
            })
            ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->paginate();

        return view('ventas.index')
            ->with('ventas', $ventas);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $seleccionados = explode(",", $request->seleccionados);
            if (is_null($request->seleccionados)) {
                Flash::error('Compras no encontradas');

                return redirect(route('lotes.index'));
            }
            $contadorSeleccionados = count($seleccionados);
            //formularios seleccionados
            $formularios = FormularioLiquidacion::whereIn('id', $seleccionados)->whereEstado(Estado::Liquidado)->get();


            $contadorFormularios = $formularios->count();

            if ($contadorFormularios == 0) {
                Flash::error('Compras liquidadas no encontradas');

                return redirect(route('lotes.index'));
            }
            $mensaje = '';
            if ($contadorFormularios < $contadorSeleccionados) {
                $mensaje = ($contadorSeleccionados - $contadorFormularios) . ' lotes no se añadieron debido a que no se encontraban liquidados.';
                $seleccionadosConFiltro = [];
                $i = 0;
                foreach ($formularios as $form) {
                    $seleccionadosConFiltro += array($i => $form->id);
                    $i = $i + 1;
                }
            } else
                $seleccionadosConFiltro = $seleccionados;

            //Venta_compra con ids de formularios
            $formularioYaCreado = VentaFormularioLiquidacion::whereIn('formulario_liquidacion_id', $seleccionadosConFiltro)->get();

            //ya existe venta
            if ($formularioYaCreado->count() > 0) {
                $venta = Venta::find($formularioYaCreado[0]->venta_id);
            } else {
                FormularioLiquidacion::whereIn('id', $seleccionadosConFiltro)->update(['estado' => Estado::Composito]);

                if ($request->tipo_lote == TipoLoteVenta::Ingenio)
                    $input['sigla'] = 'CMI';
                $input['numero_lote'] = $this->getNumero($formularios[0]->producto, $request->tipo_lote)['numero'];
                $input['letra'] = $formularios[0]->letra;
                $input['producto'] = $formularios[0]->producto;
                $input['anio'] = $this->getNumero($formularios[0]->producto, $request->tipo_lote)['anio'];
                $input['tipo_lote'] = $request->tipo_lote;

//            $input['fecha_entrega'] = date('Y-m-d');
                $venta = Venta::create($input);
//venta mineral
                $producto = Producto::whereLetra($venta->letra)->first();
                $productoMinerales = $producto->productoMinerals;
                foreach ($productoMinerales as $row) {
                    if (!$row->es_penalizacion) {
                        $campos['venta_id'] = $venta->id;
                        $campos['mineral_id'] = $row->mineral_id;
                        VentaMineral::create($campos);
                    }
                }


                $valores['venta_id'] = $venta->id;
                for ($i = 0; $i < count($seleccionadosConFiltro); $i++) {
                    $valores['formulario_liquidacion_id'] = $seleccionadosConFiltro[$i];
                    VentaFormularioLiquidacion::create($valores);
                }
                $this->storeDocumentosVentas($venta, $venta->letra);
                event(new AccionCompletaVenta("Nuevo", "Venta creada", $venta->id));

            }
            \DB::commit();


            Flash::success('Venta creada correctamente. ' . $mensaje);

            return redirect(route('ventas.edit', $venta->id));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function storeDocumentosVentas($venta, $letra)
    {
        for ($i = 0; $i < count(Fachada::getTiposDocumentosVentas($letra, $venta->sigla)); $i++) {
            $valor['descripcion'] = Fachada::getTiposDocumentosVentas($letra, $venta->sigla)[$i];
            $valor['venta_id'] = $venta->id;
            DocumentoVenta::create($valor);
        }
    }

    public function show($idVenta)
    {
        return Venta::find($idVenta);
    }

    public function edit($idVenta)
    {

        $venta = Venta::find($idVenta);
        $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($idVenta)->get();
        $seleccionados = [];

        for ($i = 0; $i < $ventasFormularios->count(); $i++) {
            array_unshift($seleccionados, $ventasFormularios[$i]->formulario_liquidacion_id);
        }
        $formularios = FormularioLiquidacion::whereIn('id', $seleccionados)->orderBy('numero_lote')->get();

        $obj = new ConcentradoController();
        $request = request()->merge(['venta_id' => $idVenta, 'tipo' => 'Ingenio']);
        $ingenios = $obj->getConcentrados($request);
        return view('ventas.edit', compact('formularios', 'venta', 'ingenios'));
    }

    public function getComposito($idVenta)
    {
        $venta = Venta::find($idVenta);
        $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($idVenta)->get();
        $seleccionados = [];

        for ($i = 0; $i < $ventasFormularios->count(); $i++) {
            array_unshift($seleccionados, $ventasFormularios[$i]->formulario_liquidacion_id);
        }
        $formularios = FormularioLiquidacion::whereIn('id', $seleccionados)->orderBy('numero_lote')->get();

        $obj = new ConcentradoController();
        $request = request()->merge(['venta_id' => $idVenta, 'tipo' => 'Ingenio']);
        $ingenios = $obj->getConcentrados($request);

        return view('ventas.composito.lectura', compact('formularios', 'venta', 'ingenios'));
    }

    public function update($id, Request $request)
    {
        $valor = $request->valor;
        $nombre = $request->nombre;

        Venta::where('id', $id)->update([$nombre => $valor]);

        if ($nombre == 'comprador_id')
            event(new AccionCompletaVenta("Modificado", "Venta modificada", $id));
        return response()->json(['res' => true, 'message' => 'Registro guardado correctamente']);
    }

    public function cambiarEstado($id, Request $request)
    {
        if ($request->has('btnRestablecer')) {
            Venta::where('id', $id)->update(['estado' => EstadoVenta::EnProceso]);
            //cambiar estado a vendido
            $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($id)->get();

            foreach ($ventasFormularios as $ventaFormulario) {
                FormularioLiquidacion::where('id', $ventaFormulario->formulario_liquidacion_id)->update(['estado' => Estado::Composito]);
            }

            event(new AccionCompletaVenta("Restablecido", "Venta restablecida", $id));
            Flash::success('Venta restablecida correctamente.');
        } elseif ($request->has('btnAnular')) {
            $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($id)->get();

            foreach ($ventasFormularios as $ventaFormulario) {
                FormularioLiquidacion::where('id', $ventaFormulario->formulario_liquidacion_id)->update(['estado' => Estado::Liquidado]);
            }
            $ventaForm = VentaFormularioLiquidacion::whereVentaId($id)->delete();

            Venta::where('id', $id)->update(['estado' => EstadoVenta::Anulado]);
            event(new AccionCompletaVenta("Anulado", "Venta anulada", $id));
            Flash::success('Venta anulada correctamente.');
        }
        return redirect(route('ventas.edit', [$id]));
    }

    public function finalizar($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            if ($request->utilidad == 'NaN' or $request->margen == 'NaN') {
                Flash::error('Refresque el composito (boton celeste esquina inferior), antes de finalizar la venta');
                \DB::rollBack();
                return redirect(route('ventas.edit', [$id]));
            }
            $total = (str_replace(',', '.', $request->monto));
            $utilidad = (str_replace(',', '.', $request->utilidad));
            $margen = (str_replace(',', '.', $request->margen));

            if (is_null($request->cambiar)) {
                $venta = Venta::whereId($id)->whereNotNull('lote_comprador')
                    ->whereNotNull('tipo_transporte')
                    ->whereNotNull('trayecto')
                    ->whereNotNull('tranca')
                    ->whereNotNull('municipio')
                    ->whereNotNull('comprador_id')->first();
                if (!$venta) {
                    Flash::error('Complete todos los campos antes de finalizar');
                    return redirect(route('ventas.edit', [$id]));
                }

                $concentradoVenta = Concentrado::whereVentaId($id)
                    ->whereTipoLote(TipoLoteVenta::Venta)
                    ->first();

                if (empty($concentradoVenta)) {
                    Flash::error('Agregue por lo menos un producto antes de finalizar');
                    return redirect(route('ventas.edit', [$id]));
                }

                $venta->update(['estado' => EstadoVenta::Liquidado, 'monto' => $total, 'utilidad' => $utilidad, 'margen' => $margen,
                    'peso_neto_seco' => $concentradoVenta->peso_neto_seco, 'valor_neto_venta' => $concentradoVenta->valor_neto_venta,
                    'fecha_venta' => date('Y-m-d')]);

                if ($total == 0.00)
                    $venta->update(['es_aprobado' => true]);
                //cambiar estado a vendido
                $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($id)->get();

                // fecha promedio
                $sumatoriaSeco = 0;
                foreach ($ventasFormularios as $ventaFormulario) {
                    $sumatoriaSeco = $sumatoriaSeco + $ventaFormulario->formulario->peso_seco;

                    FormularioLiquidacion::where('id', $ventaFormulario->formulario_liquidacion_id)->update(['estado' => Estado::Vendido]);
                    VentaFormularioLiquidacion::where('id', $ventaFormulario->id)->update(['peso_acumulado' => $ventaFormulario->calculo_peso]);
                }

                $concentrados = Concentrado::whereTipoLote(TipoLoteVenta::Ingenio)->whereVentaId($id)->get();

                foreach ($concentrados as $concentrado) {
                    $sumatoriaSeco = $sumatoriaSeco + $ventaFormulario->peso_neto_seco;
                    Concentrado::where('id', $concentrado->id)->update(['peso_acumulado' => $concentrado->calculo_peso]);
                }
                $sumatoriaSeco = $sumatoriaSeco / 2;

                $formElegido = FormularioLiquidacion::
                join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
                    ->where('venta_formulario_liquidacion.venta_id', $id)
                    ->where('peso_acumulado', '>=', $sumatoriaSeco)
                    ->orderBy('fecha_liquidacion')
                    ->first();

                $ingenioElegido = Concentrado::whereVentaId($id)
                    ->where('peso_acumulado', '>=', $sumatoriaSeco)
                    ->whereTipoLote(TipoLoteVenta::Ingenio)
                    ->orderBy('fecha')
                    ->first();


                $fechaElegida = $formElegido->fecha_liquidacion;
                if ($ingenioElegido) {
                    if ($ingenioElegido->peso_acumulado < $formElegido->peso_acumulado)
                        $fechaElegida = $ingenioElegido->fecha;
                }
                $venta->update(['fecha_promedio' => $fechaElegida]);
                // fin fecha

                if ($venta->tipo_lote == TipoLoteVenta::Ingenio)
                    $venta->update(['es_cancelado' => true, 'es_aprobado' => true, 'utilidad' => 0, 'margen' => 0, 'monto' => 0]);

                event(new AccionCompletaVenta("Liquidado", "Venta liquidada", $id));
                Flash::success('Venta liquidada correctamente.');
            } else {
                Venta::where('id', $id)->update(['monto' => $total]);
                Flash::success('Monto de Venta editada correctamente.');
            }

            \DB::commit();
            return redirect(route('ventas.edit', [$id]));
        } catch (\Exception $e) {
            \DB::rollBack();
            Flash::error('Sucedió un error, revise e intente nuevamente');
            return redirect(route('ventas.edit', [$id]));
        }
    }

    public function updateFields($id, Request $request)
    {
        $venta = Venta::find($id);
        if ($venta->estado !== EstadoVenta::EnProceso)
            return response()->json(['res' => false, 'message' => 'No se puede modificar la venta porque está liquidada']);

        $venta->fill($request->all());
        $venta->save();

        event(new AccionCompletaVenta("Modificado", "Venta modificada", $id));
        return response()->json(['res' => true, 'message' => 'Registro guardado correctamente']);
    }

    public function actualizar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $id = $request->venta_id;
            $seleccionados = explode(",", $request->seleccionados);

            $venta = Venta::find($id);


            if (is_null($request->seleccionados) || is_null($venta)) {
                Flash::error('Compras/Venta no encontradas');
                return redirect(route('ventas.index'));
            }

            if ($venta->es_despachado) {
                Flash::error('No se pueden agregar mas lotes, porque ya fue despachado');
                return redirect(route('ventas.index'));
            }

            $valores['venta_id'] = $id;

            //////////////////////////
            $contadorSeleccionados = count($seleccionados);
            $formularios = FormularioLiquidacion::whereIn('id', $seleccionados)->whereEstado(Estado::Liquidado)->get();
            $contadorFormularios = $formularios->count();

            if ($contadorFormularios == 0) {
                Flash::error('Compras liquidadas no encontradas');
                return redirect(route('lotes.index'));
            }
            $mensaje = '';
            if ($contadorFormularios < $contadorSeleccionados) {
                $mensaje = ($contadorSeleccionados - $contadorFormularios) . ' lotes no se añadieron debido a que no se encontraban liquidados.';
                $seleccionadosConFiltro = [];
                $i = 0;
                foreach ($formularios as $form) {
                    $seleccionadosConFiltro += array($i => $form->id);
                    $i = $i + 1;
                }
            } else
                $seleccionadosConFiltro = $seleccionados;
            ////////
            for ($i = 0; $i < count($seleccionadosConFiltro); $i++) {
                $ventaCompra = VentaFormularioLiquidacion::whereFormularioLiquidacionId($seleccionadosConFiltro[$i])->whereVentaId($id)->get();
                if ($ventaCompra->count() === 0) {
                    $valores['formulario_liquidacion_id'] = $seleccionadosConFiltro[$i];
                    VentaFormularioLiquidacion::create($valores);
                    FormularioLiquidacion::where('id', $seleccionadosConFiltro[$i])->update(['estado' => Estado::Composito]);
                }
            }
            event(new AccionCompletaVenta("Modificado", "Lotes agregados", $id));
            \DB::commit();
            Flash::success('Lotes agregados correctamente. ' . $mensaje);
            return redirect(route('ventas.edit', $venta->id));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function getNumero($producto, $tipo)
    {
        $fechaActual = Fachada::getFecha();
        $fechaInicioGestion = \DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime(date('Y') . "-10-01")));

        $anio = date('Y');
        if ($fechaActual >= $fechaInicioGestion)
            $anio += 1;

        $venta = Venta::
        where('anio', $anio)->whereProducto($producto)->whereTipoLote($tipo)->max('numero_lote');
//        if ($producto == 'D | Estaño') {
//            $venta = Venta::
//            where('anio', $anio)->whereProducto($producto)->max('numero_lote');
//        } elseif ($producto == 'F | Antimonio') {
//            $venta = Venta::
//            where('anio', $anio)->whereProducto($producto)->max('numero_lote');
//        } elseif ($producto == 'E | Plata') {
//            $venta = Venta::
//            where('anio', $anio)->whereProducto($producto)->max('numero_lote');
//        } else {
//            $venta = Venta::
////        where('estado', '<>', Estado::Anulado)->
//            where('anio', $anio)->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo'])->max('numero_lote');
//        }

        return ['numero' => $venta + 1, 'anio' => $anio];
    }

    public function imprimirOrdenDespacho($id)
    {
        $seleccionados = [];
        $venta = Venta::find($id);
        $ventasFormularios = VentaFormularioLiquidacion::whereVentaId($id)->get();

        for ($i = 0; $i < $ventasFormularios->count(); $i++) {
            array_unshift($seleccionados, $ventasFormularios[$i]->formulario_liquidacion_id);
        }
        $formularios = FormularioLiquidacion::whereIn('id', $seleccionados)->orderBy('numero_lote')->get();

        $ingenios = Concentrado::whereVentaId($id)->whereTipoLote(TipoLoteVenta::Ingenio)->orderBy('id')->get();

        $vistaurl = "ventas.orden_despacho";
        $view = \View::make($vistaurl, compact('formularios', 'venta', 'ingenios'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('OrdenDeDespacho-' . $venta->codigo_odd . '.pdf');
    }

    public function destroy($formularioId)
    {
        $ventaForm = VentaFormularioLiquidacion::whereFormularioLiquidacionId($formularioId)->first();
        if (empty($ventaForm)) {
            Flash::error('Compra no encontrada');

            return redirect(route('ventas.index'));
        }

        $ventaForm2 = VentaFormularioLiquidacion::whereFormularioLiquidacionId($formularioId)->whereDespachado(false)->first();
        if (empty($ventaForm2)) {
            Flash::error('No se puede eliminar porque ya se despachó el lote');

            return redirect()
                ->route('ventas.edit', ['venta' => $ventaForm->venta_id]);
        }

        $ventaForm->delete();

        FormularioLiquidacion::where('id', $formularioId)->update(['estado' => Estado::Liquidado]);
        $formulario = FormularioLiquidacion::find($formularioId);

        event(new AccionCompletaVenta("Compra eliminada", "Compra desagregada " . $formulario->lote, $ventaForm->venta_id));


        Flash::success('Compra desagregada correctamente');

        return redirect(route('ventas.edit', $ventaForm->venta_id));
    }

    public function getCompras($ventaId)
    {
        return FormularioLiquidacion::
        join('venta_formulario_liquidacion', 'formulario_liquidacion.id', '=', 'venta_formulario_liquidacion.formulario_liquidacion_id')
            ->where('venta_formulario_liquidacion.venta_id', $ventaId)
            ->select("formulario_liquidacion.*")->get();
    }

    public function getVentasCaja(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $txtTipo = $request->txtTipo;
        if (is_null($txtTipo))
            $txtTipo = 'Egreso';

        $fechaInicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 12 months'));
        if (isset($request->fecha_inicial))
            $fechaInicial = $request->fecha_inicial;

        $fechaFinal = date('Y-m-d');
        $esCancelado = $request->txtEstado;

        if (isset($request->fecha_final))
            $fechaFinal = $request->fecha_final;

        $fechaFinal = date('Y-m-d', strtotime($fechaFinal . ' + 1 days'));

        if (!$esCancelado) {
            $ventas = Venta::where('estado', EstadoVenta::Liquidado)
                ->where('es_cancelado', false)
                ->where('es_aprobado', true)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)",
                            ["%{$txtBuscar}%"]);
                })
                ->whereBetween('fecha_venta', [$fechaInicial, $fechaFinal])
                ->orderByDesc('numero_lote')->orderByDesc('id')
                ->paginate();
        } else {
            $ventas = PagoMovimiento::
            join('venta', 'venta.id', '=', 'pago_movimiento.origen_id')
                ->join('comprador', 'comprador.id', '=', 'venta.comprador_id')
                ->where('pago_movimiento.origen_type', Venta::class)
                ->where(function ($q) use ($txtBuscar) {
                    $q->where(\DB::raw("concat(pago_movimiento.monto, ' BOB')"), 'ilike', "%{$txtBuscar}%")
                        ->orwhere('producto', 'ilike', "%{$txtBuscar}%")
                        ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(venta.anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                        ->orWhere('comprador.razon_social', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('comprador.nit', 'ilike', "%{$txtBuscar}%")
                        ->orWhere('pago_movimiento.codigo', 'ilike', "%{$txtBuscar}%");
                })
                ->whereBetween('pago_movimiento.created_at', [$fechaInicial, $fechaFinal])
                ->orderByDesc('pago_movimiento.id')
                ->select('pago_movimiento.id', 'pago_movimiento.codigo', 'pago_movimiento.created_at', 'comprador.razon_social', 'comprador.nit',
                    'venta.producto', 'pago_movimiento.monto', 'pago_movimiento.origen_id', 'pago_movimiento.alta',
                    DB::raw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(venta.anio as varchar),3,4))) as lote"))
                ->paginate();

        }

        return view('ventas.caja_ventas', compact('ventas', 'esCancelado'));
    }


    public function registrarPago(Request $request)
    {
        \DB::beginTransaction();
        try {
            $ventaId = $request->idVenta;
            $venta = Venta::find($ventaId);
            if ($venta->es_cancelado) {
                \DB::rollBack();

                Flash::error('La venta ya fue pagada anteriormente.');

                return redirect(route('pagos.anticipos'));
            }

            Venta::where('id', $ventaId)->update(['es_cancelado' => true, 'fecha_cobro' => date('Y-m-d')]);

            if ($request->metodo == TipoPago::CuentaBancariaDolares) {
                $dolar = $request->all();
                $obj = new PagoDolarController();
                $dolar['codigo'] = $obj->getCodigo('Ingreso');
                $dolar['anio'] = date('y');
                if (date('m') >= 10)
                    $dolar['anio'] = $dolar['anio'] + 1;
                $dolar['glosa'] = 'BANCOS BNB M/E. Cobro por venta de Lote ' . $venta->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
                $dolar['tipo'] = 'Ingreso';
                $dolar['venta_id'] = $ventaId;

                $dolar['monto'] = $venta->monto_final / $request->tipo_cambio;

                $pago = PagoDolar::create($dolar);
                Flash::success('Pago registrados correctamente.');
                \DB::commit();
                echo "<script>
            window.location.href = '/pagos-dolares';
            window.open('/pagos-dolares/'+'$pago->id'+'/imprimir', '_blank');
                </script>";

                return true;
            }

            $campos['monto'] = $venta->monto_final;
            $campos['metodo'] = $request->metodo;
            if ($request->metodo == TipoPago::CuentaBancaria) {
                $campos['banco'] = $request->banco;
                $campos['glosa'] = 'Cobro por venta de Lote ' . $venta->lote . ', en transferencia bancaria con recibo ' . $request->numero_recibo;
            } else
                $campos['glosa'] = 'Cobro por venta de Lote ' . $venta->lote;
            $campos['origen_type'] = Venta::class;
            $campos['origen_id'] = $ventaId;
            $obj = new MovimientoController();
            $campos['codigo'] = $obj->getCodigo('Ingreso');
            $campos['anio'] = date('y');

            if (date('m') >= 10)
                $campos['anio'] = $campos['anio'] + 1;

            $objMov = new MovimientoController();
            $campos['numero'] = $objMov->proximoOrden();
            $pago = PagoMovimiento::create($campos);
//            PagoMovimiento::where('id', $pago->id)->update(['saldo_caja' => $pago->saldo_pago, 'saldo_banco' => $pago->saldo_pago_banco]);

//            $venta = Venta::find($ventaId);
            //           if ($venta->saldo == 0.00)

            \DB::commit();
            Flash::success('Venta pagada correctamente.');

            echo "<script>
            window.location.href = '/pagos-ventas';
            window.open('/ventas/'+$pago->id+'/imprimir', '_blank');
                </script>";
        } catch (\Exception $e) {
            \DB::rollBack();
            return dd($e);
        }
    }

    public function imprimir($id)
    {
        $pago = PagoMovimiento::find($id);
        $venta = Venta::find($pago->origen_id);

        $historial = AnticipoVenta::whereVentaId($pago->origen_id)->whereEsCancelado(true)
            ->orderBy('id')->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($pago->monto, 2, 'BOLIVIANOS', 'CENTAVOS');


        $vistaurl = "ventas.imprimir";
        $view = \View::make($vistaurl, compact('historial', 'pago', 'literal'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboVenta-' . $id . '-' . $pago->origen->lote . '.pdf');
    }


    public function enviarOperaciones(Request $request)
    {
        $id = $request->venta_id;
        $venta = Venta::find($id);

        if (empty($venta)) {
            return response()->json(['res' => false, 'message' => 'No se encuentra la venta']);
        }
        $venta->update(['a_operaciones' => true]);
        event(new AccionCompletaVenta("Enviado", "Enviado a Operaciones", $id));

        return response()->json(['res' => true, 'message' => 'Lote enviado a operaciones']);
    }

    public function verificarDespacho(Request $request)
    {
        $id = $request->venta_id;
        $venta = Venta::find($id);

        if (empty($venta)) {
            return response()->json(['res' => false, 'message' => 'No se encuentra la venta']);
        }
        $venta->update(['verificado_despacho' => true]);

        return response()->json(['res' => true, 'message' => 'Despacho verificado']);
    }

    public function getVentaFactura($id)
    {
        $venta = VentaFactura::find($id);
        return $venta;
    }

    public function getVenta($id)
    {
        $venta = Venta::find($id);
        return view('ventas.aprobar', compact('venta'));
    }

    public function aprobar(Request $request)
    {
        $id = $request->id;

        $venta = Venta::whereId($id)->whereEsAprobado(false)->whereEstado(EstadoVenta::Liquidado)->first();

//        if($request->tipo_diferencia=='Positiva')
//            $final =  $venta->monto + $request->diferencia;
//        else
//            $final =  $venta->monto - $request->diferencia;

        if (empty($venta)) {
            Flash::error('Venta no encontrada');

            return redirect(route('ventas.index'));
        }
        $venta->update(['es_aprobado' => true, 'monto_final' => $venta->monto]);
        Flash::success('Venta aprobada');

        return redirect(route('ventas.index'));
    }
    public function generarFactura(Request $request)
    {
        $id = $request->idVenta;
        $input=$request->all();
        $venta= VentaFactura::find($id);
        $input["fecha_emision"] = date('Y-m-d H:i:s');
        $input["documento_sector_id"] = 4;
        if($request->tipo_factura==TipoFactura::CompraVenta){
            $input["documento_sector_id"] = 5;
            $input["pais_id"] = null;
            $input["puerto_transito"] = null;
            $input["incoterm"] = null;

        }
        $venta->fill($input);
        $venta->save();

        if($request->ley_ag!=0.00){
            $ventaMineral=VentaMineral::whereVentaId($id)->whereMineralId(1)->first();
            if($ventaMineral){
                $ventaMineral->update(['descripcion_leyes' => $request->ley_ag]);
            }
        }

        if($request->ley_pb!=0.00){
            $ventaMineral=VentaMineral::whereVentaId($id)->whereMineralId(2)->first();
            if($ventaMineral){
                $ventaMineral->update(['descripcion_leyes' => $request->ley_pb]);
            }
        }

        if($request->ley_zn!=0.00){
            $ventaMineral=VentaMineral::whereVentaId($id)->whereMineralId(3)->first();
            if($ventaMineral){
                $ventaMineral->update(['descripcion_leyes' => $request->ley_zn]);
            }
        }

        $venta = VentaFactura::find($id);
        $monto=0;
        foreach($venta->minerales as $v){
            $monto = $monto + $v->subtotal;
        }

        Venta::whereId($id)->update(['monto_total' => $monto]);

        $ventaImp = VentaFactura::whereId($id)->first();

        //return $ventaImp;
        if($request->tipo_factura == TipoFactura::ExportacionMinera){


            $factura = new ExportacionMineralController();
            $lol = $factura->emitirExportacionMineral($ventaImp);
            return $lol;
        }
        else{
            $factura = new CompraVentaController();
            $lol = $factura->emitirCompraVenta($ventaImp);
            return $lol;
        }


        Flash::success('Factura generada correctamente');
        return redirect(route('ventas.index'));
    }
}
