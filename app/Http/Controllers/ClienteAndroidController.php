<?php

namespace App\Http\Controllers;

use App\Models\Anticipo;
use App\Models\Bono;
use App\Models\Cliente;
use App\Models\Cooperativa;
use App\Models\FormularioLiquidacion;
use App\Models\HistorialCliente;
use App\Models\Laboratorio;
use App\Models\Material;
use App\Models\PagoMovimiento;
use App\Models\Prestamo;
use App\Patrones\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Flash;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Luecano\NumeroALetras\NumeroALetras;

use Illuminate\Support\Facades\File;
use Karriere\PdfMerge\PdfMerge;
use Symfony\Component\Console\Input\Input;

class ClienteAndroidController extends AppBaseController
{
    public function arrayPaginator($array, $request)
    {
        $page = $request->page;
        $perPage = 15;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, false), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    public function listaPagos(Request $request)
    {
//        $txtBuscar = $request->txtBuscar;
//        if (is_null($txtBuscar))
//            $txtBuscar = '';
        $id = $request->cliente_id;
        $parametro = $request->parametro;
        $pagos =
            \DB::select("(SELECT
                pago_movimiento.id,
                pago_movimiento.monto,
                pago_movimiento.glosa,
                CONCAT(pago_movimiento.codigo,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as codigo,
                to_char(pago_movimiento.created_at,  'DD/MM/YYYY HH24:mi') as fecha_pago,
                CONCAT(formulario_liquidacion.sigla,formulario_liquidacion.numero_lote,formulario_liquidacion.letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as lote,
                bono.tipo,
                pago_movimiento.origen_type,
                pago_movimiento.origen_id
            FROM formulario_liquidacion
            INNER JOIN bono ON bono.formulario_liquidacion_id = formulario_liquidacion.id
            INNER JOIN pago_movimiento ON bono.id = pago_movimiento.origen_id
            where origen_type='App\Models\Bono' and pago_movimiento.alta= true and pago_movimiento.codigo like '%$parametro%' and formulario_liquidacion.cliente_id='$id'
            )
            UNION ALL
            (SELECT
                pago_movimiento.id,
                pago_movimiento.monto,
                pago_movimiento.glosa,
                CONCAT(pago_movimiento.codigo,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as codigo,
                to_char(pago_movimiento.created_at,  'DD/MM/YYYY HH24:mi') as fecha_pago,
                CONCAT(formulario_liquidacion.sigla,formulario_liquidacion.numero_lote,formulario_liquidacion.letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as lote,
                anticipo.tipo,
                pago_movimiento.origen_type,
                pago_movimiento.origen_id
            FROM formulario_liquidacion
            INNER JOIN anticipo ON anticipo.formulario_liquidacion_id = formulario_liquidacion.id
            INNER JOIN pago_movimiento ON anticipo.id = pago_movimiento.origen_id
            where origen_type='App\Models\Anticipo' and pago_movimiento.alta= true and pago_movimiento.codigo like '%$parametro%' and formulario_liquidacion.cliente_id='$id'

                )
            UNION ALL
            (SELECT
                pago_movimiento.id,
                pago_movimiento.monto,
                pago_movimiento.glosa,
                CONCAT(pago_movimiento.codigo,'/', anio) as codigo,
                to_char(pago_movimiento.created_at,  'DD/MM/YYYY HH24:mi') as fecha_pago,
								''  as lote,
                prestamo.tipo,
                pago_movimiento.origen_type,
                pago_movimiento.origen_id
            FROM prestamo
            INNER JOIN pago_movimiento ON prestamo.id = pago_movimiento.origen_id
            where origen_type='App\Models\Prestamo' and pago_movimiento.alta= true and pago_movimiento.codigo like '%$parametro%' and prestamo.cliente_id='$id'

                )
                UNION ALL
                (SELECT
                pago_movimiento.id,
                pago_movimiento.monto,
                pago_movimiento.glosa,
                CONCAT(pago_movimiento.codigo,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as codigo,
                to_char(pago_movimiento.created_at,  'DD/MM/YYYY HH24:mi') as fecha_pago,
                CONCAT(formulario_liquidacion.sigla,formulario_liquidacion.numero_lote,formulario_liquidacion.letra,'/', substring(cast(formulario_liquidacion.anio as varchar),3,4)) as lote,
                formulario_liquidacion.tipo,
                pago_movimiento.origen_type,
                pago_movimiento.origen_id
            FROM formulario_liquidacion

            INNER JOIN pago_movimiento ON formulario_liquidacion.id = pago_movimiento.origen_id
            where origen_type='App\Models\FormularioLiquidacion' and pago_movimiento.alta= true and pago_movimiento.codigo like '%$parametro%' and formulario_liquidacion.cliente_id='$id')

	        order by id desc");

        $page = 1;
        $size = 15;
        $data = $pagos;
        $collect = collect($data);

        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );

        return $this->arrayPaginator($pagos, $request);

    }

    public function autenticar(Request $request)
    {
        $cliente = Cliente::whereNit($request->nit)->wherePassword($request->password)->whereAlta(true)->first();
        if (empty($cliente))
            return response()->json(['res' => false], 200);
        else {
            $cliente->update(['ultimo_login' => date('Y-m-d H:i:s')]);
            return response()->json(['res' => true, 'id' => $cliente->id, 'nombre' => $cliente->nombre, 'cooperativa' => $cliente->cooperativa->razon_social, 'puntos' => $cliente->total_puntos], 200);
        }
    }

    public function cambiarPassword(Request $request)
    {
        $id = $request->cliente_id;
        $cliente = Cliente::find($id);

        if (empty($cliente)) {
            return response()->json(['res' => false, 'message' => "Cliente no encontrado"], 200);
        }

        Cliente::whereId($id)->update(['password' => $request->password]);
        return response()->json(['res' => true, 'message' => "Password cambiado correctamente"], 200);
    }


    public function getCompras(Request $request)
    {
        $id = $request->cliente_id;
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $formularioLiquidacions = DB::table('cliente')
            ->join('cooperativa', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->join('formulario_liquidacion', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
            ->join('chofer', 'formulario_liquidacion.chofer_id', '=', 'chofer.id')
            ->join('vehiculo', 'formulario_liquidacion.vehiculo_id', '=', 'vehiculo.id')
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'formulario_liquidacion.id', 'formulario_liquidacion.producto', 'formulario_liquidacion.boletas',
                DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as cliente"),
                DB::raw("concat(chofer.licencia, ' | ',chofer.nombre)   as chofer"),
                DB::raw("concat(vehiculo.placa, ' | ',vehiculo.marca)   as vehiculo"),
                'cooperativa.razon_social as cooperativa', 'peso_bruto',
                'sacos', 'chofer_id', 'vehiculo_id', 'cliente_id', 'tara', 'presentacion',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', '<>', Estado::Anulado],
                ['formulario_liquidacion.cliente_id', $id]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', '<>', Estado::Anulado], ['formulario_liquidacion.cliente_id', $id]])
            ->OrWhere([[DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', '<>', Estado::Anulado], ['formulario_liquidacion.cliente_id', $id]])
            ->orderByDesc('formulario_liquidacion.id')
            ->paginate(15);

//48 hours
        return $formularioLiquidacions;
    }

    public function imprimirAnticipo($id)
    {
        $anticipo = Anticipo::with('formularioLiquidacion')->find($id);
        $cliente = Cliente::find($anticipo->formularioLiquidacion->cliente_id);
        $fecha = $anticipo->created_at;

        $pago = PagoMovimiento::whereOrigenId($id)->whereOrigenType(Anticipo::class)->orderByDesc('id')->first();
        if ($pago)
            $fecha = $pago->created_at;

        $historial = Anticipo::whereFormularioLiquidacionId($anticipo->formularioLiquidacion->id)
            ->where('id', '<=', $id)->orderBy('id')->get();

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($anticipo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $vistaurl = "anticipos.imprimir";
        $view = \View::make($vistaurl, compact('anticipo', 'cliente', 'historial', 'literal', 'fecha'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboAnticipo-' . $anticipo->id . '-' . $anticipo->formularioLiquidacion->lote . '.pdf');
    }

    public function imprimirDevolucion($bonoId)
    {
        $devolucion = Bono::find($bonoId);
        $fecha = $devolucion->created_at;

        $pago = PagoMovimiento::whereOrigenId($bonoId)->where('origen_type', Bono::class)->orderByDesc('id')->first();
        if ($pago)
            $fecha = $pago->created_at;

        $bonos = PagoMovimiento::
        join('bono', 'bono.id', '=', 'pago_movimiento.origen_id')
            ->where('bono.es_cancelado', true)
            ->where('origen_type', Bono::class)
            ->where('pago_movimiento.id', '<=', $pago->id)
            ->whereAlta(true)
            ->where('bono.formulario_liquidacion_id', $devolucion->formulario_liquidacion_id)
            ->select('pago_movimiento.created_at', 'motivo', 'pago_movimiento.monto')
            ->paginate();

//        $bonos=Bono::whereFormularioLiquidacionId($bono->formulario_liquidacion_id)->get();


        $cliente = Cliente::find($devolucion->formularioLiquidacion->cliente_id);

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($devolucion->monto, 2, 'BOLIVIANOS', 'CENTAVOS');


        $vistaurl = "bonos.imprimir";
        $view = \View::make($vistaurl, compact('cliente', 'bonos', 'literal', 'devolucion', 'fecha'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboDevolucion-' . $devolucion->formularioLiquidacion->lote . '.pdf');
    }

    public function imprimirContrato($id)
    {

        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }

        $objRep = new ReporteController();

        $contrato = $objRep->reemplazarContrato($formularioLiquidacion);
        $contrato = $objRep->agregarFirma($formularioLiquidacion->cliente->firma, $contrato);
        $vistaurl = "formulario_liquidacions.contrato";
        $view = \View::make($vistaurl, compact('contrato'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
//        $pdf->setPaper(array(0,0,212.60,143.73));
        $pdf->setPaper('a4');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();

        return $pdf->stream('Contrato ' . $formularioLiquidacion->lote . '.pdf');
    }

    public function imprimirPrestamo($id)
    {
        $prestamo = PagoMovimiento::whereOrigenType(Prestamo::class)->whereOrigenId($id)->first();
        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney($prestamo->monto, 2, 'BOLIVIANOS', 'CENTAVOS');

        $prest = Prestamo::find($id);
        $vistaurl = "prestamos.imprimir";
        $view = \View::make($vistaurl, compact('prestamo', 'literal', 'prest'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        return $pdf->stream('reciboPrestamo-' . $prestamo->codigo . '.pdf');
    }

    public function registrarCliente(Request $request)
    {
        try {
            $input = $request->all();

            $clienteExiste = Cliente::whereNit($request->nit)->count();
            if ($clienteExiste > 0)
                return response()->json(['res' => false, 'message' => 'Ya existe un cliente con ese carnet']);

            $clienteExiste = Cliente::whereCelular($request->celular)->count();
            if ($clienteExiste > 0)
                return response()->json(['res' => false, 'message' => 'Ya existe un cliente con ese celular']);

            $input['password'] = $request->password;
            // MOMENTANEO
            $input['es_aprobado'] = true;

            if ($request->foto_anverso)
                $input['anverso'] = $this->subirArchivo($request->foto_anverso, 'anverso');
            else
                $input['anverso'] = 'blanco.png';


            if ($request->foto_reverso)
                $input['reverso'] = $this->subirArchivo($request->foto_reverso, 'reverso');
            else
                $input['reverso'] = 'blanco.png';

            if ($request->foto_rostro)
                $input['rostro'] = $this->subirArchivo($request->foto_rostro, 'rostro');
            else
                $input['rostro'] = 'blanco.png';

            if ($request->foto_firma)
                $input['firma'] = $this->subirArchivo($request->foto_firma, 'firma');
            else
                $input['firma'] = 'blanco.png';

            if (!isset($request->password))
                $input['password'] = $request->nit;

            $cliente = Cliente::create($input);

            if (isset($request->registrado_id)) {
                $clienteNuevo = DB::table('cliente')->where('id', $cliente->id)
                    ->select('nit', 'nombre', 'celular', 'firma')
                    ->get();
                $valores["valores_nuevos"] = $clienteNuevo;

                $valores["tipo"] = 'Registro';
                $valores["cliente_id"] = $cliente->id;
                $valores["registrado_id"] = $request->registrado_id;
                HistorialCliente::create($valores);
            }
            return response()->json(['res' => true, 'message' => 'Cliente guardado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => $e]);
        }
    }

    private function subirArchivo($imagen, $tipo, $formId=null)
    {
        $imagen = str_replace('data:image/png;base64,', '', $imagen);
        $imagen = str_replace(' ', '+', $imagen);
        $nombreArchivo = time() . '.png';
        if ($tipo == 'firma')
            \File::put(public_path() . '/firmas/' . $nombreArchivo, base64_decode($imagen));

        else if($tipo=='laboratorio'){
            \File::put(public_path() . '/clientesfotos/' . $formId . '.png', base64_decode($imagen));
        }
        else
            \File::put(public_path() . '/clientesfotos/' . $tipo . '/' . $nombreArchivo, base64_decode($imagen));



        return $nombreArchivo;
    }

    public function getCooperativas()
    {
        $productores = Cooperativa::orderBy('razon_social')->select('id', 'razon_social', 'nit')->get();
        return $productores;
    }

    public function getClientes(Request $request)
    {
        $txtBuscar = $request->parametro;

        $clientes = DB::table('cliente')
            ->join('cooperativa', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->select(
                'cliente.nit', 'cliente.id', 'cliente.nombre', 'cooperativa.razon_social', 'cliente.celular', 'cliente.firma', 'cliente.es_editable')
            ->where("cliente.nit", 'ilike', ["%{$txtBuscar}%"])
            ->Orwhere("cliente.nombre", 'ilike', ["%{$txtBuscar}%"])
            ->orderBy('cliente.nombre')
            ->paginate(15);

//48 hours
        return $clientes;
    }

    public function updateCliente(Request $request)
    {
        \DB::beginTransaction();
        try {
            $cliente = Cliente::find($request->id);
            $clienteCi = Cliente::whereNit($request->nit)->where('id', '<>', $cliente->id)->count();


            if (!$cliente->es_editable) {
                return response()->json(['res' => false, 'message' => 'El cliente ya se editó anteriormente']);
            }
            if ($clienteCi > 0) {
                return response()->json(['res' => false, 'message' => 'Ya existe un carnet con otro registro']);
            }

            $clienteAntiguo = DB::table('cliente')->where('id', $request->id)
                ->select('nit', 'nombre', 'celular', 'firma')
                ->get();
            $valores["valores_antiguos"] = $clienteAntiguo;

            $input = $request->all();
                $input['firma'] = $this->subirArchivo($request->foto_input, 'firma');

            $input['anverso'] = $this->subirArchivo($request->foto_anverso, 'anverso');
            $input['reverso'] = $this->subirArchivo($request->foto_reverso, 'reverso');

            $input['es_asociado'] = true;
            $input['es_editable'] = false;

            $cliente->fill($input);
            $cliente->save();

            $clienteNuevo = DB::table('cliente')->where('id', $request->id)
                ->select(
                    'nit', 'nombre', 'celular', 'firma')
                ->get();
            $valores["valores_nuevos"] = $clienteNuevo;

            $valores["tipo"] = 'Edición';
            $valores["cliente_id"] = $cliente->id;
            $valores["registrado_id"] = $request->registrado_id;
            HistorialCliente::create($valores);
            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Cliente guardado correctamente.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function getMisLaboratorios($formId)
    {
        $laboratorios = Laboratorio::whereFormularioLiquidacionId($formId)
            ->whereNotNull('mineral_id')
            ->whereOrigen('Cliente')
            ->select('id', 'valor', 'unidad', 'mineral_id', 'formulario_liquidacion_id')
            ->get();

        return $laboratorios;
    }

    public function getMisLaboratoriosEmpresa($formId)
    {
        $laboratorios = Laboratorio::whereFormularioLiquidacionId($formId)
            ->select('id', 'valor', 'unidad', 'mineral_id', 'formulario_liquidacion_id', 'origen')
            ->get();

        $promedios = FormularioLiquidacion::find($formId);
        return response()->json(['laboratorios' => $laboratorios, 'promedios' => $promedios->laboratorio_promedio]);
    }

    public function updateLaboratorio(Request $request)
    {

       // error_log('Some message here.=================== '.$request->foto_lab->getClientOriginalName());

        \DB::beginTransaction();
        try {

            if (isset($request->id_plata)) {
                $lab = Laboratorio::whereId($request->id_plata)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(1)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }

                $lab->update(['valor' => $request->valor_plata]);
            }
            if (isset($request->id_plomo)) {
                $lab = Laboratorio::whereId($request->id_plomo)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(2)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }

                $lab->update(['valor' => $request->valor_plomo]);
            }

            if (isset($request->id_zinc)) {
                $lab = Laboratorio::whereId($request->id_zinc)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(3)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }

                $lab->update(['valor' => $request->valor_zinc]);
            }

            if (isset($request->id_estanio)) {
                $lab = Laboratorio::whereId($request->id_estanio)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(4)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }


                $lab->update(['valor' => $request->valor_estanio]);
            }
            if (isset($request->id_antimonio)) {
                $lab = Laboratorio::whereId($request->id_antimonio)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(6)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }

                $lab->update(['valor' => $request->valor_antimonio]);
            }
            if (isset($request->id_cobre)) {
                $lab = Laboratorio::whereId($request->id_cobre)->whereOrigen('Cliente')->first();
                $empresa = Laboratorio::where('origen', 'Empresa')->whereMineralId(5)
                    ->where('valor', '<>', 0)->whereFormularioLiquidacionId($lab->formulario_liquidacion_id)->first();
                if(!$empresa){
                    return response()->json(['res' => false, 'message' => 'Primero Colquechaca Mining debe llenar los datos que hicieron analizar']);
                }

                $lab->update(['valor' => $request->valor_cobre]);
            }

            $formularioLiquidacion=FormularioLiquidacion::whereId($lab->formulario_liquidacion_id)->whereEstado(Estado::EnProceso)->first();
//
//            if(!$formularioLiquidacion){
//                return response()->json(['res' => false, 'message' => 'No se encuentra el lote, o este ya fue liquidado']);
//            }
//            $objLab = new LaboratorioController();
//            $objLab->actualizarRegalia($lab->formulario_liquidacion_id);
//
//            $objValor = new ValorPorToneladaController();
//            $objValor->updateValorPorTonelada($lab->formulario_liquidacion_id);

            $ruta = public_path("clientesfotos/laboratorio/".$lab->formulario_liquidacion_id.".png");
            error_log('Some message here.===================lddlllllllllllllllllllll '. $request->foto_lab);

            $imagen = str_replace('data:image/jpeg;base64,', '',  $request->foto_lab);
            $imagen = str_replace(' ', '+', $imagen);

//            $file->move(public_path('clientesfotos/laboratorio'), $lab->formulario_liquidacion_id.".png");
            file_put_contents($ruta,  base64_decode($imagen));


  //          move_uploaded_file($request->foto_lab,$ruta);
//            $imagen = str_replace('data:image/jpg,', '', $request->foto_lab);
//            $imagen = str_replace(' ', '+', $imagen);
//
    //         \File::put(public_path() . '/clientesfotos/laboratorio/' . $lab->formulario_liquidacion_id . '.jpg', base64_decode($imagen));
        //    copy(base64_decode($request->foto_lab),$ruta);

//
            error_log('Some message here.===================0000000lllllllllllllllllllll ');

            $this->generarAnalisisCliente($formularioLiquidacion->id);


            $nombreArchivoForm= public_path() .'/clientes/'.$formularioLiquidacion->id.'.pdf';
            //unir varios pdf's en uno
            $pdf = new \PDFMerger;
            $pdf->addPDF($nombreArchivoForm, 'all');

            //adjuntando los documentos ya anteriormente registrados
            if (!is_null($formularioLiquidacion->url_documento)) {
                $file_url = public_path() . "/documents/" . $formularioLiquidacion->url_documento;
                if (file_exists($file_url))
                    $pdf->addPDF($file_url, 'all');
            }

            //juntando todos los documentos
            $nombreArchivo = $formularioLiquidacion->id . '_document' . '.pdf';
            $pdf->merge('file', public_path() . "/documents/" . $nombreArchivo);
            File::move($nombreArchivoForm, public_path() .'/clientes/'.$formularioLiquidacion->id.'.pdf');

            File::delete($nombreArchivoForm);

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Análisis guardado correctamente.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            error_log('Some message here.===================llllllllllllllllllllll '. $e);

            return $this->make_exception($e);
        }
    }

    public function generarAnalisisCliente($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::find($id);
        if (empty($formularioLiquidacion)) {
            return response()->json(['msg' => 'Formulario Liquidación no encontrado']);
        }

        $vistaurl = "clientes.laboratorio_cliente";
        $view = \View::make($vistaurl, compact('id'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $file_url = public_path() . "/clientes/" . $id . '.pdf';
        file_put_contents($file_url, $pdf->output());
        return $pdf->output();
    }

}
