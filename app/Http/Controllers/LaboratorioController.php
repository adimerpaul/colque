<?php

namespace App\Http\Controllers;


use App\Events\AccionCompleta;
use App\Models\Costo;
use App\Models\CotizacionDiaria;
use App\Models\FormularioLiquidacion;
use App\Models\Laboratorio;
use App\Http\Controllers\AppBaseController;
use App\Models\LaboratorioEnsayo;
use App\Models\Material;
use Illuminate\Http\Request;
use Flash;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function MongoDB\BSON\toJSON;

class LaboratorioController extends AppBaseController
{

    public function actualizar($id, $valor)
    {
        $mensaje = 'Valor actualizado';
        $lab = Laboratorio::find($id);


        if ($lab->valor != $valor){
            //Es humedad
            if (is_null($lab->mineral_id)) {
                if ($lab->origen == 'Empresa') {
                    return response()->json(['res' => false, 'message' => "No se puede registrar la humedad analizada por la empresa"]);
                }
                $this->updateValor($lab, $valor, true);


                $this->actualizarPesoNetoSeco($lab->formulario_liquidacion_id);
                $this->actualizarRegalia($lab->formulario_liquidacion_id);

                return response()->json(['res' => true, 'message' => $mensaje]);
            } // no es humedad
            else {
                $this->updateValor($lab, $valor, false);
                return response()->json(['res' => true, 'message' => $mensaje]);
            }
        }

    }



    private function updateValor($lab, $valor, $esHumedad)
    {
            $lab->update(array('valor' => $valor));
            $lab->save();

            if ($esHumedad)
                event(new AccionCompleta("Modificado", "Laboratorio de Humedad modificado", $lab->formulario_liquidacion_id));
            else {
              //  $this->actualizarRegalia($lab->formulario_liquidacion_id);
                event(new AccionCompleta("Modificado", "Laboratorio de " . $lab->mineral->nombre . " modificado", $lab->formulario_liquidacion_id));
            }

    }

    public function copiarACliente($formularioId)
    {
        $laboratorioCliente = Laboratorio::whereFormularioLiquidacionId($formularioId)->whereOrigen('Cliente')->orderBy('mineral_id')->get();
        $laboratorioEmpresa = Laboratorio::whereFormularioLiquidacionId($formularioId)->whereOrigen('Empresa')->orderBy('mineral_id')->get();
        for ($i = 0; $i < $laboratorioCliente->count(); $i++) {
            Laboratorio::where('id', $laboratorioCliente[$i]->id)->update(['valor' => $laboratorioEmpresa[$i]->valor]);
        }
        $this->actualizarPesoNetoSeco($formularioId);
        $this->actualizarRegalia($formularioId);
        event(new AccionCompleta("Modificado", "Laboratorio copiado de Empresa a Cliente", $formularioId));
        return response()->json(['res' => true, 'message' => 'Copia realizada correctamente']);
    }

