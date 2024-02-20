<?php

namespace App\Http\Controllers;

use App\Models\DocumentoCompra;
use App\Models\FormularioLiquidacion;
use App\Models\Laboratorio;
use App\Models\LaboratorioEnsayo;
use App\Models\LaboratorioPesoHumedad;
use App\Patrones\ElementoLaboratorio;
use App\Patrones\Estado;
use App\Patrones\TipoEnsayoHumedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AndroidLaboratorioController extends AppBaseController
{
    public function getCompras(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $formularioLiquidacions = DB::table('cliente')
            ->join('cooperativa', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->join('formulario_liquidacion', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'formulario_liquidacion.id', 'formulario_liquidacion.producto', 'formulario_liquidacion.boletas',
                DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as cliente"),
                'cooperativa.razon_social as cooperativa', 'peso_bruto',
                'sacos', 'cliente_id', 'tara', 'presentacion',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso],
                ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '100 HOURS'")],
                ['formulario_liquidacion.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso],
                ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '100 HOURS'")],
                ['formulario_liquidacion.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->OrWhere([[DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '200 HOURS'")],
                ['formulario_liquidacion.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->orderByDesc('formulario_liquidacion.id')
            ->paginate();

//100 hours
        return $formularioLiquidacions;
    }


    public function getVentas(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $ventas = DB::table('comprador')
            ->join('venta', 'comprador.id', '=', 'venta.comprador_id')
            ->select(DB::raw("concat(venta.sigla, venta.numero_lote, venta.letra,'/', SUBSTRING ( venta.anio::text,3)) as lote"),
                'venta.id', 'venta.producto', 'venta.boletas',
                DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as cliente"),
                'cooperativa.razon_social as cooperativa', 'peso_bruto',
                'sacos', 'cliente_id', 'tara', 'presentacion',
                DB::raw("to_char(venta.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([["venta.producto", 'ilike', ["%{$txtBuscar}%"]], ['venta.estado', Estado::EnProceso],
                ['venta.created_at', '>=', DB::raw("NOW() - INTERVAL '100 HOURS'")],
                ['venta.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['venta.estado', Estado::EnProceso],
                ['venta.created_at', '>=', DB::raw("NOW() - INTERVAL '100 HOURS'")],
                ['venta.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->OrWhere([[DB::raw("concat(venta.sigla, venta.numero_lote, venta.letra,'/', SUBSTRING ( venta.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['venta.estado', Estado::EnProceso], ['venta.created_at', '>=', DB::raw("NOW() - INTERVAL '200 HOURS'")],
                ['venta.created_at', '>=', "2023-06-28 00:00:00"],
                ['recepcionado_laboratorio', false]])
            ->orderByDesc('venta.id')
            ->paginate();

//100 hours
        return $ventas;
    }

    public function getEnsayos(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $estado = $request->estado;
        if (is_null($estado))
            $estado = '%';

        $fecha_inicial = date('Y-m-d');
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $fecha_final = date('Y-m-d', strtotime($fecha_final . ' + 1 days'));

        $ensayos = LaboratorioEnsayo::
        whereBetween('created_at', [$fecha_inicial, $fecha_final])
            ->where(function ($q) use ($txtBuscar) {
                $q->where('codigo', 'ilike', "%{$txtBuscar}%")
                    ->orWhereHas('formularioLiquidacion', function ($q) use ($txtBuscar) {
                        $q->whereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)",
                            ["%{$txtBuscar}%"]);
                    });
            })
            ->where(function ($q) use ($estado) {
                if ($estado != '%') {
                    $q->whereEsFinalizado($estado);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return $ensayos;
    }

    public function storeEstanio(Request $request)
    {
        try {

            $laboratorio = LaboratorioEnsayo::whereFormularioLiquidacionId($request->id)->first();

            if (is_null($laboratorio->fecha_analisis)) {
                $laboratorio->update(['fecha_analisis' => date('Y-m-d H:i:s')]);
            }

            $input = $request->all();
            $laboratorio = Laboratorio::whereLaboratorioEnsayoId($laboratorio->id)->whereMineralId(4)->whereOrigen("Empresa")->first();
            $input["valor"] = $request->factor * $request->ml / $request->peso_muestra;
            $laboratorio->fill($input);
            $laboratorio->save();

            return response()->json(['res' => true, 'message' => 'Laboratorio registrado correctamente con Ley: ' . round($laboratorio->valor, 2) . ' %']);

        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => $e]);

        }
    }

    public function storeHumedad(Request $request)
    {

        \DB::beginTransaction();
        try {
            $input = $request->all();
            $laboratorio = LaboratorioEnsayo::find($request->laboratorio_ensayo_id);
            $laboratorio->fill($input);
            $laboratorio->save();

            if (is_null($laboratorio->fecha_analisis)) {
                $laboratorio->update(['fecha_analisis' => date('Y-m-d H:i:s')]);
            }

            $labHumedad = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($request->laboratorio_ensayo_id)->orderByDesc('id')->first();
            if ($labHumedad) {
                $labHumedad = LaboratorioPesoHumedad::find($labHumedad->id);
                $labHumedad->fill($input);
                $labHumedad->save();
            } else {
                LaboratorioPesoHumedad::create($input);
            }
            try {
                $valor = ($request->peso_humedo - $request->peso_seco) / ($request->peso_humedo - $request->peso_tara) * 100;
                if ($valor < 0.10)
                    $valor = 0.10;
            } catch (\Exception $e) {
                $valor = 0;
            }

//
//            if ($request->tipo == TipoEnsayoHumedad::Duplicado) {
//                $valor2 = ($request->peso_humedo_dos - $request->peso_seco_dos) / ($request->peso_humedo_dos - $request->peso_tara_dos) * 100;
//                $valor = ($valor + $valor2) / 2;
//
//                $campo["laboratorio_ensayo_id"] = $request->laboratorio_ensayo_id;
//                $campo["peso_humedo"] = $request->peso_humedo_dos;
//                $campo["peso_seco"] = $request->peso_seco_dos;
//                $campo["peso_tara"] = $request->peso_tara_dos;
//                LaboratorioPesoHumedad::create($campo);
//            }
//
//            Laboratorio::whereLaboratorioEnsayoId($request->laboratorio_ensayo_id)->whereNull('mineral_id')->whereOrigen("Empresa")
//                ->update(['valor' => $valor]);
            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Humedad registrada correctamente con Ley: ' . round($valor, 2) . ' %']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['res' => false, 'message' => 'Ocurrió un error, revise e intente nuevamente']);

        }
    }

    public function cambiarEstado(Request $request)
    {
        \DB::beginTransaction();
        try {
            $lab = LaboratorioEnsayo::find($request->id);

            $form = FormularioLiquidacion::find($lab->formulario_liquidacion_id);
            if ($form->estado != Estado::EnProceso)
                return response()->json(['res' => false, 'message' => 'No se puede cambiar el estado de un lote liquidado']);

            $mensaje = 'restablecido';
            $fechaFinal = date('Y-m-d H:i:s');
            $fechaInicial = $lab->updated_at;
            $segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
            if ($segundos < 12 and $lab->es_finalizado) {
                if ($request->estado == "true") {
                    $mensaje = 'finalizado';
                }
                return response()->json(['res' => true, 'message' => 'Ensayo ' . $mensaje . ' correctamente']);
            }


            $lab->update(['es_finalizado' => $request->estado]);


            if ($request->estado == "true") {
///////
                $labHumedad = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($request->id)->orderByDesc('id')->first();

                $valor = ($labHumedad->peso_humedo - $labHumedad->peso_seco) / ($labHumedad->peso_humedo - $labHumedad->peso_tara) * 100;

                if ($valor < 0.10)
                    $valor = 0.10;
                $labo = Laboratorio::whereLaboratorioEnsayoId($request->id)->whereNull('mineral_id')->whereOrigen("Empresa")->orderByDesc('id')->first();
                $labo->update(['valor' => $valor]);

///////
                $lab->update(['fecha_finalizacion' => date('Y-m-d H:i:s')]);
                $mensaje = 'finalizado';

                ///////////adjuntar boleta de anticipo a documentos
                $objLaboratorio = new LaboratorioEnsayoController();
                $objCaja = new CajaController();
                $objLaboratorio->generarBoletaEnsayo($request->id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($lab->formulario_liquidacion_id);

                $res = $objCaja->subirDocumento($formularioLiquidacion);

//                if ($formularioLiquidacion->letra = 'D' and str_contains($lab->elementos, 'Sn')) {
//                    DocumentoCompra::whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->whereDescripcion(\App\Patrones\DocumentoCompra::LaboratorioEmpresa)
//                        ->update(['agregado' => true]);
//                }


                if (is_null($formularioLiquidacion->url_documento)) {
                    $formularioLiquidacion->url_documento = $formularioLiquidacion->id . '_document.pdf';
                    $formularioLiquidacion->save();
                }

                ///////////
            }
            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Ensayo ' . $mensaje . ' correctamente']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['res' => false, 'message' => 'Ocurrió un error, revise e intente nuevamente']);

        }
    }

    public function storeEnsayo(Request $request)
    {
        try {
            $ultimoLab = LaboratorioEnsayo::orderbyDesc('created_at')
                ->whereFormularioLiquidacionId($request->formulario_liquidacion_id)
                ->first();
            if ($ultimoLab) {
                $fechaInicial = $ultimoLab->created_at;

                $fechaFinal = date('Y-m-d H:i:s');
                $segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
                if ($segundos < 7) {
                    return response()->json(['res' => true, 'message' => 'Recepción registrada correctamente. Código: ' . $ultimoLab->codigo_lab]);
                }
            }
            $laboratorioEnsayo = LaboratorioEnsayo::whereFormularioLiquidacionId($request->formulario_liquidacion_id)->count();
            if ($laboratorioEnsayo > 0) {
                return response()->json(['res' => false, 'message' => 'Ya se registró el lote con anterioridad']);
            }
            $input = $request->all();
            $input['codigo'] = $this->getNumero();
            $lab = LaboratorioEnsayo::create($input);

            $this->registrarElemento($request->elementos, $request->formulario_liquidacion_id, $lab->id);
            return response()->json(['res' => true, 'message' => 'Recepción registrada correctamente. Código: ' . $lab->codigo_lab]);

        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => $e]);

        }
    }

    private function registrarElemento($elemento, $formulario_id, $ensayo_id)
    {
        if ($elemento == ElementoLaboratorio::EstanioHumedad) {
            Laboratorio::whereFormularioLiquidacionId($formulario_id)->whereNull('mineral_id')->whereOrigen("Empresa")
                ->update(['laboratorio_ensayo_id' => $ensayo_id]);
            Laboratorio::whereFormularioLiquidacionId($formulario_id)->whereMineralId(4)->whereOrigen("Empresa")
                ->update(['laboratorio_ensayo_id' => $ensayo_id]);
        } elseif ($elemento == ElementoLaboratorio::Estanio) {
            Laboratorio::whereFormularioLiquidacionId($formulario_id)->whereMineralId(4)->whereOrigen("Empresa")
                ->update(['laboratorio_ensayo_id' => $ensayo_id]);
        } elseif ($elemento == ElementoLaboratorio::Humedad) {
            Laboratorio::whereFormularioLiquidacionId($formulario_id)->whereNull('mineral_id')->whereOrigen("Empresa")
                ->update(['laboratorio_ensayo_id' => $ensayo_id]);
            FormularioLiquidacion::whereId($formulario_id)->update(['recepcionado_laboratorio' => true]);
        }
    }
//    public function storeElemento(Request $request){
//        try {
//            $input = $request->all();
//            if(is_null($request->mineral_id)){
//                Laboratorio::whereFormularioLiquidacionId($request->formulario_liquidacion_id)->whereNull('mineral_id')->whereOrigen("Empresa")
//                ->update(['laboratorio_ensayo_id' => $request->laboratorio_ensayo_id]);
//            }
//            else{
//                Laboratorio::whereFormularioLiquidacionId($request->formulario_liquidacion_id)->whereMineralId($request->mineral_id)->whereOrigen("Empresa")
//                    ->update(['laboratorio_ensayo_id' => $request->laboratorio_ensayo_id]);
//            }
//            return response()->json(['res' => true, 'message' => 'Elemento registrado correctamente: ']);
//
//        } catch (\Exception $e) {
//            return response()->json(['res' => false, 'message' => 'Ocurrió un error, revise e intente nuevamente']);
//
//        }
//    }

    private function getNumero()
    {
        $lab = LaboratorioEnsayo::max('codigo');
        return ($lab + 1);
    }

    public function getCantidadProceso(Request $request)
    {
        $ensayos = LaboratorioEnsayo::whereEsFinalizado(false)->count();

        return $ensayos;
    }

    /*public function arrayPaginator($array, $request)
    {
        $page = $request->page;
        $perPage = 15;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, false), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }*/
}
