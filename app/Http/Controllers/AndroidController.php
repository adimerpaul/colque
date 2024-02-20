<?php

namespace App\Http\Controllers;

use App\Events\AccionCompleta;
use App\Http\Requests\CreateFormularioLiquidacionRequest;
use App\Http\Requests\UpdateFormularioLiquidacionRequest;
use App\Models\Anticipo;
use App\Models\Bono;
use App\Models\CambioFormulario;
use App\Models\CampoReporte;
use App\Models\Cliente;
use App\Models\Concentrado;
use App\Models\Cooperativa;
use App\Models\Costo;
use App\Models\CotizacionDiaria;
use App\Models\CuentaCobrar;
use App\Models\DescuentoBonificacion;
use App\Models\DocumentoCompra;
use App\Models\FormularioAndroid;
use App\Models\FormularioDescuento;
use App\Models\FormularioLiquidacion;
use App\Models\FormularioLiquidacionData;
use App\Models\Historial;
use App\Models\HistorialCuentaCobrar;
use App\Models\Laboratorio;
use App\Models\LaboratorioPrecio;
use App\Models\PagoMovimiento;
use App\Models\Prestamo;
use App\Models\Producto;
use App\Models\TablaAcopiadora;
use App\Models\TablaAcopiadoraDetalle;
use App\Models\TipoCambio;
use App\Models\UbicacionFormulario;
use App\Models\Venta;
use App\Models\VentaFormularioLiquidacion;
use App\Patrones\AccionCambioFormulario;
use App\Patrones\AccionHistorialCuenta;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\ClaseDevolucion;
use App\Patrones\Empaque;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Models\LiquidacionMineral;
use App\Patrones\Permiso;
use App\Patrones\Rol;
use App\Patrones\TipoDescuentoBonificacion;
use App\Patrones\TipoLoteVenta;
use App\Patrones\TipoMaterial;
use App\Repositories\FormularioLiquidacionRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function PHPUnit\Framework\isNull;

class AndroidController extends AppBaseController
{