    public function actualizarUnidad($formulario_id, $mineral_id, $unidad)
    {
        try {
            $laboratorio = Laboratorio::where('mineral_id', $mineral_id)->where('formulario_liquidacion_id', $formulario_id)->update(['unidad' => $unidad]);
            event(new AccionCompleta("Modificado", "Unidad de laboratorio modificada", $formulario_id));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function contarDirimicion($formulario_id)
    {
        $contadorDirimicion = Laboratorio::where('formulario_liquidacion_id', $formulario_id)->whereOrigen('Dirimicion')->whereNotNull('valor')->count();
        return $contadorDirimicion;
    }

    public function quitarDirimicion($formulario_id)
    {
        Laboratorio::where('formulario_liquidacion_id', $formulario_id)->whereOrigen('Dirimicion')->update(['valor' => null]);
        $this->actualizarPesoNetoSeco($formulario_id);
        $this->actualizarRegalia($formulario_id);

        Costo::where('formulario_liquidacion_id', $formulario_id)->update(['dirimicion' => 0]);
        event(new AccionCompleta("Modificado", "Dirimición eliminada", $formulario_id));

        return json_encode(true);

    }

    public function eliminar($origen, $id)
    {
        Laboratorio::where('formulario_liquidacion_id', $id)->where('origen', $origen)->update(['valor' => null]);
        $this->actualizarPesoNetoSeco($id);
        $this->actualizarRegalia($id);

        return true;
    }

    private function actualizarPesoNetoSeco($formularioLiquidacionId)
    {
        $formulario = FormularioLiquidacion::findOrFail($formularioLiquidacionId);
        FormularioLiquidacion::where('id', $formularioLiquidacionId)->update(['peso_seco' => $formulario->peso_neto_seco]);
        FormularioLiquidacion::where('id', $formularioLiquidacionId)->update(['neto_venta' => $formulario->valor_neto_venta]);

        return true;
    }

    public function actualizarRegalia($formularioId)
    {
        $formulario = FormularioLiquidacion::findOrFail($formularioId);
        FormularioLiquidacion::where('id', $formularioId)
            ->update(['regalia_minera' => $formulario->totales['total_minerales'],
                'humedad_kilo' => $formulario->humedad_kg,
                'humedad_promedio' => $formulario->humedad,
                'total_retencion_descuento' => ($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos'])]);

        if ($formulario->letra == 'D')
            FormularioLiquidacion::where('id', $formularioId)->update(['ley_sn' => $formulario->ley_estanio]);
    }


    public function getPromedios2($formulario_id)
    {
        $labsEmpresa = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad, m.margen_error, m.margen_maximo,
           round((l.valor), 5) as valor
                                from laboratorio l left join mineral m
                                     on l.mineral_id = m.id
                                where l.formulario_liquidacion_id = ? and l.origen = 'Empresa'
                                group by l.mineral_id, m.nombre, m.simbolo, l.unidad, l.valor, m.margen_error, m.margen_maximo
                                order by l.mineral_id", [$formulario_id]);

        $labsCliente = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad, m.margen_error, m.margen_maximo,
           round((l.valor), 5) as valor
                                from laboratorio l left join mineral m
                                     on l.mineral_id = m.id
                                where l.formulario_liquidacion_id = ? and l.origen = 'Cliente'
                                group by l.mineral_id, m.nombre, m.simbolo, l.unidad, l.valor, m.margen_error, m.margen_maximo
                                order by l.mineral_id", [$formulario_id]);


        $dirimendos = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad,
           round(l.valor, 5) as valor
                                from laboratorio l left join mineral m
                                     on l.mineral_id = m.id
                                where l.formulario_liquidacion_id = ? and l.origen = 'Dirimicion'
                                order by l.mineral_id", [$formulario_id]);


        $promedios = [];
        foreach ($labsEmpresa as $row) {
            $dirimendo = collect($dirimendos)->where('simbolo', $row->simbolo)->first();
            $clientes = collect($labsCliente)->where('simbolo', $row->simbolo)->first();

            if ($row->valor == 0.00) {
                $promedio = $row->valor;
            } else {
                if ($row->mineral == 'Humedad') {
                    $promedio = $row->valor;
                } else {

                    if ($clientes->valor == 0.00 or ($dirimendo->valor<$row->valor and $dirimendo->valor!=0.00)) {
                        $promedio = $row->valor; //estanio
                        if ($row->mineral_id == 1 or $row->mineral_id == 2 or $row->mineral_id == 3 or $row->mineral_id == 5 or $row->mineral_id == 6)
                            $promedio = $row->valor + 0.5;
                        if ($row->mineral_id == 1 and $row->valor > 100) {
                            $promedio = $row->valor + 0.25;
                        }
                    } else {
                        $diferencia = abs($row->valor - $clientes->valor);
                        $margenMaximo = $row->margen_maximo;
                        if ($row->mineral_id == 1 and $row->valor > 100)
                            $margenMaximo = 6.00;

                        $margenError= $row->margen_error;
                        if ($row->mineral_id == 1 and $row->valor > 100)
                            $margenError = 5.00;

                        if ($diferencia <= $margenError) {
                            $promedio = ($row->valor + $clientes->valor) / 2;
                        } elseif ($diferencia <= $margenMaximo) {
                            $mayor = $row->valor;
                            $menor = $clientes->valor;
                            if ($mayor < $menor) {
                                $menor = $row->valor;
                            }
                            $mayor = $menor + $margenError;
                            $promedio = ($mayor + $menor) / 2;
                        } else {
                            $promedio = $dirimendo->valor;
                        }
                    }
                }
            }

            $promedios[] = (object)[
                'mineral_id' => $row->mineral_id,
                'simbolo' => $row->simbolo,
                'mineral' => $row->mineral,
                'unidad' => $row->unidad,
                'promedio' => (float)$promedio,
            ];
        }


        return $promedios;
    }

    public function getPromedios($formulario_id)
    {
        $form = FormularioLiquidacion::find($formulario_id);
        if ($form->fecha_hora_liquidacion > '2023-02-21 00:00:00' or is_null($form->fecha_hora_liquidacion)) {
            $laboratorios = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad,
           round(avg(l.valor), 5) as valor
                                from laboratorio l left join mineral m
                                     on l.mineral_id = m.id
                                where l.formulario_liquidacion_id = ? and l.origen <> 'Dirimicion'
                                group by l.mineral_id, m.nombre, m.simbolo, l.unidad
                                order by l.mineral_id", [$formulario_id]);


            $dirimendos = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad,
           round(l.valor, 5) as valor
                                from laboratorio l left join mineral m
                                     on l.mineral_id = m.id
                                where l.formulario_liquidacion_id = ? and l.origen = 'Dirimicion'
                                order by l.mineral_id", [$formulario_id]);
        } else {
            $laboratorios = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad,
       round(avg(l.valor), 2) as valor
                            from laboratorio l left join mineral m
                                 on l.mineral_id = m.id
                            where l.formulario_liquidacion_id = ? and l.origen <> 'Dirimicion'
                            group by l.mineral_id, m.nombre, m.simbolo, l.unidad
                            order by l.mineral_id", [$formulario_id]);


            $dirimendos = \DB::select("select l.mineral_id, coalesce(m.simbolo, 'H2O') as simbolo, coalesce(m.nombre, 'Humedad') as mineral, l.unidad,
       round(l.valor, 2) as valor
                            from laboratorio l left join mineral m
                                 on l.mineral_id = m.id
                            where l.formulario_liquidacion_id = ? and l.origen = 'Dirimicion'
                            order by l.mineral_id", [$formulario_id]);
        }


        $promedios = [];
        foreach ($laboratorios as $row) {
            $dirimendo = collect($dirimendos)->where('simbolo', $row->simbolo)->first();
            $promedio = is_null($dirimendo->valor) ? $row->valor : $dirimendo->valor;
            $promedios[] = (object)[
                'mineral_id' => $row->mineral_id,
                'simbolo' => $row->simbolo,
                'mineral' => $row->mineral,
                'unidad' => $row->unidad,
                'promedio' => (float)$promedio,
            ];
        }

        return $promedios;
    }

    public function getLaboratorios($form_id)
    {
        //$formulario = FormularioLiquidacion::find($form_id);
        //$letra = $formulario->letra;

        $laboratorios = Laboratorio::select('mineral_id', 'formulario_liquidacion_id', 'unidad')
            ->whereFormularioLiquidacionId($form_id)
            ->whereNotNull('mineral_id')
//            ->where(function ($q) use ($letra) {
//                if($letra != 'F'){
//                    $q->whereEsPenalizacion(false);
//                }
//            })
            ->whereEsPenalizacion(false)
            ->groupBy('mineral_id', 'unidad', 'formulario_liquidacion_id')
            ->orderBy('mineral_id')
            ->get();
        $laboratoriosEmpresas = Laboratorio::whereOrigen('Empresa')->whereFormularioLiquidacionId($form_id)
//            ->where(function ($q) use ($letra) {
//                if($letra != 'F'){
//                    $q->whereEsPenalizacion(false);
//                }
//            })
            ->whereEsPenalizacion(false)
            ->orderBy('mineral_id')->get();

        $laboratoriosClientes = Laboratorio::whereOrigen('Cliente')->whereFormularioLiquidacionId($form_id)
//            ->where(function ($q) use ($letra) {
//                if($letra != 'F'){
//                    $q->whereEsPenalizacion(false);
//                }
//            })
            ->whereEsPenalizacion(false)
            ->orderBy('mineral_id')->get();

        $laboratoriosDirimiciones = Laboratorio::whereOrigen('Dirimicion')->whereFormularioLiquidacionId($form_id)
//            ->where(function ($q) use ($letra) {
//                if($letra != 'F'){
//                    $q->whereEsPenalizacion(false);
//                }
//            })
            ->whereEsPenalizacion(false)
            ->orderBy('mineral_id')->get();

        return response()->json(['res' => true, 'laboratoriosEmpresas' => $laboratoriosEmpresas,
            'laboratoriosClientes' => $laboratoriosClientes, 'laboratoriosDirimiciones' => $laboratoriosDirimiciones
            , 'laboratorios' => $laboratorios]);

    }

    public function imprimirInforme($id)
    {
        $ensayo = LaboratorioEnsayo::find($id);
        $laboratorios = Laboratorio::whereLaboratorioEnsayoId($id)->get();

        if (empty($ensayo)) {
            return response()->json(['msg' => 'Ensayo no encontrado']);
        }
        //generador qr
        $urlQR = url("/api/informe-ensayo/{$ensayo->id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $vistaurl = "reportes.informe_laboratorio";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('ensayo', 'laboratorios', 'qrcode'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $fechaImpresion = date('d/m/Y H:i');
        if (!is_null($ensayo->fecha_finalizacion))
            $fechaImpresion = date('d/m/Y H:i', strtotime($ensayo->fecha_finalizacion));
        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));
        return $pdf->stream('InformeEnsayo ' . $ensayo->lote . '.pdf');
    }

    public function imprimirInformeAnimas($lote1, $lote2)
    {
        $laboratorios = Laboratorio::
//            where('updated_at', '>', '2023-06-18 00:00:00 ')
//            ->
        whereNull('mineral_id')
            ->whereOrigen('Empresa')
            ->whereHas('formularioLiquidacion', function ($q) use ($lote1, $lote2) {
                $q->where('numero_lote', '>=', $lote1)->where('numero_lote', '<=', $lote2)
                    ->whereLetra('E')
                    ->where('estado', '<>', 'Anulado')
                    ->where('anio', 2024)
                    ->whereHas('cliente', function ($q) {
                        $q->where('cooperativa_id', 44);

                    });
            })
            ->orderBy('formulario_liquidacion_id')
            ->get();

        //generador qr
        $urlQR = url("/informe-animas/$lote1/$lote2");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));


        $vistaurl = "reportes.informe_animas";
//        $view = PDF::loadView('$vistaurl', $formularioLiquidacion, $qrcode); // \View::make($vistaurl, compact('formularioLiquidacion', 'qrcode'))->render();
        $view = \View::make($vistaurl, compact('laboratorios', 'qrcode', 'lote1', 'lote2'))->render(); //PDF::loadView('reportes.pesaje', $formularioLiquidacion, $qrcode);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
//        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));
//
//        $fechaImpresion = date('d/m/Y H:i');
//        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));

        return $pdf->stream('InformeEnsayoAnimas.pdf');
    }
}
