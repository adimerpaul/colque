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
use App\Models\ContratoPlantilla;
use App\Models\Costo;
use App\Models\CotizacionDiaria;
use App\Models\CuentaCobrar;
use App\Models\DescuentoBonificacion;
use App\Models\DocumentoCompra;
use App\Models\FormularioDescuento;
use App\Models\FormularioKardex;
use App\Models\FormularioLiquidacion;
use App\Models\FormularioLiquidacionData;
use App\Models\Historial;
use App\Models\HistorialCuentaCobrar;
use App\Models\Laboratorio;
use App\Models\LaboratorioPrecio;
use App\Models\PagoMovimiento;
use App\Models\Prestamo;
use App\Models\Producto;
use App\Models\ProductoMineral;
use App\Models\PuntoCliente;
use App\Models\TablaAcopiadora;
use App\Models\TablaAcopiadoraDetalle;
use App\Models\TipoCambio;
use App\Patrones\AccionCambioFormulario;
use App\Patrones\AccionHistorialCuenta;
use App\Patrones\ClaseCuentaCobrar;
use App\Patrones\ClaseDescuento;
use App\Patrones\ClaseDevolucion;
use App\Patrones\Contrato;
use App\Patrones\DescripcionPunto;
use App\Patrones\Empaque;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Models\LiquidacionMineral;
use App\Patrones\Permiso;
use App\Patrones\Rol;
use App\Patrones\TipoDescuentoBonificacion;
use App\Patrones\TipoMaterial;
use App\Patrones\TipoMotivoDevolucion;
use App\Repositories\FormularioLiquidacionRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function PHPUnit\Framework\isNull;

class FormularioLiquidacionController extends AppBaseController
{
    /** @var  FormularioLiquidacionRepository */
    private $formularioLiquidacionRepository;

    public function __construct(FormularioLiquidacionRepository $formularioLiquidacionRepo)
    {
        $this->formularioLiquidacionRepository = $formularioLiquidacionRepo;
        $this->middleware('cotizacion')->only(['index']);
    }

    /**
     * Display a listing of the FormularioLiquidacion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $fecha_inicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 6 months'));
        if (isset($request->fecha_inicial))
            $fecha_inicial = $request->fecha_inicial;
        $fecha_final = date('Y-m-d');
        if (isset($request->fecha_final))
            $fecha_final = $request->fecha_final;

        $formularioLiquidacions = FormularioLiquidacion::where('estado', 'ilike', "%{$request->txtEstado}%")
            ->where(function ($q) use ($txtBuscar) {
                $q->where('producto', 'ilike', "%{$txtBuscar}%")
                    ->orWhereRaw("(CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) ilike ?)", ["%{$txtBuscar}%"])
                    ->orWhereHas('cliente', function ($q) use ($txtBuscar) {
                        $q->where('nombre', 'ilike', "%{$txtBuscar}%")
                            ->orWhere('nit', 'ilike', "%{$txtBuscar}%")
                            ->orWhereHas('cooperativa', function ($q) use ($txtBuscar) {
                                $q->where('razon_social', 'ilike', "%{$txtBuscar}%")
                                    ->orWhere('nro_nim', 'ilike', "%{$txtBuscar}%");
                            });
                    });
            })
            ->whereBetween('fecha_liquidacion', [$fecha_inicial, $fecha_final])
            ->orderByDesc('id')->orderByDesc('numero_lote')
            ->paginate();

        return view('formulario_liquidacions.index')
            ->with('formularioLiquidacions', $formularioLiquidacions);
    }

    /**
     * Show the form for creating a new FormularioLiquidacion.
     *
     * @return Response
     */
    public function create()
    {
//        $comando= exec('node D:/proyectos/serialport4/app.js');
        $tara = 0;
        $neto = 0;
        $bruto = 0;
//        if($comando){
//        $indexTara = strpos($comando, 'Tare ') + strlen('Tare ');
//        $tara = substr($comando, $indexTara);
//        list($tara, $b) = explode(' kg Net ', $tara);
//
//        $indexNeto = strpos($comando, 'Net ') + strlen('Net ');
//        $neto = substr($comando, $indexNeto);
//        list($neto, $b) = explode(' kg', $neto);
//
//        list($a, $bruto) = explode('Gross ', $comando);
//        list($bruto, $b) = explode(' kg Tare ', $bruto);
//        }
        return view('formulario_liquidacions.create', compact('bruto', 'tara'));
    }