    private function getNumero($producto)
    {
        $fechaActual = Fachada::getFecha();
        $fechaInicioGestion = \DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime(date('Y') . "-10-01")));

        $anio = date('Y');
        if ($fechaActual >= $fechaInicioGestion)
            $anio += 1;

        if ($producto == 'D | Estaño' or $producto == 'E | Plata' or $producto== 'F | Antimonio' or $producto== 'F | Antimonio Oro' or $producto == 'G | Cobre') {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->where('producto', 'ilike','%'.$producto.'%')->max('numero_lote');
        } else {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo'])->max('numero_lote');
        }
        return ['numero' => $formulario + 1, 'anio' => $anio];

    }

    public function registrar(Request $request, $esEdicion = false)
    {
        //error_log('Some message here.=================== '.$request->userAgent());
        if (!str_contains($request->userAgent(), 'KFTRWI') and !str_contains($request->userAgent(), 'KFMAWI'))
            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
//        if($ip!=='192.168.100.44')
//            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
//            if(isset($request->peso_de_balanza) and $request->peso_bruto<3000.00)
//                return response()->json(['res' => false, 'message' => 'No se puede ingresar el peso manualmente cuando es menor a 3 toneladas']);
        $ultimoForm = FormularioLiquidacion::orderbyDesc('created_at')->first();
        $fechaInicial = $ultimoForm->created_at;
        $fechaFinal = date('Y-m-d H:i:s');
        //error_log('Some message here._______________________________________________'.$fechaFinal);
        $segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
        if ($segundos < 15 and !$esEdicion) {
            return response()->json(['res' => true, 'message' => 'Pesaje creado correctamente', 'id' => $ultimoForm->id, 'cliente' => $ultimoForm->cliente->nombre]);
        }

        \DB::beginTransaction();
        try {
            $input = $request->all();
            $producto = Producto::findOrFail($request->producto_id);
            $tipoCambio = TipoCambio::orderByDesc('id')->first();
            $input['tipo_cambio_id'] = $tipoCambio->id;
            $input['fecha_cotizacion'] = Fachada::getFecha();
            $input['fecha_liquidacion'] = Fachada::getFecha();
            $input['fecha_pesaje'] = date('Y-m-d');

            //sigla o numero de lote
            $input['sigla'] = 'CM';
            $input['numero_lote'] = $this->getNumero($producto->info)['numero'];
            $input['letra'] = $producto->letra;
            $input['anio'] = $this->getNumero($producto->info)['anio'];
            $input['producto'] = $producto->info;
            $input['valor_por_tonelada'] = null;
            $input['chofer_id'] = 1;
            $input['vehiculo_id'] = 1;

            if (is_null($request->peso_bruto))
//            {
                $input['peso_bruto'] = 0;
//                $input['tara'] = 0;
//                $input['sacos'] = 0;
//            } else {
//                $input['tara'] = (0.1 * $request->sacos);
//            }
// plata brosa tiene merma, plata fina tara..... estaño tiene tara 0225 y merma 0... ab c
            //if ($request->presentacion == Empaque::Ensacado) {
            if ($producto->letra == 'D') {
                $input['tara'] = (0.250 * $request->sacos);
                $input['presentacion'] = Empaque::Ensacado;
                $input['merma'] = 0;
                $input['ley_sn'] = 0;
            } elseif ($producto->letra == 'E') {
                $input['con_cotizacion_promedio'] = true;
                $input['presentacion'] = Empaque::Ensacado;
                $input['tara'] = (0.225 * $request->sacos);
                $input['merma'] = 0;

                if ($request->tipo_material == TipoMaterial::Broza) {
                    $input['merma'] = 1;
                    $input['tara'] = 0;
                }
            } else {
                $input['sacos'] = 0;
                $input['presentacion'] = Empaque::AGranel;
            }
            $input['en_molienda'] = false;
            if ($request->en_molienda == 'Si')
                $input['en_molienda'] = true;

            $input['ip_tablet'] = $request->ip();
            $input['dispositivo'] = $request->userAgent();
            $formularioLiquidacion = FormularioLiquidacion::create($input);
            //registrando deuda anterior si el total es mayor a 0

            $this->registrarDeudaAnterior($formularioLiquidacion);

            //registro de minerales
            $productoMinerales = $producto->productoMinerals;
            foreach ($productoMinerales as $row) {
                if (!$row->es_penalizacion) {
                    $campos['es_penalizacion'] = $row->es_penalizacion;
                    $campos['ley_minima'] = ($formularioLiquidacion->letra == 'E' and $formularioLiquidacion->tipo_material == TipoMaterial::Broza) ? 4.00 : $row->ley_minima;
                    $campos['formulario_liquidacion_id'] = $formularioLiquidacion->id;
                    $campos['mineral_id'] = $row->mineral_id;
                    LiquidacionMineral::create($campos);
                }
            }
            $precio = LaboratorioPrecio::whereLaboratorioQuimicoId(1)->whereProductoId($request->producto_id)->first();

            //registro de costos
            \DB::table('costo')->insert([
                'tratamiento' => $producto->costo_tratamiento,
                'laboratorio' => $precio->monto,
                'pesaje' => $producto->costo_pesaje,
                'comision' => $producto->costo_comision,
                'publicidad' => $producto->costo_publicidad,
                'pro_productor' => $producto->pro_productor,
                'dirimicion' => 0,
                'formulario_liquidacion_id' => $formularioLiquidacion->id,
            ]);
////laboratorios

            foreach ($productoMinerales as $row) {
                if (!$row->es_penalizacion) {
                    $lab['valor'] = 0;
                    $lab['unidad'] = $row->mineral->unidad_laboratorio;
                    $lab['origen'] = 'Empresa';
                    $lab['formulario_liquidacion_id'] = $formularioLiquidacion->id;
                    $lab['mineral_id'] = $row->mineral_id;
                    $lab['es_penalizacion'] = $row->es_penalizacion;
                    Laboratorio::create($lab);

                    $lab['origen'] = 'Cliente';
                    Laboratorio::create($lab);

                    $lab['valor'] = null;
                    $lab['origen'] = 'Dirimicion';
                    Laboratorio::create($lab);
                }
            }

            $humedad['valor'] = 0;
            $humedad['unidad'] = '%';
            $humedad['origen'] = 'Empresa';
            $humedad['formulario_liquidacion_id'] = $formularioLiquidacion->id;
            $humedad['mineral_id'] = null;
            Laboratorio::create($humedad);

            $humedad['origen'] = 'Cliente';
            Laboratorio::create($humedad);

            $humedad['valor'] = null;
            $humedad['origen'] = 'Dirimicion';
            Laboratorio::create($humedad);

////////////////////


            //Descuentos bonificaciones
            $cliente = Cliente::find($request->cliente_id);
            if ($cliente->cooperativa_id == 28) { //Colquechaca RL
                $seccion = 'seccion';
                $descuentos = DescuentoBonificacion::whereCooperativaId($cliente->cooperativa_id)
                    ->where('nombre', 'not ilike', "%{$seccion}%")
                    ->where('agregado_por_defecto', true )
                    ->where('nombre', '<>', 'BONO PROVEEDOR' )
                    ->whereAlta(true)->orderBy('id')->get();
            } else {
                $descuentos = DescuentoBonificacion::whereCooperativaId($cliente->cooperativa_id)
                    ->where('id', '<>', 99) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('id', '<>', 208) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('id', '<>', 158) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('id', '<>', 364) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('id', '<>', 365) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('id', '<>', 367) ///solucion momentanea para descuento q ya no se utiliza
                    ->where('agregado_por_defecto', true )
                    ->where('nombre', '<>', 'BONO PROVEEDOR' )
                    ->whereAlta(true)->orderBy('id')->get();
            }
            $this->storeDescuentos($formularioLiquidacion->id, $request->cliente_id, $descuentos, $producto->letra);

            $objDocumento = new DocumentoController();
            $objDocumento->storeDocumentosCompras($formularioLiquidacion->id);

            ///////////adjuntar boleta de pesaje a documentos
            $objRep = new ReporteController();
            $objCaja = new CajaController();
            $objRep->generarBoletaPesaje($formularioLiquidacion->id);

            $formularioLiquidacion = FormularioLiquidacion::findOrFail($formularioLiquidacion->id);

            $contarRepetidos = FormularioLiquidacion::whereNumeroLote($formularioLiquidacion->numero_lote)
                ->whereAnio($formularioLiquidacion->anio)
                ->whereLetra($formularioLiquidacion->letra)
                ->count();

            //error_log('Some message here.=================================================================='.$contarRepetidos);
            if ($contarRepetidos > 1) {
                $anteriorForm = FormularioLiquidacion::whereNumeroLote($formularioLiquidacion->numero_lote)
                    ->whereAnio($formularioLiquidacion->anio)
                    ->whereLetra($formularioLiquidacion->letra)
                    ->orderByDesc('id')
                    ->first();
                \DB::rollBack();
                return response()->json(['res' => true, 'message' => 'Pesaje creado correctamente', 'id' => $anteriorForm->id, 'cliente' => $anteriorForm->cliente->nombre]);
            }
            $res = $objCaja->subirDocumento($formularioLiquidacion);
            $formularioLiquidacion->url_documento = $res;
            $formularioLiquidacion->save();
            DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::BoletaPesaje)
                ->update(['agregado' => true]);
            ///////////

            $campos['descripcion'] = 'Nuevo lote ' . $formularioLiquidacion->lote;
            $campos['formulario_liquidacion_id'] = $formularioLiquidacion->id;
            $campos['accion'] = AccionCambioFormulario::NuevoLote;
            CambioFormulario::create($campos);


            $this->registrarHistorial($formularioLiquidacion->id, "Nuevo", "Formulario creado");

            \DB::commit();

            if ($esEdicion)
                return $formularioLiquidacion;
            else
                return response()->json(['res' => true, 'message' => 'Pesaje creado correctamente']);
                    //, 'id' => $formularioLiquidacion->id
                    //, 'cliente' => $formularioLiquidacion->cliente->nombre



        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function storeDescuentos($formId, $clienteId, $descuentos, $letra)
    {
        $camposDescuento['formulario_liquidacion_id'] = $formId;

        for ($i = 0; $i < $descuentos->count(); $i++) {
//            if ($descuentos[$i]->nombre == "BONO PRODUCTOR") {
//                if ($clienteId == 4 and ($letra == 'A' or $letra == 'B' or $letra == 'C')) {
//                    $camposDescuento['descuento_bonificacion_id'] = $descuentos[$i]->id;
//                    $camposDescuento['valor'] = 30;
//                    $camposDescuento['en_funcion'] = $descuentos[$i]->en_funcion;
//                    $camposDescuento['unidad'] = $descuentos[$i]->unidad;
//                    FormularioDescuento::create($camposDescuento);
//                } elseif ($clienteId == 5 and ($letra == 'A' or $letra == 'B' or $letra == 'C')) {
//                    $camposDescuento['descuento_bonificacion_id'] = $descuentos[$i]->id;
//                    $camposDescuento['valor'] = 10;
//                    $camposDescuento['en_funcion'] = $descuentos[$i]->en_funcion;
//                    $camposDescuento['unidad'] = $descuentos[$i]->unidad;
//                    FormularioDescuento::create($camposDescuento);
//                }
//            } else {
                $camposDescuento['descuento_bonificacion_id'] = $descuentos[$i]->id;
                $camposDescuento['valor'] = $descuentos[$i]->valor;
                $camposDescuento['en_funcion'] = $descuentos[$i]->en_funcion;
                $camposDescuento['unidad'] = $descuentos[$i]->unidad;
                FormularioDescuento::create($camposDescuento);
//            }

        }
    }

    private function registrarDeudaAnterior(FormularioLiquidacion $formulario)
    {
        $cuentas = CuentaCobrar::whereOrigenId($formulario->cliente_id)->whereOrigenType(Cliente::class)->whereEsCancelado(false)->get();
        foreach ($cuentas as $cuenta) {
            $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => FormularioLiquidacion::class, 'origen_id' => $formulario->id]);

            if ($c) {
                $tipo = FormularioLiquidacion::class;
                if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                    $tipo = Prestamo::class;

                $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " . $formulario->lote . " monto " . $cuenta->monto, $cuenta->id_inicio, $tipo);
                $this->registrarHistorial($formulario->id, "Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto);
            }
        }


        $formulario = FormularioLiquidacion::find($formulario->id);
        if ($formulario->totales['total_cuentas_cobrar'] > 0.00) {
            $formulario->update(['total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
        }
    }

    public function listaSinPeso(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fechaInicio = date('Y-m-d', strtotime(date('Y-m-d') . ' - 2 months'));
        $fechaFin = date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days'));

        $formularioLiquidacions = DB::table('cliente')
            ->join('cooperativa', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->join('formulario_liquidacion', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
            ->join('chofer', 'formulario_liquidacion.chofer_id', '=', 'chofer.id')
            ->join('vehiculo', 'formulario_liquidacion.vehiculo_id', '=', 'vehiculo.id')
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'formulario_liquidacion.id', 'formulario_liquidacion.producto',
                DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as cliente"),
                DB::raw("concat(chofer.licencia, ' | ',chofer.nombre)   as chofer"),
                DB::raw("concat(vehiculo.placa, ' | ',vehiculo.marca)   as vehiculo"),
                'cooperativa.razon_social as cooperativa', 'peso_bruto',
                'sacos',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([['formulario_liquidacion.created_at', '>=', $fechaInicio], ['formulario_liquidacion.created_at', '<', $fechaFin], ["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.peso_bruto', 0.00]])
            ->Orwhere([['formulario_liquidacion.created_at', '>=', $fechaInicio], ['formulario_liquidacion.created_at', '<', $fechaFin], ["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.peso_bruto', 0.00]])
            ->OrWhere([['formulario_liquidacion.created_at', '>=', $fechaInicio], ['formulario_liquidacion.created_at', '<', $fechaFin], [DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.peso_bruto', 0.00]])
            ->orderByDesc('formulario_liquidacion.numero_lote')->orderByDesc('formulario_liquidacion.id')
            ->get();


        return $formularioLiquidacions;
    }

    public function listaConError(Request $request)
    {
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
                'cooperativa.razon_social as cooperativa', 'peso_bruto', 'formulario_liquidacion.tipo_material',
                'sacos', 'chofer_id', 'vehiculo_id', 'cliente_id', 'tara', 'presentacion',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso],
                ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->OrWhere([[DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->orderByDesc('formulario_liquidacion.id')
            ->get();

//48 hours
        return $formularioLiquidacions;
    }

    public function listaAImprimir(Request $request)
    {
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
            ->where([["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso],
                ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '24 HOURS'")]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '24 HOURS'")]])
            ->OrWhere([[DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', Estado::EnProceso], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '24 HOURS'")]])
            ->orderByDesc('formulario_liquidacion.id')
            ->get();


        return $formularioLiquidacions;
    }


    public function editar(Request $request)
    {
        $ip = $request->ip();
//        if($ip!=='192.168.100.220')
//            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);

        \DB::beginTransaction();
        try {
            $tara = (0.250 * $request->sacos);
            FormularioLiquidacion::where('id', $request->id)
                ->update(['tara' => $tara, 'peso_bruto' => $request->peso_bruto, 'boletas' => $request->boletas
                    , 'sacos' => $request->sacos, 'fecha_pesaje' => date('Y-m-d')]);

            $this->registrarHistorial($request->id, "Modificado", "Formulario modificado");

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Pesaje modificado correctamente', 'id' => $request->id]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }


    public function editarPesaje(Request $request)
    {
        if (!str_contains($request->userAgent(), 'KFTRWI') and !str_contains($request->userAgent(), 'KFMAWI'))
            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
//        if($ip!=='192.168.100.220')
//            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
        $input = $request->all();

        \DB::beginTransaction();
        try {
            $id = $request->id;
            $formulario = FormularioLiquidacion::find($id);
            $producto = Producto::findOrFail($request->producto_id);

            if ($formulario->letra != $producto->letra) {
                /* if($formulario->totales['total_anticipos']>=0.00 OR $formulario->totales['total_cuentas_cobrar']>=0.00){
                     \DB::rollBack();
                     return response()->json(['res' => false, 'message' => 'No se puede anular el lote porque tiene anticipos o cuentas por cobrar', 'id' => $request->id]);
                 }*/
                $this->anular($formulario);
//                $this->registrar($request);
                $formNuevoLote = $this->registrar($request, true);

                $campos['descripcion'] = 'Cambio de producto: Lote ' . $formulario->lote . ' ANULADO, Lote ' . $formNuevoLote->lote . ' CREADO';
                $campos['formulario_liquidacion_id'] = $id;
                CambioFormulario::create($campos);

                CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereEsCancelado(false)
                    ->update(['origen_id' => $formNuevoLote->id]);
                Anticipo::whereFormularioLiquidacionId($id)->update(['formulario_liquidacion_id' => $formNuevoLote->id]);
                \DB::commit();
                return response()->json(['res' => true, 'message' => 'Lote modificado correctamente', 'id' => $request->id]);
            }


            if ($formulario->cliente_id != $request->cliente_id) {
                //eliminando los descuentos y bonificaciones si se ha cambiado el cliente
                $anticipo = Anticipo::whereFormularioLiquidacionId($id)->where('es_cancelado', false)->count();
                if ($anticipo > 0) {
                    \DB::rollBack();
                    return response()->json(['res' => false, 'message' => 'No se puede modificar un lote que tiene anticipos', 'id' => $id]);
                } else {
//                    $cuentaCobrar = CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereEsCancelado(false)
//                        ->update(['origen_type' => Cliente::class, 'origen_id' => $formulario->cliente_id]);

                    //cuentas cobrar
                    $cuentasCobrar = CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereEsCancelado(false)->get();
//                        dd($cuentasCobrar[0]->id_inicio);

                    foreach ($cuentasCobrar as $cuenta) {
                        $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => Cliente::class, 'origen_id' => $formulario->cliente_id]);

                        if ($c) {
                            $tipo = FormularioLiquidacion::class;
                            if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                                $tipo = Prestamo::class;

                            $this->registrarHistorialCuenta(AccionHistorialCuenta::CambioCliente,
                                "Cambio de cliente en lote, traspaso de cuenta de lote " . $formulario->lote . " a cliente ",
                                $cuenta->id_inicio, $tipo);

                        }
                    }
                    ////
                    ///
                    $this->registrarDescuentosBonificaciones($formulario, $request->cliente_id);

                    $clienteNuevo = Cliente::find($request->cliente_id);
                    $campos['descripcion'] = 'Cambio de cliente: De ' . $formulario->cliente->nombre . ' a ' . $clienteNuevo->nombre;
                    $campos['formulario_liquidacion_id'] = $id;
                    CambioFormulario::create($campos);
                }
            }

            if ($formulario->peso_bruto != $request->peso_bruto) {
                $campos['descripcion'] = 'Cambio de peso bruto: De ' . $formulario->peso_bruto . ' a ' . $request->peso_bruto;
                $campos['formulario_liquidacion_id'] = $id;
                CambioFormulario::create($campos);

                ///////////adjuntar boleta de pesaje a documentos
                $objRep = new ReporteController();
                $objCaja = new CajaController();
                $nombreArchivoForm = public_path() . '/documents/' . $id . '_document.pdf';
                File::delete($nombreArchivoForm);

                $objRep->generarBoletaPesaje($id);

                $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);
                $res = $objCaja->subirDocumento($formularioLiquidacion);
                $formularioLiquidacion->url_documento = $res;
                $formularioLiquidacion->save();
                DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::BoletaPesaje)
                    ->update(['agregado' => true]);
                ///////////
            }

            //cambio tipo material
            if ($formulario->tipo_material != $request->tipo_material and $formulario->letra == 'E') {
                $input['presentacion'] = Empaque::Ensacado;
                $input['tara'] = (0.225 * $request->sacos);
                $input['merma'] = 0;

                if ($request->tipo_material == TipoMaterial::Broza) {
                    $input['merma'] = 1;
                    $input['tara'] = 0;
                }
            }

            if ($formulario->tipo_material != $request->tipo_material and $formulario->letra == 'D') {
                $input['presentacion'] = Empaque::Ensacado;
                $input['tara'] = (0.250 * $request->sacos);
                $input['merma'] = 0;

            }

/////
            $input['fecha_pesaje'] = date('Y-m-d');
            $formulario->update($input);

            /////cuentas por cobrar
            $cuentas = CuentaCobrar::whereOrigenId($request->cliente_id)->whereOrigenType(Cliente::class)->whereEsCancelado(false)->get();

            foreach ($cuentas as $cuenta) {
                $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => FormularioLiquidacion::class, 'origen_id' => $id]);

                if ($c) {
                    $tipo = FormularioLiquidacion::class;
                    if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                        $tipo = Prestamo::class;
                    $this->registrarHistorial($id, "Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto);
                    $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " . $formulario->lote . " monto " . $cuenta->monto, $cuenta->id_inicio, $tipo);
                }
            }
            //////

            if ($request->producto_id == 4 ) {
                $request->tara = (0.250 * $request->sacos);
            }if ($request->producto_id == 6) {
                $request->tara = (0.225 * $request->sacos);
            }
            else {
                $request->sacos = 0;
            }

            $form = FormularioLiquidacion::find($request->id);
            $form->update(['tara' => $request->tara, 'peso_bruto' => $request->peso_bruto, 'boletas' => $request->boletas,
                'sacos' => $request->sacos, 'fecha_pesaje' => date('Y-m-d')]);


            $this->registrarHistorial($request->id, "Modificado", "Formulario modificado");

            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Pesaje modificado correctamente', 'id' => $request->id
                , 'cliente' => $form->cliente->nombre
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['res' => false, 'message' => $e, 'id' => $request->id]);

//            return $this->make_exception($e);
        }
    }

    public function registrarDescuentosBonificaciones(FormularioLiquidacion $formularioLiquidacion, $clienteId)
    {

        \DB::select("delete from formulario_descuento where formulario_liquidacion_id = ? ", [$formularioLiquidacion->id]);
        $cliente = Cliente::find($clienteId);
        $cooperativaId = $cliente->cooperativa_id;

        if ($cliente->cooperativa_id == 28) { //Colquechaca RL
            $seccion = 'seccion';
            $descuentos = DescuentoBonificacion::whereCooperativaId($cliente->cooperativa_id)
                ->where('nombre', 'not ilike', "%{$seccion}%")
                ->whereAlta(true)->orderBy('id')->get();
        } else {
            $descuentos = DescuentoBonificacion::whereCooperativaId($cooperativaId)
                ->where('id', '<>', 99) ///solucion momentanea para descuento q ya no se utiliza
                ->where('id', '<>', 208) ///solucion momentanea para descuento q ya no se utiliza
                ->where('id', '<>', 158) ///solucion momentanea para descuento q ya no se utiliza
                ->whereAlta(true)->orderBy('id')->get();
        }
        $this->storeDescuentos($formularioLiquidacion->id, $clienteId, $descuentos, $formularioLiquidacion->letra);
    }

    private function anular($formularioLiquidacion)
    {
        $formularioLiquidacion->estado = Estado::Anulado;
        $formularioLiquidacion->motivo_anulacion = 'Registro de producto equivocado';
        $formularioLiquidacion->save();


        $this->registrarHistorial($formularioLiquidacion->id, "Anulado", "Formulario anulado");
    }

    private function registrarHistorial($id, $accion, $observacion)
    {
        $historial = new Historial();
        $historial->fecha = Fachada::getFechaHora();
        $historial->accion = $accion;
        $historial->observacion = $observacion;
        $historial->formulario_liquidacion_id = $id;
        $historial->users_id = 7;
        $historial->save();
    }

    public function getVentas(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 6 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;

        $fecha_final = date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days'));
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        return \DB::table('venta')
            ->leftJoin('comprador', 'venta.comprador_id', 'comprador.id')
            ->where('estado', EstadoVenta::EnProceso)
            ->whereAOperaciones(true)
            ->whereBetween('venta.created_at', [$fecha_inicial, $fecha_final])
            ->where(function ($q) use ($txtBuscar) {
                $q->where('producto', 'ilike', "%{$txtBuscar}%")
                    ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                    ->orWhere('razon_social', 'ilike', "%{$txtBuscar}%")
                    ->orWhere('nit', 'ilike', "%{$txtBuscar}%");
            })
            ->select(
                DB::raw("concat(comprador.nit, ' | ',comprador.razon_social)   as comprador"),
                DB::raw("concat(sigla, numero_lote, letra,'/', SUBSTRING ( anio::text,3)) as lote"),
                'producto', 'venta.id', 'lote_comprador', 'comprador_id', 'es_despachado',
                DB::raw("to_char(venta.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->orderBy('numero_lote')->orderBy('id')->get();
    }

    public function getCantidadVentas()
    {
        return \DB::table('venta')
            ->leftJoin('comprador', 'venta.comprador_id', 'comprador.id')
            ->whereAOperaciones(true)
            ->whereEsDespachado(false)
            ->count();
    }

    public function getRetiros(Request $request)
    {
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
                'cooperativa.razon_social as cooperativa', 'peso_bruto', 'formulario_liquidacion.tipo_material',
                'sacos', 'chofer_id', 'vehiculo_id', 'cliente_id', 'tara', 'presentacion',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->where([["formulario_liquidacion.producto", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::Anulado],
                ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->Orwhere([["cliente.nombre", 'ilike', ["%{$txtBuscar}%"]], ['formulario_liquidacion.estado', Estado::Anulado], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->OrWhere([[DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"]],
                ['formulario_liquidacion.estado', Estado::Anulado], ['formulario_liquidacion.created_at', '>=', DB::raw("NOW() - INTERVAL '48 HOURS'")]])
            ->orderByDesc('formulario_liquidacion.id')
            ->get();

//48 hours
        return $formularioLiquidacions;
    }

    public function getOrdenDespacho($id)
    {
        $formularios = \DB::table('formulario_liquidacion')->
        join('venta_formulario_liquidacion', 'venta_formulario_liquidacion.formulario_liquidacion_id', 'formulario_liquidacion.id')
            ->where('venta_formulario_liquidacion.venta_id', $id)
            ->select(
                DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                "formulario_liquidacion.peso_bruto",
                "formulario_liquidacion.sacos", 'venta_formulario_liquidacion.despachado', 'formulario_liquidacion.id')
            ->orderBy('numero_lote')->get();
        $ingenios =
            \DB::table('concentrado')->where('venta_id', $id)->where('tipo_lote', TipoLoteVenta::Ingenio)
                ->select('id', 'nombre', 'peso_neto_humedo', 'despachado')
                ->orderBy('id')->get();

        return response()->json(['res' => true, 'lotes' => $formularios, 'ingenios' => $ingenios]);
    }

    public function actualizarDespacho(Request $request)
    {
        if (!str_contains($request->userAgent(), 'KFTRWI') and !str_contains($request->userAgent(), 'KFMAWI'))
            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);

        if ($request->tipo == 'Ingenio') {
            $id = $request->id;
            $ingenio = Concentrado::find($id);

            $venta = Venta::find($ingenio->venta_id);
            if ($venta->es_despachado)
                return response()->json(['res' => false, 'message' => 'No se puede realizar la acción poque el lote ya se finalizó']);

           // $contador = UbicacionFormulario::whereFormularioLiquidacionId($id)->count();

                $ingenio->update(['despachado' => !($ingenio->despachado)]);

                //if ($contador > 0)
                  //  UbicacionFormulario::whereFormularioLiquidacionId($id)->update(['alta' => false]);
        } else {
            $id = $request->id;
            $ventaForm = VentaFormularioLiquidacion::whereFormularioLiquidacionId($id)->first();

            $venta = Venta::find($ventaForm->venta_id);
            if ($venta->es_despachado)
                return response()->json(['res' => false, 'message' => 'No se puede realizar la acción poque el lote ya se finalizó']);

            $contador = UbicacionFormulario::whereFormularioLiquidacionId($id)->count();

            if ($ventaForm->despachado) {
                $ventaForm->update(['despachado' => !($ventaForm->despachado), 'fecha_despacho' => null]);
//            if($contador>0)
//                UbicacionFormulario::whereFormularioLiquidacionId($id)->update(['alta'=>true]);
            } else {
                $ventaForm->update(['despachado' => !($ventaForm->despachado), 'fecha_despacho' => date('Y-m-d')]);
                if ($contador > 0)
                    UbicacionFormulario::whereFormularioLiquidacionId($id)->update(['alta' => false]);
            }
        }
        return response()->json(['res' => true, 'message' => 'Registro guardado correctamente']);
    }

    public function actualizarDespachoVenta(Request $request)
    {
        if (!str_contains($request->userAgent(), 'KFTRWI') and !str_contains($request->userAgent(), 'KFMAWI'))
            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
        \DB::beginTransaction();
        try {
            $id = $request->id;
            $ventasForms = VentaFormularioLiquidacion::whereVentaId($id)->count();
            $ventasDespachados = VentaFormularioLiquidacion::whereVentaId($id)->whereDespachado(true)->count();
            if ($ventasForms != $ventasDespachados) {
                return response()->json(['res' => false, 'message' => 'No se puede finalizar porque existen lotes sin despachar']);
            }
            $venta = Venta::find($id);

            $venta->update(['es_despachado' => true]);
            \DB::commit();

            return response()->json(['res' => true, 'message' => 'Lote finalizado correctamente']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function actualizarRetiro(Request $request)
    {
        if (!str_contains($request->userAgent(), 'KFTRWI') and !str_contains($request->userAgent(), 'KFMAWI'))
            return response()->json(['res' => false, 'message' => 'No tiene los permisos para realizar esta operación']);
        \DB::beginTransaction();
        try {
            $id = $request->id;
            $form = FormularioLiquidacion::find($id);

            $form->update(['es_retirado' => true]);
            \DB::commit();

            return response()->json(['res' => true, 'message' => 'Lote retirado correctamente']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }


    public function getDevoluciones(Request $request)
    {
        $txtBuscar = $request->parametro;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $devoluciones = DB::table('cliente')
            ->join('cooperativa', 'cliente.cooperativa_id', '=', 'cooperativa.id')
            ->join('formulario_liquidacion', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
            ->join('bono', 'formulario_liquidacion.id', '=', 'bono.formulario_liquidacion_id')
            ->where('bono.es_cancelado', true)
            ->whereIn('bono.clase', [ClaseDevolucion::Interno, ClaseDevolucion::Externo])
            ->where('formulario_liquidacion.es_retirado', false)
            ->where(function ($q) use ($txtBuscar) {
                $q->where(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3))"), "ilike", ["%{$txtBuscar}%"])
                    ->orWhere("cliente.nombre", 'ilike', ["%{$txtBuscar}%"]);
            })
            ->select(DB::raw("concat(formulario_liquidacion.sigla, formulario_liquidacion.numero_lote, formulario_liquidacion.letra,'/', SUBSTRING ( formulario_liquidacion.anio::text,3)) as lote"),
                'formulario_liquidacion.id',
                DB::raw("concat(cliente.nit, ' | ',cliente.nombre, ' | ', cooperativa.razon_social)   as cliente"),
                'peso_bruto',
                'sacos',
                DB::raw("to_char(formulario_liquidacion.created_at,  'DD/MM/YYYY') as fecha_recepcion"))
            ->orderByDesc('formulario_liquidacion.id')
            ->groupBy('formulario_liquidacion.id',
                'formulario_liquidacion.anio',

                'peso_bruto',
                'sacos',
                'formulario_liquidacion.sigla', 'formulario_liquidacion.numero_lote', 'formulario_liquidacion.letra',
                'formulario_liquidacion.created_at',
                'cliente.nit', 'cliente.nombre', 'cooperativa.razon_social')
            ->get();
        return $devoluciones;
    }

    public function getNumeroLote($producto)
    {
        $fechaActual = Fachada::getFecha();
        $fechaInicioGestion = \DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime(date('Y') . "-10-01")));

        $anio = date('Y');
        if ($fechaActual >= $fechaInicioGestion)
            $anio += 1;

        if ($producto == 3) {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereProducto('D | Estaño')->max('numero_lote');
        } elseif ($producto == 5) {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->where('producto', 'like', '%F | Antimonio%')->max('numero_lote');
        } elseif ($producto == 4) {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereProducto('E | Plata')->max('numero_lote');
        } elseif ($producto == 6) {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereProducto('G | Cobre')->max('numero_lote');
        } else {
            $formulario = FormularioLiquidacion::
//        where('estado', '<>', Estado::Anulado)->
            where('anio', $anio)->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo'])->max('numero_lote');
        }
        return ($formulario + 1);

    }

    private function getCotizacionDiaria($f)
    {
        return CotizacionDiaria::with(['mineral:id,simbolo,nombre'])
            ->whereFecha($f->fecha_cotizacion)
            ->whereIn('mineral_id', $f->liquidacioMinerales->pluck('mineral_id'))
            ->orderBy('mineral_id')
            ->get();
    }

    public function getResumen($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::with([
            'tipoCambio',
            'cliente'
        ])->find($id);

        //cotizaciones diarias
        $cotizacionDiaria = $this->getCotizacionDiaria($formularioLiquidacion);

        return ['formulario' => $formularioLiquidacion, 'diarias' => $cotizacionDiaria];
    }

    public function getFormularioPdf($formulario_id)
    {
        $nombre = null;

        $resumen = $this->getResumen($formulario_id);
        $formularioLiquidacion = $resumen['formulario'];
        $cotizacionesDiarias = $resumen['diarias'];

        $descuentos = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Descuento');
            })->orderBy('descuento_bonificacion_id')->get();
        $bonificaciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Bonificacion');
            })->orderBy('descuento_bonificacion_id')->get();
        $retenciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
            ->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Retencion');
            })->orderBy('descuento_bonificacion_id')->get();
        $totalRegalias = 0;
        /* $pagable=$formularioLiquidacion->valor_neto_venta - $retenciones->sum('sub_total') - $descuentos->sum('sub_total')+$bonificaciones->sum('sub_total') - $formularioLiquidacion->totales['total_anticipos'];*/

        //generador qr
        $urlQR = url("/formulario-pdf/{$formulario_id}");
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($urlQR));

        $formatter = new NumeroALetras();
        $literal = $formatter->toMoney(abs($formularioLiquidacion->totales['total_saldo_favor']), 2, 'BOLIVIANOS', 'CENTAVOS');
        $sumaBrutoVenta = 0;
        foreach ($formularioLiquidacion->minerales_regalia as $mineral) {
            $sumaBrutoVenta = $mineral['valor_bruto_venta'] + $sumaBrutoVenta;
        }

        $anticipos = Anticipo::whereFormularioLiquidacionId($formulario_id)->orderBy('fecha')->get();
        $bonos = Bono::whereFormularioLiquidacionId($formulario_id)->orderBy('fecha')->get();
        //$totalLiquidacion=$pagable-$formularioLiquidacion->totales['total_anticipos'];
        $vistaurl = "formulario_liquidacions.impresion_android";
        $view = \View::make($vistaurl, compact('formulario_id', 'formularioLiquidacion', 'bonificaciones', 'descuentos', 'anticipos',
            'retenciones', 'nombre', 'cotizacionesDiarias', 'qrcode', 'totalRegalias', 'literal', 'bonos', 'sumaBrutoVenta'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(48, 810, 'www.colquechaca.com', null, 9, array(0, 0, 0));

        $fechaImpresion = date('d/m/Y H:i');
        $pago = PagoMovimiento::whereOrigenId($formulario_id)->whereOrigenType(FormularioLiquidacion::class)->whereAlta(true)->first();
        if ($pago)
            $fechaImpresion = date('d/m/Y H:i', strtotime($pago->created_at));
        $canvas->page_text(360, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));


        return $pdf->stream('Liquidación ' . $formularioLiquidacion->lote . '.pdf');
    }

    private function registrarHistorialCuenta($accion, $observacion, $origen_id = null, $origen_type = null)
    {
        $req["accion"] = $accion;
        $req["observacion"] = $observacion;
        if ($origen_id != 0) {
            $req["origen_id"] = $origen_id;
            $req["origen_type"] = $origen_type;
        }
        $req["users_id"] = 7;
        HistorialCuentaCobrar::create($req);
    }



}
