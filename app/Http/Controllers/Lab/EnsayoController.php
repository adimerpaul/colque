<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\AppBaseController;
use App\Models\Concentrado;
use App\Models\FormularioLiquidacion;
use App\Models\Lab\Ensayo;
use App\Models\Lab\PagoMovimiento;
use App\Models\Lab\PrecioElemento;
use App\Models\Lab\Recepcion;
use App\Models\Laboratorio;
use App\Patrones\EstadoLaboratorio;
use Illuminate\Http\Request;
use Flash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EnsayoController extends AppBaseController
{
    public function actualizarLote($id, Request $request)
    {
        $ensayo = Ensayo::find($id);
        if ($ensayo->recepcion->cliente_id == 1) {
            $tipo = $request->tipo;

            if ($tipo == 'C') {
                $form = FormularioLiquidacion::find($request->lote);

                $ensayoLote = Ensayo::whereElementoId($ensayo->elemento_id)
                    ->whereOrigenId($form->id)
                    ->whereOrigenType(FormularioLiquidacion::class)
                    //      ->where('estado', '<>', EstadoLaboratorio::Anulado)
                    ->where('id', '<>', $id)
                    ->count();

                if ($ensayoLote > 0) {
                    return response()->json(['res' => false, 'message' => "El lote ya se registró anteriormente"]);
                }

                $elemento = $ensayo->elemento_id;
                //antiguo laboratorio
                if(!is_null( $ensayo->lote)){
                    if($elemento==1){
                        $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereMineralId(4)->first();
                        $labo->update(['ensayo_id' => null]);
                    }
                    elseif($elemento==2){
                        $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereNull('mineral_id')->first();
                        $labo->update(['ensayo_id' => null]);
                    }
                    elseif($elemento==3){
                        $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereMineralId(1)->first();
                        $labo->update(['ensayo_id' => null]);
                    }
                }

                //fin antiguo laboratorio

                $labo = Laboratorio::whereOrigen('Empresa')->whereFormularioLiquidacionId($request->lote)
                    ->where(function ($q) use ($elemento) {
                        if ($elemento == 2) {
                            $q->whereNull('mineral_id');
                        } elseif ($elemento == 1) {
                            $q->whereMineralId(4);
                        }
                        elseif ($elemento == 3) {
                            $q->whereMineralId(1);
                        }
                    })->first();
                $labo->update(['ensayo_id' => $id]);


                Ensayo::whereId($id)->update(['lote' => $form->lote, 'origen_id' => $form->id, 'origen_type' => FormularioLiquidacion::class]);
            } else {
                $concentrado = Concentrado::find($request->lote);

                $ensayoLote = Ensayo::
                join('laboratorio.recepcion', 'laboratorio.recepcion.id', 'laboratorio.ensayo.recepcion_id')
                    ->whereElementoId($ensayo->elemento_id)
                    ->where('laboratorio.ensayo.origen_id', $concentrado->id)
                    ->where('laboratorio.ensayo.origen_type', Concentrado::class)
                    ->where('estado', '<>', EstadoLaboratorio::Anulado)
                    ->where('laboratorio.ensayo.id', '<>', $id)
                    ->count();


                if ($ensayoLote > 0) {
                    return response()->json(['res' => false, 'message' => "El lote ya se registró anteriormente"]);
                }

                Ensayo::whereId($id)->update(['lote' => $concentrado->nombre, 'origen_id' => $concentrado->id, 'origen_type' => Concentrado::class]);
            }
        } else {
            Ensayo::whereId($id)->update(['lote' => $request->lote]);
        }
        return response()->json(['res' => true, 'message' => "Lote registrado correctamente", 'cliente' => $ensayo->recepcion->cliente_id]);
    }


    public function destroy($id)
    {
        $ensayo = Ensayo::find($id);

        if (empty($ensayo)) {
            Flash::error('Ensayo no encontrado');

            return redirect(route('recepcion-lab.edit', [$ensayo->recepcion_id]));
        }
//
//        if (!$ensayo->puede_eliminarse) {
//            Flash::error('No es posible realizar esta acción');
//
//            return redirect(route('clientes.lista', [$ensayo->cooperativa_id]));
//        }

        if(!is_null( $ensayo->lote)){
            if($ensayo->elemento_id==1){
                $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereMineralId(4)->first();
                $labo->update(['ensayo_id' => null]);
            }
            elseif($ensayo->elemento_id==2){
                $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereNull('mineral_id')->first();
                $labo->update(['ensayo_id' => null]);
            }
            elseif($ensayo->elemento_id==3){
                $labo=Laboratorio::whereEnsayoId($id)->whereOrigen('Empresa')->whereMineralId(1)->first();
                $labo->update(['ensayo_id' => null]);
            }
        }
        Ensayo::destroy($id);

        Flash::success('Ensayo eliminado correctamente.');

        return redirect(route('recepcion-lab.edit', [$ensayo->recepcion_id]));
    }

    public function update($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $recepcion = Recepcion::find($id);

            if ($recepcion->a_caja)
                return response()->json(['res' => false, 'message' => 'ERROR!! Ya se envió a caja anteriormente']);

            $valor["recepcion_id"] = $id;
            $diferenciaEstanio = $recepcion->cantidad_estanio - $request->cantidadEstanio;
            $diferenciaPlata = $recepcion->cantidad_plata - $request->cantidadPlata;

            $precioEstanio = PrecioElemento::whereElementoId(1)->first();
            $precioHumedad = PrecioElemento::whereElementoId(2)->first();
            $precioPlata = PrecioElemento::whereElementoId(3)->first();

            if ($recepcion->cantidad_estanio < $request->cantidadEstanio) {
                for ($i = 0; $i < ($diferenciaEstanio * -1); $i++) {
                    $valor["elemento_id"] = 1;
                    $valor["precio_unitario"] = $precioEstanio->monto;
                    Ensayo::create($valor);
                }
            } elseif ($recepcion->cantidad_estanio > $request->cantidadEstanio) {
                for ($i = 0; $i < ($diferenciaEstanio); $i++) {
                    $ensayo = Ensayo::whereRecepcionId($id)->whereElementoId(1)->orderByDesc('id')->first();
                    $ensayo->delete($ensayo->id);
                }
            }

            if ($recepcion->cantidad_plata < $request->cantidadPlata) {
                for ($i = 0; $i < ($diferenciaPlata * -1); $i++) {
                    $valor["elemento_id"] = 3;
                    $valor["precio_unitario"] = $precioPlata->monto;
                    Ensayo::create($valor);
                }
            } elseif ($recepcion->cantidad_plata > $request->cantidadPlata) {
                for ($i = 0; $i < ($diferenciaPlata); $i++) {
                    $ensayo = Ensayo::whereRecepcionId($id)->whereElementoId(3)->orderByDesc('id')->first();
                    $ensayo->delete($ensayo->id);
                }
            }

            $diferenciaHumedad = $recepcion->cantidad_humedad - $request->cantidadHumedad;
            if ($recepcion->cantidad_humedad < $request->cantidadHumedad) {
                for ($i = 0; $i < ($diferenciaHumedad * -1); $i++) {
                    $valor["elemento_id"] = 2;
                    $valor["precio_unitario"] = $precioHumedad->monto;
                    Ensayo::create($valor);
                }
            } elseif ($recepcion->cantidad_humedad > $request->cantidadHumedad) {
                for ($i = 0; $i < ($diferenciaHumedad * 1); $i++) {
                    $ensayo = Ensayo::whereRecepcionId($id)->whereElementoId(2)->orderByDesc('id')->first();
                    $ensayo->delete($ensayo->id);
                }
            }
            $recepcion->update(['anticipo' => $request->monto]);

            //monto pagado
//            $pagoMovimiento = PagoMovimiento::whereOrigenId($id)->whereOrigenType(Recepcion::class)->orderBy('id')->first();
//            if ($pagoMovimiento){
//                if($pagoMovimiento->es_cancelado and $pagoMovimiento->monto !=$request->monto){
//                    \DB::rollBack();
//                    return response()->json(['res' => false, 'message' => 'No se pudo actualizar el monto porque ya se encuentra pagado']);
//                }
//                else{
//                    $pagoMovimiento->update(['monto' => $request->monto]);
//                }
//            }


            //fin monto pagado
            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Recepción registrada correctamente']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function ensayolab(request $request)
    {
        $dateBuscar = $request->fecha;

        $ensayos = Ensayo::query();

        if (!empty($dateBuscar)) {
            $ensayos->whereDate('created_at', $dateBuscar);
        }

        $ensayos = $ensayos->orderBy('codigo')
            ->paginate(50);

        if ($ensayos->isEmpty()) {
            Flash::info('No se encontro los datos de la busqueda');
        }

        return view('lab.ensayos.index', compact('ensayos'));
    }

    public function imprimirInformeEnsayo($origenId, $id)
    {
        $pedido = Recepcion::find($id);


        $ensayos = Ensayo::whereRecepcionId($id)->whereEsFinalizado(true)->orderBy('elemento_id')->orderBy('id')->get();

        if (empty($pedido)) {
            return response()->json(['msg' => 'Ensayo no encontrado']);
        }

        //generador qr
        $urlQR = url("/imprimir-informe-ensayo/{$pedido->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "lab.ensayos.informe";
        $view = \View::make($vistaurl, compact('pedido', 'ensayos', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $fechaImpresion = date('d/m/Y H:i');

        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

        $file_url = public_path() . "/documents/" . $origenId . '.pdf';
        file_put_contents($file_url, $pdf->output());
//        return $pdf->stream('Pesaje ' . $formularioLiquidacion->lote . '.pdf');
        return $pdf->output();

//        if (!is_null($pedido->fecha_finalizacion))
//            $fechaImpresion = date('d/m/Y H:i', strtotime($pedido->fecha_finalizacion));
        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('InformeEnsayo ' . $pedido->codigo . '.pdf');
    }

    public function imprimirInforme($id)
    {
        $pedido = Recepcion::find($id);
        $ensayos = Ensayo::whereRecepcionId($id)->whereEsFinalizado(true)->orderBy('elemento_id')->orderBy('id')->get();

        if (empty($pedido)) {
            return response()->json(['msg' => 'Ensayo no encontrado']);
        }

//        if (!$pedido->es_cancelado) {
//            return response()->json(['msg' => 'Ensayos no pagados']);
//        }

        //generador qr
        $urlQR = url("/imprimir-informe-ensayo/{$pedido->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "lab.ensayos.informe";
        $view = \View::make($vistaurl, compact('pedido', 'ensayos', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $fechaImpresion = date('d/m/Y H:i');

        $canvas->page_text(48, 810, 'FECHA Y HORA DE IMPRESIÓN: ' . $fechaImpresion, null, 7, array(0, 0, 0));

//        if (!is_null($pedido->fecha_finalizacion))
//            $fechaImpresion = date('d/m/Y H:i', strtotime($pedido->fecha_finalizacion));
        $canvas->page_text(510, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('InformeEnsayo ' . $pedido->codigo . '.pdf');
    }

    public function getEnsayos(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $txtElemento = $request->elemento;
        if (is_null($txtElemento))
            $txtElemento = '2';

        $estado = $request->estado;
        if (is_null($estado))
            $estado = '%';

        if ($estado == 'proceso')
            $estado = EstadoLaboratorio::EnProceso;

        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));
        $ensayos = Ensayo::
        whereBetween('created_at', [$fecha_inicial, $fecha_final])
            ->where(function ($q) use ($txtBuscar) {
                $q->where('lote', 'ilike', "%{$txtBuscar}%")
                    ->orWhereHas('recepcion', function ($q) use ($txtBuscar) {
                        $q->where("codigo", "ilike", "%{$txtBuscar}%");
                    });
            })
            ->where(function ($q) use ($estado) {
                if ($estado == EstadoLaboratorio::Finalizado) {
                    $q->whereEsFinalizado(true);
                } elseif ($estado == EstadoLaboratorio::EnProceso) {
                    $q->whereEsFinalizado(false)
                        ->whereHas('recepcion', function ($q) use ($estado) {
                            $q->where('estado', $estado);
                        });
                } else {
                    $q->whereHas('recepcion', function ($q) use ($estado) {
                        $q->whereIn('estado', [EstadoLaboratorio::Finalizado, EstadoLaboratorio::EnProceso]);
                    });

                }
            })
            ->whereElementoId($txtElemento)
            ->orderByDesc('origen_id')
            ->paginate(20);

        return $ensayos;
    }

    public function storeHumedad(Request $request)
    {

        \DB::beginTransaction();
        try {
            $input = $request->all();

            try {
                $valor = ($request->peso_humedo - $request->peso_seco) / ($request->peso_humedo - $request->peso_tara) * 100;
                if ($valor < 0.10)
                    $valor = 0.10;
            } catch (\Exception $e) {
                $valor = 0;
            }

            $input["resultado"] = $valor;

            $laboratorio = Ensayo::find($request->ensayo_id);

            if ($laboratorio->es_finalizado) {
                return response()->json(['res' => false, 'message' => 'ERROR, no se puede modificar un ensayo finalizado']);
            }
            $laboratorio->fill($input);
            $laboratorio->save();

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Humedad registrada correctamente con Ley: ' . round($valor, 2) . ' %']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['res' => false, 'message' => 'Ocurrió un error, revise e intente nuevamente']);

        }
    }

    public function storeEstanio(Request $request)
    {
        try {
            $input = $request->all();
            $laboratorio = Ensayo::find($request->id);


            $input["resultado"] = ($request->factor_volumetrico * $request->mililitros_gastados / $request->peso_muestra ) *100;
            $laboratorio->fill($input);
            $laboratorio->save();

            return response()->json(['res' => true, 'message' => 'Laboratorio registrado correctamente con Ley: ' . round($laboratorio->resultado, 2) . ' %']);

        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => $e]);

        }
    }

    public function storePlata(Request $request)
    {
        try {
            $input = $request->all();
            $laboratorio = Ensayo::find($request->id);


            $input["resultado"] = (($request->peso_dore - $request->peso_oro) / $request->peso_muestra ) *1000;
            $laboratorio->fill($input);
            $laboratorio->save();

            return response()->json(['res' => true, 'message' => 'Laboratorio registrado correctamente con Ley: ' . round($laboratorio->resultado, 2) . ' D.M.']);

        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => $e]);

        }
    }

}