    private function getNumero($producto)
    {
        $fechaActual = Fachada::getFecha();
        $fechaInicioGestion = \DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime(date('Y') . "-10-01")));

        $anio = date('Y');
        if ($fechaActual >= $fechaInicioGestion)
            $anio += 1;

        if ($producto == 'D | Estaño' or $producto == 'E | Plata' or str_contains($producto, 'F | Antimonio') or $producto == 'G | Cobre') {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereProducto($producto)->max('numero_lote');
        } else {
            $formulario = FormularioLiquidacion::
            where('anio', $anio)->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo'])->max('numero_lote');
        }
        return ['numero' => $formulario + 1, 'anio' => $anio];

    }


    public function store(CreateFormularioLiquidacionRequest $request)
    {
//        dd($request->ip());
        \DB::beginTransaction();
        try {
            $input = $request->all();

            $producto = Producto::findOrFail($request->producto_id);
            $tipoCambio = TipoCambio::orderByDesc('id')->first();
            $input['tipo_cambio_id'] = $tipoCambio->id;
            $input['fecha_cotizacion'] = Fachada::getFecha();
            $input['fecha_liquidacion'] = Fachada::getFecha();
            //  $fechaPesaje = str_replace('/', '-', $request->fecha_pesaje);
            //  $input['fecha_pesaje'] = date('Y-m-d', strtotime($fechaPesaje));

            //sigla o numero de lote
            $input['sigla'] = 'CM';
            $input['numero_lote'] = $this->getNumero($producto->info)['numero'];
            $input['letra'] = $producto->letra;
            $input['anio'] = $this->getNumero($producto->info)['anio'];
            $input['producto'] = $producto->info;
            $input['valor_por_tonelada'] = null;

            if ($request->presentacion == Empaque::AGranel) {
                $input['sacos'] = 0;
            }

            if ($producto->letra == 'D') {
                $input['presentacion'] = Empaque::Ensacado;
                $input['merma'] = 0;
                $input['ley_sn'] = 0;
                $input['tara'] = (0.250 * $request->sacos);
            } elseif ($producto->letra == 'E') {
                $input['con_cotizacion_promedio'] = true;
                if ($request->tipo_material == TipoMaterial::Broza) {
                    $input['merma'] = 1;
                    $input['tara'] = 0;
                } else {
                    $input['tara'] = (0.225 * $request->sacos);
                    $input['merma'] = 0;
                }
            }
            $formularioLiquidacion = $this->formularioLiquidacionRepository->create($input);

            //rregistrando deuda anterior si el total es mayor a 0
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

            //laboratorios
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

///////////
            //Descuentos bonificaciones
            $cliente = Cliente::find($request->cliente_id);
            if ($cliente->cooperativa_id == 28) { //Colquechaca RL
                $seccion = 'secci';
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
            $this->adjuntarBoleta($formularioLiquidacion);
            ///////////


            Flash::success('Formulario Liquidacion creado correctamente.');

            event(new AccionCompleta("Nuevo", "Formulario creado", $formularioLiquidacion->id));

            \DB::commit();
            return redirect(route('formularioLiquidacions.index'));

        } catch
        (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function adjuntarBoleta($formularioLiquidacion)
    {
        $objRep = new ReporteController();
        $objCaja = new CajaController();
        $objRep->generarBoletaPesaje($formularioLiquidacion->id);

        $formularioLiquidacion = FormularioLiquidacion::findOrFail($formularioLiquidacion->id);
        $res = $objCaja->subirDocumento($formularioLiquidacion);
        $formularioLiquidacion->url_documento = $res;
        $formularioLiquidacion->save();
        DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::BoletaPesaje)
            ->update(['agregado' => true]);
    }

    private function adjuntarContrato($formularioLiquidacion)
    {
        $objRep = new ReporteController();
        $objCaja = new CajaController();
        $objRep->generarContrato($formularioLiquidacion->id);

        $formularioLiquidacion = FormularioLiquidacion::findOrFail($formularioLiquidacion->id);
        $res = $objCaja->subirDocumento($formularioLiquidacion);
        $formularioLiquidacion->url_documento = $res;
        $formularioLiquidacion->save();
        DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::Contrato)
            ->update(['agregado' => true]);
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

    public function registrarDescuentosBonificaciones(FormularioLiquidacion $formularioLiquidacion, $clienteId, $cambioCliente)
    {

        \DB::select("delete from formulario_descuento where formulario_liquidacion_id = ? ", [$formularioLiquidacion->id]);
        $cooperativaId = $formularioLiquidacion->cliente->cooperativa_id;
        if ($cambioCliente) {
            $cliente = Cliente::find($clienteId);
            $cooperativaId = $cliente->cooperativa_id;
        }

        $descuentos = DescuentoBonificacion::whereCooperativaId($cooperativaId)
            ->where('id', '<>', 99) ///solucion momentanea para descuento q ya no se utiliza
            ->where('id', '<>', 208) ///solucion momentanea para descuento q ya no se utiliza
            ->whereAlta(true)->orderBy('id')->get();

        $this->storeDescuentos($formularioLiquidacion->id, $clienteId, $descuentos, $formularioLiquidacion->letra);
    }

    private function registrarDeudaAnterior(FormularioLiquidacion $formulario)
    {
        $cuentas = CuentaCobrar::whereOrigenId($formulario->cliente_id)->whereOrigenType(Cliente::class)->whereEsCancelado(false)->get();
        foreach ($cuentas as $cuenta) {
//            dd($cuenta->id_inicio);
            $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => FormularioLiquidacion::class, 'origen_id' => $formulario->id]);

            if ($c) {
                $tipo = FormularioLiquidacion::class;
                if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                    $tipo = Prestamo::class;
                $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " . $formulario->lote . " monto " . $cuenta->monto, $cuenta->id_inicio, $tipo);
                event(new AccionCompleta("Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto, $formulario->id));
            }
        }


        $formulario = FormularioLiquidacion::find($formulario->id);
        if ($formulario->totales['total_cuentas_cobrar'] > 0.00) {
            $formulario->update(['total_cuenta_cobrar' => $formulario->totales['total_cuentas_cobrar']]);
        }

//        $ultimoFormulario = FormularioLiquidacion::whereClienteId($formulario->cliente_id)
//            ->where('id', '<>', $formulario->id)
//            ->orderByDesc('id')
//            ->first();
//        if (!is_null($ultimoFormulario) && (double)$ultimoFormulario->totales['total_final'] < 0) {
//            $formularioActual = FormularioLiquidacion::find($formulario->id);
//            $formularioActual->anticipos()->create([
//                'fecha' => date("d/m/Y"),
//                'monto' => abs((double)$ultimoFormulario->totales['total_final']),
//                'motivo' => "CUENTAS POR COBRAR A {$ultimoFormulario->cliente->nombre} POR SALDO NEGATIVO NRO LOTE: {$ultimoFormulario->lote}"
//            ]);
//        }
    }

    public function show($id)
    {
        $formularioLiquidacion = FormularioLiquidacion::with([
            'tipoCambio',
            'cliente',
            'chofer',
            'vehiculo',
            'producto',
            'descuentoBonificaciones',
            'liquidacioMinerales.mineral',
            'laboratorios',
        ])->find($id);

        if (empty($formularioLiquidacion)) {
            Flash::error('Formulario Liquidación no encontrado');

            return redirect(route('formularioLiquidacions.index'));
        }

        return view('formulario_liquidacions.show')->with('formularioLiquidacion', $formularioLiquidacion);
    }

    public function getResumen($id)
    {
        $formulario = FormularioLiquidacion::find($id);
        if ($formulario->es_cancelado == false)
            $this->actualizarSaldoYLiquido($formulario);

        $formularioLiquidacion = FormularioLiquidacion::with([
            'tipoCambio',
            'cliente'
        ])->find($id);

        //cotizaciones diarias
        $cotizacionDiaria = $this->getCotizacionDiaria($formularioLiquidacion);

        return ['formulario' => $formularioLiquidacion, 'diarias' => $cotizacionDiaria];
    }

    private function getCotizacionDiaria($f)
    {
        return CotizacionDiaria::with(['mineral:id,simbolo,nombre'])
            ->whereFecha($f->fecha_cotizacion)
            ->whereIn('mineral_id', $f->liquidacioMinerales->pluck('mineral_id'))
            ->orderBy('mineral_id')
            ->get();
    }

    private function actualizarTotalDescuentosBonificaciones($formulario)
    {
        if ($formulario->totales['total_bonificaciones'] != $formulario->total_bonificacion)
            $formulario->update(['total_bonificacion' => $formulario->totales['total_bonificaciones']]);

        $formulario->update(['total_bonificacion_acumulativa' => $formulario->totales['total_bonificaciones_acumulativas']]);

        if ($formulario->total_retencion_descuento !=
            round(($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos']), 2)
        ) {
            $formulario->update(['total_retencion_descuento' =>
                ($formulario->totales['total_retenciones'] + $formulario->totales['total_descuentos'])]);
        }

    }

    public function edit($id)
    {
        $formulario = FormularioLiquidacion::findOrFail($id);

      //  dd($formulario->laboratorio_promedio);
        if ($formulario->es_cancelado == false) {
            $this->actualizarFechaLiquidacion($formulario);
//            $this->actualizarTotalDescuentosBonificaciones($formulario);
        }

        $formularioLiquidacion = FormularioLiquidacionData::with(['cliente'])->findOrFail((int)$id);

//        if ($formulario->es_cancelado == false) {
//            $this->actualizarValorPagableYPesoSeco($id);
//            $this->actualizarSaldoYLiquido($formulario);
//        }

        $costo = Costo::whereFormularioLiquidacionId($id)->first();
        $fechaActual = date("Y-m-d");

        $cambios = CambioFormulario::whereFormularioLiquidacionId($id)->whereAccion(AccionCambioFormulario::Pesaje)->whereRevisado(false)->get();
        CambioFormulario::whereFormularioLiquidacionId($id)->whereIn('accion', [AccionCambioFormulario::Pesaje, AccionCambioFormulario::NuevoLote])->update(['revisado' => true]);

        return view('formulario_liquidacions.edit', compact('formularioLiquidacion', 'fechaActual', 'costo', 'formulario', 'cambios'));
    }

    private function actualizarFechaLiquidacion($formulario)
    {
        $hoy = date('Y-m-d');
        if ($formulario->estado == Estado::EnProceso and $formulario->fecha_liquidacion != $hoy and auth()->user()->rol == Rol::Comercial) {
            $formulario->fecha_liquidacion = $hoy;
            $formulario->save();
        }
    }

    private function tieneCotizacionDiariaEnFecha($fecha, $formulario_id)
    {
        $mineralesIds = LiquidacionMineral::whereFormularioLiquidacionId($formulario_id)->pluck('mineral_id');
        return CotizacionDiaria::WhereIn('mineral_id', $mineralesIds)->whereFecha($fecha)->count() > 0;
    }

    private function tieneCotizacionOficialEnFecha($fecha)
    {

        return Fachada::getFechaCotizacionOficial($fecha);
    }

    public function update($id, UpdateFormularioLiquidacionRequest $request)
    {
        \DB::beginTransaction();
        try {
            $formularioLiquidacion = FormularioLiquidacion::find($id);

            if (empty($formularioLiquidacion)) {
                Flash::error('Formulario Liquidación no encontrado');
                return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
            }

            $input = $request->all();

            if ($request->has('btnGuardar')) {
                //verificando si el codigo ya existe
                if ($this->esCodigoRepetido($input, $formularioLiquidacion->id)) {
                    Flash::error('Error! número de lote ya existe para esta gesión, cambie los datos del lote');
                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                }
                //$fechaCotizacion = Fachada::setFormatoFecha($request->fecha_cotizacion);
//arreglar
                //                $fechaLiquidacion = Fachada::setFormatoFecha($request->fecha_liquidacion);
                //$fechaPesaje = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_pesaje)));

                //verificando que existan cotizaiones diarias, oficial y tipo de cambio para la fecha en particular
//                if (!$this->tieneCotizacionDiariaEnFecha($fechaCotizacion, $formularioLiquidacion->id)) {
//                    Flash::error("Error! No existe Cotización Diaria de los minerales para la fecha {$fechaCotizacion}, elija otra fecha y vuelva a intentarlo");
//                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
//                }
//arreglar
//                if (auth()->user()->rol == Rol::Comercial)
//                    $fecha_liquidacion = Fachada::setDateFormat(Fachada::setDate(date('d/m/Y', strtotime($formularioLiquidacion->fecha_liquidacion))));
//                else
//                    $fecha_liquidacion = Fachada::setDateFormat(Fachada::setDate($request->fecha_liquidacion));

//                if (is_null($this->tieneCotizacionOficialEnFecha($fecha_liquidacion))) {
//                    Flash::error("Error! No existe Cotización Oficial de los minerales para la fecha {$request->fecha_liquidacion}, elija otra fecha y vuelva a intentarlo");
//                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
//                }

                //tipo de cambio
                $tipoCambio = TipoCambio::whereFecha(($formularioLiquidacion->fecha_cotizacion))->first();
                if (is_null($tipoCambio)) {
                    Flash::error('Error! No existe Tipo de Cambio para la fecha de cotizacion');
                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                }
                $input['tipo_cambio_id'] = $tipoCambio->id;
                //arreglar
//                $input['fecha_liquidacion'] = $fechaLiquidacion;
                // $input['fecha_cotizacion'] = $fechaCotizacion;
                //$input['fecha_pesaje'] = $fechaPesaje;
                //agregando los descuentos
                if ($formularioLiquidacion->descuentoBonificaciones()->count() <= 0) {
                    $this->registrarDescuentosBonificaciones($formularioLiquidacion, $formularioLiquidacion->cliente_id, false);
                }
                $input['fecha_liquidacion'] = Fachada::setFormatoFecha($request->fecha_liquidacion);
                if (is_null($request->comision_externa))
                    $input['comision_externa'] = false;

                if (is_null($request->con_cotizacion_promedio))
                    $input['con_cotizacion_promedio'] = false;

                if (is_null($request->es_cotizacion_manual))
                    $input['es_cotizacion_manual'] = false;

                if (is_null($request->con_ley_minima) and Permiso::esAdmin() and ($formularioLiquidacion->letra == 'B' or $formularioLiquidacion->letra == 'D' or $formularioLiquidacion->letra == 'E'))
                    $input['con_ley_minima'] = false;

                $formularioLiquidacion = $this->formularioLiquidacionRepository->update($input, $id);

                event(new AccionCompleta("Modificado", "Formulario modificado", $formularioLiquidacion->id));

                Flash::success('Formulario actualizado correctamente.');
            }
            if ($request->has('btnGuardarPesaje')) {
                $clienteAntiguo = $formularioLiquidacion->cliente_id;
                //eliminando los descuentos y bonificaciones si se ha cambiado el cliente
                if ((int)$clienteAntiguo !== (int)$request->cliente_id) {
                    $anticipo = Anticipo::whereFormularioLiquidacionId($id)->where('es_cancelado', false)->count();
                    if ($anticipo > 0) {
                        \DB::rollBack();
                        Flash::error('No se puede cambiar de cliente a un lote con anticipos cancelados');
                        return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                    } else {
                        //cuentas cobrar
                        $cuentasCobrar = CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->whereEsCancelado(false)->get();
//                        dd($cuentasCobrar[0]->id_inicio);

                        foreach ($cuentasCobrar as $cuenta) {
                            $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => Cliente::class, 'origen_id' => $formularioLiquidacion->cliente_id]);

//                            dd($c);
                            if ($c) {
                                $tipo = FormularioLiquidacion::class;
                                if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                                    $tipo = Prestamo::class;
                                $this->registrarHistorialCuenta(AccionHistorialCuenta::CambioCliente,
                                    "Cambio de cliente en lote, traspaso de cuenta de lote " . $formularioLiquidacion->lote . " a cliente ",
                                    $cuenta->id_inicio, $tipo);
                            }
                        }
                        ////
                        $this->registrarDescuentosBonificaciones($formularioLiquidacion, $request->cliente_id, true);
//                        event(new AccionCompleta("Cuenta Transferida", "Cuenta por cobrar , por saldo negativo del lote ".$formulario->lote. " al lote ".$formularioDestino->lote, $formulario->id));

                    }
                }
                $input['fecha_pesaje'] = Fachada::setFormatoFecha($request->fecha_pesaje);
                /*if ($request->presentacion == Empaque::Ensacado) {
                    $input['tara'] = (0.250 * $request->sacos);
                } else {
                    $input['sacos'] = 0;
                }*/

                if ($formularioLiquidacion->letra == 'D') {
                    $input['tara'] = (0.250 * $request->sacos);
                    $input['presentacion'] = Empaque::Ensacado;
                    $input['merma'] = 0;
                } elseif ($formularioLiquidacion->letra == 'E') {
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


                $pesoAntiguo = $formularioLiquidacion->peso_bruto;
                $tipoMaterialAntiguo = $formularioLiquidacion->tipo_material;

                $formularioLiquidacion->update($input);
                if ((round($pesoAntiguo, 2) !== round($request->peso_bruto, 2)) or
                    (int)$clienteAntiguo !== (int)$request->cliente_id) {
                    $this->adjuntarBoleta($formularioLiquidacion);
                }

                if ($tipoMaterialAntiguo != $request->tipo_material and $formularioLiquidacion->letra == 'E') {
                    $productoMineral = ProductoMineral::whereMineralId(1)->whereProductoId(6)->first();
                    $leyMinima = $productoMineral->ley_minima;
                    if ($formularioLiquidacion->tipo_material == TipoMaterial::Broza)
                        $leyMinima = 4.00;
                    LiquidacionMineral::whereFormularioLiquidacionId($id)->whereMineralId(1)->update(['ley_minima' => $leyMinima]);
                }
                /////cuentas por cobrar
                $cuentas = CuentaCobrar::whereOrigenId($request->cliente_id)->whereOrigenType(Cliente::class)->whereEsCancelado(false)->get();

                foreach ($cuentas as $cuenta) {
//            dd($cuenta->id_inicio);
                    $c = CuentaCobrar::whereId($cuenta->id)->update(['origen_type' => FormularioLiquidacion::class, 'origen_id' => $id]);

                    if ($c) {
                        $tipo = FormularioLiquidacion::class;
                        if ($cuenta->clase == ClaseCuentaCobrar::Prestamo)
                            $tipo = Prestamo::class;
                        $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " . $formularioLiquidacion->lote . " monto " . $cuenta->monto, $cuenta->id_inicio, $tipo);
                        event(new AccionCompleta("Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto, $id));
                    }
                }

                $objVpt = new ValorPorToneladaController();
                $objVpt->updateValorPorTonelada($id);

                //////
//                $this->registrarDeudaAnterior($formularioLiquidacion);
                event(new AccionCompleta("Modificado", "Formulario modificado", $formularioLiquidacion->id));

//                event(new AccionCompleta("Nuevo", "Pesaje guardado", $formularioLiquidacion->id));
                Flash::success('Datos del pesaje actualizado correctamente.');
            }
            if ($request->has('btnAnular')) {
                $formularioLiquidacion->estado = Estado::Anulado;
                $formularioLiquidacion->save();

                event(new AccionCompleta("Anulado", "Formulario anulado", $formularioLiquidacion->id));

                Flash::error('Formulario anulado correctamente.');
            }

            if ($request->has('btnRestablecer')) {

                if ($formularioLiquidacion->es_cancelado) {
                    Flash::error('No se puede restablecer porque ya fue cancelado en caja');
                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                }
                if ($formularioLiquidacion->estado == Estado::EnProceso) {
                    Flash::error('No se puede restablecer porque ya se encuentra con estado En proceso');
                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                }
                if ($formularioLiquidacion->estado == Estado::Composito or $formularioLiquidacion->estado == Estado::Vendido) {
                    Flash::error('No se puede restablecer porque ya se encuentra en un composito');
                    return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
                }

                $formularioLiquidacion->estado = Estado::EnProceso;
                $formularioLiquidacion->puntos = 0;

                if ($formularioLiquidacion->letra == 'D') {
                    $campo['valor'] = $formularioLiquidacion->puntos_calculo * -1;
                    $campo['cliente_id'] = $formularioLiquidacion->cliente_id;
                    $campo['descripcion'] = DescripcionPunto::LoteRestablecido . ' ' . $formularioLiquidacion->lote;
                    PuntoCliente::create($campo);
                }


                $formularioLiquidacion->save();


                $campos['descripcion'] = 'Lote ' . $formularioLiquidacion->lote . ' restablecido por ' . auth()->user()->personal->nombre_completo;
                $campos['formulario_liquidacion_id'] = $id;
                $campos['accion'] = AccionCambioFormulario::Restablecimiento;
                CambioFormulario::create($campos);

//                $obj = new RetencionPagoController();
//                $obj->restar($formularioLiquidacion);

                CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->update(['es_cancelado' => false]);

                //borrar cuenta por saldo negativo
                if ($formularioLiquidacion->saldo_favor < 0) {
                    $fechaLiquidacion = date('Y-m-d', strtotime($formularioLiquidacion->fecha_hora_liquidacion));

                    $cuentasSaldoNegativo = CuentaCobrar::whereOrigenId($formularioLiquidacion->cliente_id)
                        ->whereOrigenType(Cliente::class)
                        ->where('monto', (abs($formularioLiquidacion->saldo_favor)))
                        ->where(\DB::raw("created_at::TIMESTAMP::DATE"), $fechaLiquidacion)
                        ->whereClase(ClaseCuentaCobrar::SaldoNegativo)
                        ->whereEsCancelado(false)
                        ->where("motivo", 'ilike', "%$formularioLiquidacion->lote")
                        ->first();
                    $cuentasSaldoNegativo->delete();
                }


                event(new AccionCompleta("Restablecido", "Formulario restablecido", $formularioLiquidacion->id));

                Flash::success('Formulario restablecido correctamente.');
            }
            $this->actualizarValorPagableYPesoSeco($id);

            \DB::commit();

            return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function esCodigoRepetido($input, $id)
    {
        return FormularioLiquidacion::where('id', '<>', $id)
                ->where([
                    ['sigla', '=', $input['sigla']],
                    ['numero_lote', '=', $input['numero_lote']],
                    ['letra', '=', $input['letra']],
                    ['anio', '=', $input['anio']],
                    ['estado', '<>', 'Anulado']
                ])->count() > 0;
    }

    public function finalizar(Request $request, $id, $automatico = false, $deAfuera = false)
    {

        \DB::beginTransaction();
        try {
            $formularioLiquidacion = FormularioLiquidacion::find($id);

            if ($formularioLiquidacion->estado != Estado::EnProceso) {

                return response()->json(['res' => false, 'message' => 'No se puede finalizar, porque se encuentra en un estado distinto a En Proceso']);
            }
//descuentos repetidos
            $descuentos = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
                ->whereFormularioLiquidacionId($formularioLiquidacion->id)
                ->whereHas('descuentoBonificacion', function ($q) {
                    $q->
                    where([['clase', '<>', ClaseDescuento::Acumulativo], ['tipo', 'Descuento']])
                        ->where([['clase', '<>', ClaseDescuento::Acumulativo], ['tipo', TipoDescuentoBonificacion::Bonificacion]]);
                    // whereTipo('Descuento')->orWhere('tipo',TipoDescuentoBonificacion::Bonificacion);
                })->orderBy('descuento_bonificacion_id')->get();

            $array = [];
            foreach ($descuentos as $d) {
                array_push($array, $d->descuentoBonificacion->nombre);
            }
            if (count($array) > count(array_unique($array))) {
                return response()->json(['res' => false, 'message' => 'No se puede finalizar, porque existen retenciones repetidas']);
            }
            if ($formularioLiquidacion->cliente->cooperativa_id == 28) {
                $seccion = 'seccion';
                $descuentosContador = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
                    ->whereFormularioLiquidacionId($formularioLiquidacion->id)
                    ->whereHas('descuentoBonificacion', function ($q) use ($seccion) {
                        $q->whereTipo('Descuento')
                            ->where('nombre', 'ilike', "%{$seccion}%");
                    })->orderBy('descuento_bonificacion_id')->count();
                if ($descuentosContador == 0)
                    return response()->json(['res' => false, 'message' => 'Error! No existe descuento de SECCION, revise']);
            }
            //
            if (!$automatico) {
                /*   if ($formularioLiquidacion->costo->laboratorio == 0)
                       return response()->json(['res' => false, 'message' => 'Primero guarde el costo del laboratorio']);

                   $laboratorioContador = Laboratorio::whereFormularioLiquidacionId($id)->whereNotNull('valor')->whereOrigen('Dirimicion')->count();
                   if ($formularioLiquidacion->costo->dirimicion == 0 and $laboratorioContador > 0)
                       return response()->json(['res' => false, 'message' => 'Primero guarde el costo de dirimición']);
   */
                if (is_null($formularioLiquidacion->valor_por_tonelada and $formularioLiquidacion->totales['total_minerales'] != 0)) { // si no se encuentra en la matriz
                    return response()->json(['res' => false, 'message' => 'Error! No existe el valor por tonelada, revise']);

                }
            }



            //quemado
            if($formularioLiquidacion->totales['total_minerales']==0.00){
                $bonificaciones = FormularioDescuento::whereFormularioLiquidacionId($id)
                    ->whereHas('descuentoBonificacion', function ($q) {
                        $q->whereTipo(TipoDescuentoBonificacion::Bonificacion)
                            ->whereNombre("BONO TRANSPORTE");
                    })->get();

                foreach($bonificaciones as $b){
                    FormularioDescuento::
                    where('id', $b->id)->update([
                        'valor' => 20]);
                }

            }
            //fin quemado

//            $obj = new RetencionPagoController();
//            $obj->registrar($formularioLiquidacion);

            $contrato = ContratoPlantilla::whereTipo('Compra')->orderByDesc('id')->first();
            if (!$deAfuera) {
                $input['fecha_liquidacion'] = Fachada::setFormatoFecha($request->fecha_liquidacion);
                $aporte = $request->aporte_fundacion / 100;
                $formularioLiquidacion->aporte_fundacion = $aporte;
                $formularioLiquidacion->fecha_liquidacion = $input['fecha_liquidacion']; //date('Y-m-d');
            }
            $formularioLiquidacion->estado = Estado::Liquidado;
            $formularioLiquidacion->contrato_plantilla_id = $contrato->id;

            $formularioLiquidacion->fecha_hora_liquidacion = date('Y-m-d H:i:s');
            $formularioLiquidacion->puntos = $formularioLiquidacion->puntos_calculo;

            if ($formularioLiquidacion->letra == 'D') {
                $campo['valor'] = $formularioLiquidacion->puntos_calculo;
                $campo['cliente_id'] = $formularioLiquidacion->cliente_id;
                $campo['descripcion'] = DescripcionPunto::LoteFinalizado . ' ' . $formularioLiquidacion->lote;
                PuntoCliente::create($campo);
            }

            $formularioLiquidacion->save();


            CuentaCobrar::whereOrigenId($id)->whereOrigenType(FormularioLiquidacion::class)->update(['es_cancelado' => true]);


            if ($formularioLiquidacion->totales['total_saldo_favor'] < 0) {
                $campos['monto'] = $formularioLiquidacion->totales['total_saldo_favor'] * (-1);
                $campos['motivo'] = "CUENTAS POR COBRAR A {$formularioLiquidacion->cliente->nombre} POR SALDO NEGATIVO NRO LOTE: {$formularioLiquidacion->lote}";
//            'Liquidación por devolución de Lote ' . $devolucion->formularioLiquidacion->lote;
                $formularioDeCliente = FormularioLiquidacion::whereClienteId($formularioLiquidacion->cliente_id)->whereEstado(Estado::EnProceso)
                    ->where('id', '>', $id)->first();
                $campos['origen_type'] = Cliente::class;
                $campos['origen_id'] = $formularioLiquidacion->cliente_id;
                $campos['formulario_liquidacion_id'] = $formularioLiquidacion->id;

                if ($formularioDeCliente) {
                    $campos['origen_type'] = FormularioLiquidacion::class;
                    $campos['origen_id'] = $formularioDeCliente->id;
                }
                $cuenta = CuentaCobrar::create($campos);

                $tipo = FormularioLiquidacion::class;
                $this->registrarHistorialCuenta(AccionHistorialCuenta::CreacionSaldoNegativo, "Creación de cuenta por cobrar por saldo negativo en lote " . $formularioLiquidacion->lote, $formularioLiquidacion->id, $tipo);
                if ($formularioDeCliente) {
                    $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro,
                        "Registro de cuenta por cobrar en lote " . $formularioDeCliente->lote . " monto " . $cuenta->monto, $formularioLiquidacion->id, $tipo);
                    $formularioDeCliente = FormularioLiquidacion::find($formularioDeCliente->id);
                    $formularioDeCliente->update(['total_cuenta_cobrar' => $formularioDeCliente->totales['total_cuentas_cobrar']]);
                    event(new AccionCompleta("Cuenta agregada", "Cuenta por cobrar agregada con monto BOB " . $cuenta->monto, $formularioDeCliente->id));

                }
            }
            $this->actualizarValorPagableYPesoSeco($id);
            $this->actualizarSaldoYLiquido($formularioLiquidacion);
            $this->actualizarTotalDescuentosBonificaciones($formularioLiquidacion);

            $this->adjuntarContrato($formularioLiquidacion);


            event(new AccionCompleta("Finalizado", "Formulario finalizado con saldo a favor: " . $formularioLiquidacion->totales['total_saldo_favor'], $formularioLiquidacion->id));
            \DB::commit();
            return response()->json(['res' => true, 'message' => 'Formulario finalizado correctamente']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }


    public function imprimir($formulario_id, $nombre = null)
    {
        $resumen = $this->getResumen($formulario_id);
        $formularioLiquidacion = $resumen['formulario'];
        $cotizacionesDiarias = $resumen['diarias'];

        $descuentos = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Descuento');
            })->orderBy('descuento_bonificacion_id')->get();
        $bonificaciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Bonificacion')
                    ->whereClase(ClaseDescuento::EnLiquidacion);
            })->orderBy('descuento_bonificacion_id')->get();
        $retenciones = FormularioDescuento::with(['descuentoBonificacion', 'formulario'])
            ->whereFormularioLiquidacionId($formulario_id)
            ->whereHas('descuentoBonificacion', function ($q) {
                $q->whereTipo('Retencion');
            })->orderBy('descuento_bonificacion_id')->get();
        $totalRegalias = 0;
        /* $pagable=$formularioLiquidacion->valor_neto_venta - $retenciones->sum('sub_total') - $descuentos->sum('sub_total')+$bonificaciones->sum('sub_total') - $formularioLiquidacion->totales['total_anticipos'];*/

        //generador qr
        $urlQR = url("/imprimirFormulario/{$formulario_id}");
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
        $vistaurl = "formulario_liquidacions.impresion";
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
        $canvas->page_text(350, 810, 'FECHA Y HORA DE IMPRESIÓN: ORURO ' . $fechaImpresion, null, 7, array(0, 0, 0));


        return $pdf->stream('Liquidación ' . $formularioLiquidacion->lote . '.pdf');
    }

    public function kardex(Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fechaFinal = $fechaFin;
        $tipo = $request->tipo;
        $productoLetra = $request->producto_id;

        if (is_null($tipo))
            $tipo = 1;
        $campos = CampoReporte::whereTipoReporteId($tipo)->select('codigo', 'visible')->get();

        $estado = $request->txtEstado;
        if ($fechaInicio == null or $fechaFin == null) {
            $formularios = FormularioKardex::where('id', '>', '300000000')->get();
        } else {
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' + 1 days'));
            $formularios = FormularioKardex::
            where([['fecha_liquidacion', '>=', $fechaInicio], ['fecha_liquidacion', '<', $fechaFin]])
                ->where(function ($q) use ($productoLetra) {
                    if (!is_null($productoLetra)) {
                        if ($productoLetra == '%') {
                            $q->whereIn('producto', ['A | Zinc Plata', 'B | Plomo Plata', 'C | Complejo']);
                        } else {
                            $q->where('producto', 'like', "$productoLetra%");
                        }

                    }
                })
//                ->where('producto', 'like', "$productoLetra%")
                ->where(function ($q) use ($estado) {
                    if ($estado == 'comprado') {
                        $q->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido]);
                    } else {
                        $q->where('estado', 'ilike', "%{$estado}%");
                    }
                })
                ->where('id', '<>', 561) // form pagado pero q se le devolvio
                ->orderBy('id')
                ->get();
        }
//dd($formularios[0]->laboratorio_promedio);
        return view('formulario_liquidacions.kardex', compact('formularios', 'fechaInicio', 'fechaFinal', 'productoLetra', 'campos', 'tipo'));
    }

    private function actualizarValorPagableYPesoSeco($formularioId)
    {
        $formulario = FormularioLiquidacion::findOrFail($formularioId);
        if (round($formulario->peso_neto_seco, 2) != round($formulario->peso_seco, 2))
            FormularioLiquidacion::where('id', $formularioId)->update(['peso_seco' => $formulario->peso_neto_seco]);


        if (round($formulario->valor_neto_venta, 2) != round(($formulario->neto_venta), 2))
            FormularioLiquidacion::where('id', $formularioId)->update(['neto_venta' => $formulario->valor_neto_venta]);

        if (round($formulario->regalia_minera, 2) != round(($formulario->totales['total_minerales']), 2))
            FormularioLiquidacion::where('id', $formularioId)->update(['regalia_minera' => $formulario->totales['total_minerales']]);

        if (round($formulario->humedad_promedio, 2) != round(($formulario->humedad), 2)) {
            FormularioLiquidacion::where('id', $formularioId)->update(['humedad_promedio' => $formulario->humedad]);
        }
        if (round($formulario->humedad_kilo, 5) != round(($formulario->humedad_kg), 5)) {
            FormularioLiquidacion::where('id', $formularioId)->update(['humedad_kilo' => $formulario->humedad_kg,]);
        }

    }

    public function actualizarSaldoYLiquido($formulario)
    {
        if (round($formulario->totales['total_saldo_favor'], 2) != round(($formulario->saldo_favor), 2))
            $formulario->update(['saldo_favor' => $formulario->totales['total_saldo_favor']]);
        if (round($formulario->totales['total_liquidacion'], 2) != round(($formulario->liquido_pagable), 2))
            $formulario->update(['liquido_pagable' => $formulario->totales['total_liquidacion']]);
    }

    public static function getLotesActivos($letra)
    {
        $lotes = FormularioLiquidacion::whereLetra($letra)->whereEstado(Estado::EnProceso)->orderBy('id')->get()->pluck('lote', 'id')->toArray();

        return json_encode($lotes);
    }

    public function intercambiarLote(Request $request)
    {
        \DB::beginTransaction();
        try {

            $id1 = $request->lote1;
            $id2 = $request->lote2;

            $form1 = FormularioLiquidacion::find($id1);
            $form2 = FormularioLiquidacion::find($id2);

            $numeroLote1 = $form1->numero_lote;
            $numeroLote2 = $form2->numero_lote;

            $form1->update(['numero_lote' => $numeroLote2]);
            $form2->update(['numero_lote' => $numeroLote1]);

            event(new AccionCompleta("Intercambio", "Lote intercambiado con " . $form2->lote, $id1));
            event(new AccionCompleta("Intercambio", "Lote intercambiado con " . $form1->lote, $id2));


            \DB::commit();
            Flash::success('Lotes intercambiados correctamente.');

            return redirect(route('formularioLiquidacions.index'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function anular($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $formularioLiquidacion = FormularioLiquidacion::find($id);

            $anticipo = Anticipo::whereFormularioLiquidacionId($id)->where('es_cancelado', false)->count();
            if ($anticipo > 0 and is_null($request->es_retiro)) {
                Flash::error('No se puede anular el formulario si existen anticipos');
                return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
            }
            if ($formularioLiquidacion->totales['total_cuentas_cobrar'] > 0) {
                Flash::error('No se puede anular el formulario si existen cuentas por cobrar');
                return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
            }

            $devoluciones = Bono::whereFormularioLiquidacionId($id)->count();

            if (!is_null($request->es_retiro) and is_null($request->destino) and $devoluciones==0) {
                $dev['fecha'] = date('Y-m-d');
                $dev['monto'] = 0;
                $dev['motivo'] = "ANALISIS";
                $dev['formulario_liquidacion_id'] = $id;
                $dev['es_aprobado'] = true;
                $dev['tipo_motivo'] = TipoMotivoDevolucion::Analisis;

                Bono::create($dev);
            }
            if (!is_null($request->destino)) {
                $devoluciones = Bono::whereFormularioLiquidacionId($id)->get();
                $formularioDestino = FormularioLiquidacion::find($request->destino);
                foreach ($devoluciones as $devolucion) {
                    $devolucion->clase = ClaseDevolucion::Externo;
                    $devolucion->es_aprobado = true;
                    $devolucion->es_cancelado = true;
                    $devolucion->save();
                    event(new AccionCompleta("Devolución Transferida", "Devolución Transferida a lote " . $formularioDestino->lote . " con monto " . $devolucion->monto, $id));
                    event(new AccionCompleta("Devolución Registrada", "Devolución Transferida de lote " . $formularioLiquidacion->lote . " con monto " . $devolucion->monto, $request->destino));
                }

                foreach ($devoluciones as $devolucion) {

                    $cu["motivo"] = "RETIRO DE MATERIAL, " . $devolucion->motivo . ', ' . $formularioLiquidacion->lote;
                    $cu["monto"] = $devolucion->monto;
                    $cu["origen_id"] = $request->destino;
                    $cu["origen_type"] = FormularioLiquidacion::class;
                    $cu["clase"] = ClaseCuentaCobrar::Retiro;
                    $cu["formulario_liquidacion_id"] = $devolucion->formulario_liquidacion_id;

                    CuentaCobrar::create($cu);
                    $this->registrarHistorialCuenta(AccionHistorialCuenta::Registro, "Registro de cuenta por cobrar en lote " .
                        $formularioDestino->lote . " monto " . $devolucion->monto, $devolucion->formulario_liquidacion_id, FormularioLiquidacion::class);

                }
            }

            $formularioLiquidacion->estado = Estado::Anulado;
            $formularioLiquidacion->motivo_anulacion = $request->motivo_anulacion;
            $formularioLiquidacion->save();

            event(new AccionCompleta("Anulado", "Formulario anulado", $formularioLiquidacion->id));

            \DB::commit();
            Flash::error('Formulario anulado correctamente.');

            return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    private function registrarHistorialCuenta($accion, $observacion, $origen_id = null, $origen_type = null)
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

    public function concluirMolienda($formId)
    {
        FormularioLiquidacion::whereId($formId)->update(['en_molienda' => false]);
        event(new AccionCompleta("Molienda", "Molienda Concluida", $formId));
        Flash::success('Molienda concluida.');

        return redirect(route('formularioLiquidacions.index'));
    }

    public function liquidacionAutomatica()
    {
        \DB::beginTransaction();
        try {
            $fecha = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 14 days'));
            $lotes = FormularioLiquidacion::whereEstado(Estado::EnProceso)->where('created_at', '<', $fecha)
                ->where('humedad_promedio', '<>', 0.00)->get();

            foreach ($lotes as $lote) {
                if (!str_contains($lote->ley_producto, '0.00')) {
                    $request = request()->merge(['fecha_liquidacion' => date('Y-m-d'), 'aporte_fundacion' => 0]);
                    $this->finalizar($request, $lote->id, true, false);
                }
            }
            \DB::commit();
            return redirect(route('formularioLiquidacions.index'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }

    }


    public function finalizarAfuera($id)
    {
        $form = FormularioLiquidacion::find($id);

        if (str_contains($form->ley_producto, ' 0.00')) {
            Flash::error('Error! No existen las leyes, revise');
            return redirect(route('formularioLiquidacions.index'));
        }

        if (is_null($form->valor_por_tonelada) or $form->valor_por_tonelada == 0) { // si no se encuentra en la matriz
            Flash::error('Error! No existe el valor por tonelada, revise');
            return redirect(route('formularioLiquidacions.index'));
        }


        if ($form->estado == !\App\Patrones\Estado::EnProceso or $form->cantidad_devoluciones != 0) {
            Flash::error('Error! No se puede finalizar el lote, revise');
            return redirect(route('formularioLiquidacions.index'));
        }


        $request = request()->merge(['fecha_liquidacion' => date('Y-m-d'), 'aporte_fundacion' => 0]);
        $this->finalizar($request, $id, true, true);
        Flash::success('Lote finalizado correctamente');

        return redirect(route('formularioLiquidacions.index'));

    }

    public function cambiarProducto(Request $request)
    {
        $id = $request->idFormu;

        \DB::beginTransaction();
        try {
            $producto = Producto::findOrFail($request->producto_id);

            $form = FormularioLiquidacion::find($id);

            if ($form->letra == $producto->letra) {
                Flash::error('EL producto es el mismo que estaba originalmente');
                return redirect(route('formularioLiquidacions.edit', [$form]));
            }

            if (!in_array($form->letra, ['A', 'B', 'C']) or !in_array($producto->letra, ['A', 'B', 'C']) or $form->estado != Estado::EnProceso) {
                Flash::error('No se puede realizar el cambio de producto');
                return redirect(route('formularioLiquidacions.edit', [$form]));
            }
            $form->update(['letra' => $producto->letra, 'producto' => $producto->info]);

            $lab = Laboratorio::whereFormularioLiquidacionId($id)->whereNotNull('mineral_id');
            $lab->delete();

            $liquidacion = LiquidacionMineral::whereFormularioLiquidacionId($id);
            $liquidacion->delete();

            //registro de minerales
            $productoMinerales = $producto->productoMinerals;
            foreach ($productoMinerales as $row) {
                if (!$row->es_penalizacion) {
                    $campos['es_penalizacion'] = $row->es_penalizacion;
                    $campos['ley_minima'] = ($producto->letra == 'E' and $form->tipo_material == TipoMaterial::Broza) ? 4.00 : $row->ley_minima;
                    $campos['formulario_liquidacion_id'] = $id;
                    $campos['mineral_id'] = $row->mineral_id;
                    LiquidacionMineral::create($campos);
                }
            }
//laboratorios
            foreach ($productoMinerales as $row) {
                if (!$row->es_penalizacion) {
                    $labo['valor'] = 0;
                    $labo['unidad'] = $row->mineral->unidad_laboratorio;
                    $labo['origen'] = 'Empresa';
                    $labo['formulario_liquidacion_id'] = $id;
                    $labo['mineral_id'] = $row->mineral_id;
                    $labo['es_penalizacion'] = $row->es_penalizacion;
                    Laboratorio::create($labo);

                    $labo['origen'] = 'Cliente';
                    Laboratorio::create($labo);

                    $labo['valor'] = null;
                    $labo['origen'] = 'Dirimicion';
                    Laboratorio::create($labo);
                }
            }


            ///////////adjuntar boleta de pesaje a documentos
            $objRep = new ReporteController();
            $objCaja = new CajaController();
            $objRep->generarBoletaPesaje($id);

            $formularioLiquidacion = FormularioLiquidacion::findOrFail($id);
            $res = $objCaja->subirDocumento($formularioLiquidacion);
            $formularioLiquidacion->url_documento = $res;
            $formularioLiquidacion->save();
            DocumentoCompra::whereFormularioLiquidacionId($formularioLiquidacion->id)->whereDescripcion(\App\Patrones\DocumentoCompra::BoletaPesaje)
                ->update(['agregado' => true]);
            ///////////

            event(new AccionCompleta("Modificado", "Producto modificado", $formularioLiquidacion->id));

            \DB::commit();

            Flash::success('Producto actualizado correctamente');
            return redirect(route('formularioLiquidacions.edit', [$formularioLiquidacion]));


        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }

    public function cambiarTornaguia(Request $request, $id)
    {
        try {
            $form = FormularioLiquidacion::findOrFail($id);
            $form->con_tornaguia = !$form->con_tornaguia;
            $form->save();
            return ['res' => true, 'message' => 'Cambiado correctamente'];
        } catch (\Exception $e) {
            return $this->make_exception($e);
        }
    }

    public function reiniciarDescuentos($id)
    {
        \DB::beginTransaction();
        try {
            $form = FormularioLiquidacion::find($id);
            $cliente = Cliente::find($form->cliente_id);
            \DB::select("delete from formulario_descuento where formulario_liquidacion_id = ? ", [$form->id]);


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
            $this->storeDescuentos($form->id, $form->cliente_id, $descuentos, $form->letra);

            \DB::commit();

            return response()->json(['res' => true, 'message' => 'Descuentos/Bonificaciones reiniciados']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }
}
