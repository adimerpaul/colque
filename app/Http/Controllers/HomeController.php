<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CotizacionDiaria;
use App\Models\Material;
use App\Models\FormularioLiquidacion;
use App\Models\SatisfaccionCliente;
use App\Models\TipoCambio;
use App\Models\Venta;
use App\Patrones\Estado;
use App\Patrones\EstadoVenta;
use App\Patrones\Fachada;
use App\Patrones\Metas;
use App\Patrones\Rol;
use App\Patrones\TipoLoteVenta;
use App\Patrones\TipoSatisfaccionCliente;
use App\Repositories\FormularioLiquidacionRepository;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->rol==Rol::ClienteLab or auth()->user()->rol==Rol::Laboratorio)
            return redirect()->route('inicio-lab');
        $this->registrarCambio();
        $obj = new MovimientoController();
        $obj->actualizarSaldoInicial();


        if (!Fachada::cambioPass())
            return redirect()->route('users.editPass');

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $mes = $request->mes;

        if (!$fechaInicio)
            $fechaInicio = date('Y-m', strtotime(date('Y-m') . ' - 1 years'));
        if (!$fechaFin)
            $fechaFin = date('Y-m');

        $fechaInicioVenta = $request->fecha_inicio_venta;
        $fechaFinVenta = $request->fecha_fin_venta;

        if (!$fechaInicioVenta)
            $fechaInicioVenta = date('Y-m', strtotime(date('2023-05')));
        if (!$fechaFinVenta)
            $fechaFinVenta = date('Y-m');

        $fechaInicioStock = $request->fecha_inicio_stock;
        $fechaFinStock = $request->fecha_fin_stock;

        if (!$fechaInicioStock)
            $fechaInicioStock = date('Y-m', strtotime(date('Y-m') . ' - 1 years'));
        if (!$fechaFinStock)
            $fechaFinStock = date('Y-m');

        $fechaInicialVenta = $request->fecha_inicial_venta;
        $fechaFinalVenta = $request->fecha_final_venta;

        if (!$fechaInicialVenta)
            $fechaInicialVenta = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 month'));
        if (!$fechaFinalVenta)
            $fechaFinalVenta = date('Y-m-d');

        $fechaInicioProductor = $request->fecha_inicio_productor;
        $fechaFinProductor = $request->fecha_fin_productor;

        if (!$fechaInicioProductor)
            $fechaInicioProductor = date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days'));
        if (!$fechaFinProductor)
            $fechaFinProductor = date('Y-m-d');

        $chartNetoYSeco = $this->estadisticaNetoYSeco(Fachada::getFechasAnio()['inicio'], Fachada::getFechasAnio()['fin']);

        $chartPesoNetoSeco = $this->estadisticaPesoNetoSeco($fechaInicio, $fechaFin);
        $chartValorNetoVenta = $this->estadisticaValorNetoVenta($fechaInicio, $fechaFin);

        $chartPesoNetoSecoVenta = $this->estadisticaPesoNetoSecoVenta($fechaInicioVenta, $fechaFinVenta);
        $chartValorNetoVentaVenta = $this->estadisticaValorNetoVentaVenta($fechaInicioVenta, $fechaFinVenta);

        $chartComprasMes = $this->estadisticaComprasMes($mes);
        $chartSatisfaccionClientes = $this->estadisticaSatisfaccionClientes();
        $reporteHistorico = $this->reporteHistorico($fechaInicio, $fechaFin);
        $reporteHistoricoStock = $this->reporteHistoricoStock($fechaInicioStock, $fechaFinStock);
        $reporteProductoresLiquidados = $this->getCooperativasLiquidadas($fechaInicioProductor, $fechaFinProductor);

        $reporteHistoricoVenta = $this->reporteHistoricoVenta($fechaInicioVenta, $fechaFinVenta);
        $reporteDetalleVenta = $this->reporteDetalleVentas($fechaInicialVenta, $fechaFinalVenta);
        $reporteHistoricoProducto = $this->reporteHistoricoProducto($fechaInicio, $fechaFin);
        $reporteHistoricoProductoStock = $this->reporteHistoricoProductoStock($fechaInicioStock, $fechaFinStock);
        $reporteHistoricoProductoVenta = $this->reporteHistoricoProductoVenta($fechaInicioVenta, $fechaFinVenta);
        $clientes = $this->getCountClientes();

        $comprasNetoVenta = $this->getCompras()['netoVenta'];
        $comprasPesoSeco = $this->getCompras()['pesoSeco'];

        $inventarioNetoVenta = $this->getInventario()['netoVenta'];
        $inventarioPesoSeco = $this->getInventario()['pesoSeco'];

        $ventasNetoVenta = $this->getVentas()['netoVenta'];
        $ventasPesoSeco = $this->getVentas()['pesoSeco'];

        $comprasMetaNetoVenta = $this->getComprasMeta()['netoVenta'];
        $comprasMetaPesoSeco = $this->getComprasMeta()['pesoSeco'];

        $comprasMetaSn = $this->getComprasProductoMeta()['netoVentaSn'];
        $comprasMetaZnAg = $this->getComprasProductoMeta()['netoVentaZnAg'];
        $comprasMetaPbAg = $this->getComprasProductoMeta()['netoVentaPbAg'];

        $ventasMeta = $this->getVentasMeta();
        $metas = $this->getMetas();
        $porcentajesMetas = $this->getPorcentajesMetas();

        $minerales = Material::all();

        return view('home', compact('minerales', 'reporteHistorico', 'chartPesoNetoSeco',
            'chartValorNetoVenta', 'chartComprasMes', 'clientes',
            'comprasNetoVenta', 'comprasPesoSeco', 'inventarioNetoVenta', 'inventarioPesoSeco', 'ventasNetoVenta',
            'ventasPesoSeco', 'comprasMetaNetoVenta', 'comprasMetaPesoSeco', 'metas', 'porcentajesMetas',
            'comprasMetaSn', 'comprasMetaZnAg', 'comprasMetaPbAg', 'ventasMeta', 'chartNetoYSeco', 'chartSatisfaccionClientes',
            'reporteHistoricoProducto', 'reporteHistoricoProductoStock', 'reporteHistoricoStock', 'reporteHistoricoVenta', 'reporteHistoricoProductoVenta',
            'chartPesoNetoSecoVenta', 'chartValorNetoVentaVenta', 'reporteDetalleVenta', 'reporteProductoresLiquidados'));
    }

    private function getMetas()
    {
        return (['comprasPesoSeco' => Metas::comprasPesoSeco, 'comprasNetoVenta' => Metas::comprasNetoVenta,
            'comprasSn' => Metas::comprasSn, 'comprasZnAg' => Metas::comprasZnAg,
            'comprasPbAg' => Metas::comprasPbAg, 'ventasMensuales' => Metas::ventasMensuales]);
    }

    private function getPorcentajesMetas()
    {
        $comprasPesoSeco = $this->getComprasMeta()['pesoSeco'] * 100 / $this->getMetas()['comprasPesoSeco'];
        $comprasNetoVenta = $this->getComprasMeta()['netoVenta'] * 100 / $this->getMetas()['comprasNetoVenta'];
        $comprasSn = $this->getComprasProductoMeta()['netoVentaSn'] * 100 / $this->getMetas()['comprasSn'];
        $comprasZnAg = $this->getComprasProductoMeta()['netoVentaZnAg'] * 100 / $this->getMetas()['comprasZnAg'];
        $comprasPbAg = $this->getComprasProductoMeta()['netoVentaPbAg'] * 100 / $this->getMetas()['comprasPbAg'];
        $ventasMensuales = $this->getVentasMeta() * 100 / $this->getMetas()['ventasMensuales'];

        return (['comprasPesoSeco' => $comprasPesoSeco, 'comprasNetoVenta' => $comprasNetoVenta
            , 'comprasSn' => $comprasSn, 'comprasZnAg' => $comprasZnAg, 'comprasPbAg' => $comprasPbAg,
            'ventasMensuales' => $ventasMensuales]);
    }

    private function getFechasQuincena()
    {
        $fecha = date('Y-m-d');
        if (date('d') > 15) {
            $fechaInicio = date("Y-m-16 00:00:00", strtotime($fecha));
            $fechaFin = date("Y-m-t 23:59:59", strtotime($fecha));
        } else {
            $fechaInicio = date("Y-m-01 00:00:00", strtotime($fecha));
            $fechaFin = date("Y-m-15 23:59:59", strtotime($fecha));
        }
        return (['inicio' => $fechaInicio, 'fin' => $fechaFin]);
    }

    private function getVentasMeta()
    {
        $mes = date('Y-m');
        $cantidad = Venta::
        where('estado', EstadoVenta::Liquidado)
            ->whereTipoLote(TipoLoteVenta::Venta)
            ->where(DB::raw("to_char(fecha_venta, 'YYYY-MM')"), $mes)
            ->count();
        return $cantidad;
    }

    private function getComprasProductoMeta()
    {
        $sn = FormularioLiquidacion::where('fecha_liquidacion', '>=', Fachada::getFechasAnio()['inicio'])
            ->where('fecha_liquidacion', '<=', Fachada::getFechasAnio()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->whereLetra('D')
            ->sum('neto_venta');
        $znAg = FormularioLiquidacion::where('fecha_liquidacion', '>=', Fachada::getFechasAnio()['inicio'])
            ->where('fecha_liquidacion', '<=', Fachada::getFechasAnio()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->whereLetra('A')
            ->sum('neto_venta');
        $pbAg = FormularioLiquidacion::where('fecha_liquidacion', '>=', Fachada::getFechasAnio()['inicio'])
            ->where('fecha_liquidacion', '<=', Fachada::getFechasAnio()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->whereLetra('B')
            ->sum('neto_venta');
        return (['netoVentaSn' => $sn, 'netoVentaZnAg' => $znAg, 'netoVentaPbAg' => $pbAg]);
    }

    private function getComprasMeta()
    {
        $pesoSeco = FormularioLiquidacion::where('fecha_liquidacion', '>=', Fachada::getFechasAnio()['inicio'])
            ->where('fecha_liquidacion', '<=', Fachada::getFechasAnio()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->sum('peso_seco');
        $netoVenta = FormularioLiquidacion::where('fecha_liquidacion', '>=', Fachada::getFechasAnio()['inicio'])
            ->where('fecha_liquidacion', '<=', Fachada::getFechasAnio()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->sum('neto_venta');
        return (['pesoSeco' => $pesoSeco, 'netoVenta' => $netoVenta]);
    }

    private function getVentas()
    {
        $pesoSeco = Venta::where('fecha_venta', '>=', $this->getFechasQuincena()['inicio'])
            ->where('fecha_venta', '<=', $this->getFechasQuincena()['fin'])
            ->where('estado', EstadoVenta::Liquidado)
            ->whereTipoLote(TipoLoteVenta::Venta)
            ->get()
            ->sum('suma_peso_neto_seco');
        $netoVenta = Venta::where('fecha_venta', '>=', $this->getFechasQuincena()['inicio'])
            ->where('fecha_venta', '<=', $this->getFechasQuincena()['fin'])
            ->where('estado', EstadoVenta::Liquidado)
            ->whereTipoLote(TipoLoteVenta::Venta)
            ->get()
            ->sum('suma_valor_neto_venta');
        return (['pesoSeco' => $pesoSeco, 'netoVenta' => $netoVenta]);
    }

    private function getCompras()
    {
        $pesoSeco = FormularioLiquidacion::where('fecha_liquidacion', '>=', $this->getFechasQuincena()['inicio'])
            ->where('fecha_liquidacion', '<=', $this->getFechasQuincena()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->sum('peso_seco');
        $netoVenta = FormularioLiquidacion::where('fecha_liquidacion', '>=', $this->getFechasQuincena()['inicio'])
            ->where('fecha_liquidacion', '<=', $this->getFechasQuincena()['fin'])
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->sum('neto_venta');
        return (['pesoSeco' => $pesoSeco, 'netoVenta' => $netoVenta]);
    }

    private function getInventario()
    {
        $pesoSeco = FormularioLiquidacion::
        whereIn('estado', [Estado::Liquidado, Estado::Composito])
            ->sum('peso_seco');
        $netoVenta = FormularioLiquidacion::
        whereIn('estado', [Estado::Liquidado, Estado::Composito])
            ->sum('neto_venta');
        return (['pesoSeco' => $pesoSeco, 'netoVenta' => $netoVenta]);
    }

    private function getCountClientes()
    {
        return Cliente::where('created_at', '>=', $this->getFechasQuincena()['inicio'])
            ->where('created_at', '<=', $this->getFechasQuincena()['fin'])->count();
    }

    private function reporteHistorico($fechaInicio, $fechaFin)
    {
        $form = FormularioLiquidacion::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as
            fecha,
	            fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'compra')
	            as peso,

	            fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as
	            varchar) ,1, 7), 'compra') as neto_venta,
	            fn_saldo_favor(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as
	            varchar) ,1, 7)) as saldo_favor"))
            ->groupBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->get();

        return $form;
    }

    private function reporteHistoricoStock($fechaInicio, $fechaFin)
    {
        $form = FormularioLiquidacion::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as
            fecha,
	            fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'stock')
	            as peso,

	            fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as
	            varchar) ,1, 7), 'stock') as neto_venta"))
            ->groupBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->get();

        return $form;
    }

    private function reporteHistoricoVenta($fechaInicio, $fechaFin)
    {
        $venta = Venta::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7) as
            fecha,
	            fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7), 'venta')
	            as peso,

	            fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as
	            varchar) ,1, 7), 'venta') as neto_venta"))
            ->groupBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->where([[DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '<=', $fechaFin],
                ["tipo_lote", '=', TipoLoteVenta::Venta]])
            ->get();
        return $venta;
    }

    private function reporteDetalleVentas($fechaInicio, $fechaFin)
    {
        $ventas = Venta::whereEstado(EstadoVenta::Liquidado)
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->whereTipoLote(TipoLoteVenta::Venta)
            ->orderBy('fecha_venta')
            ->get();

        return $ventas;
    }

    private function reporteHistoricoProducto($fechaInicio, $fechaFin)
    {
        $form = FormularioLiquidacion::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as
            fecha,
            formulario_liquidacion.letra,
	            fn_peso_seco_producto(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), formulario_liquidacion.letra, 'compra')
	            as peso,

	             fn_peso_neto_humedo_producto(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), formulario_liquidacion.letra)
	            as peso_neto_humedo,

	            fn_neto_venta_producto(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as
	            varchar) ,1, 7), formulario_liquidacion.letra, 'compra') as neto_venta"))
            ->groupBy('formulario_liquidacion.letra', DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->get();

        return $form;
    }


    private function reporteHistoricoProductoStock($fechaInicio, $fechaFin)
    {
        $form = FormularioLiquidacion::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as
            fecha,
            formulario_liquidacion.letra,
	            fn_peso_seco_producto(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), formulario_liquidacion.letra, 'stock')
	            as peso,

	            fn_neto_venta_producto(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as
	            varchar) ,1, 7), formulario_liquidacion.letra, 'stock') as neto_venta"))
            ->groupBy('formulario_liquidacion.letra', DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->get();

        return $form;
    }

    public function getCooperativasLiquidadas($fechaInicial, $fechaFinal){


        $cooperativas = \DB::select("
              SELECT cooperativa.razon_social, c1.cooperativa_id, cooperativa.nit, f1.producto,
	            (SELECT count(*) FROM cooperativa
	                INNER JOIN cliente c2 ON cooperativa.id = c2.cooperativa_id
	                INNER JOIN formulario_liquidacion f2 ON f2.cliente_id = c2.id
		            WHERE f2.estado in ('Liquidado', 'Composito', 'Vendido') and f2.regalia_minera<>0
		                and c2.cooperativa_id = c1.cooperativa_id and f2.fecha_liquidacion>=? and f2.fecha_liquidacion<=?
	                    and f2.letra=f1.letra
	            )
                FROM cooperativa INNER JOIN cliente c1 ON cooperativa.id = c1.cooperativa_id
	            INNER JOIN formulario_liquidacion f1 ON f1.cliente_id = c1.id
		        WHERE f1.estado in ('Liquidado', 'Composito', 'Vendido') and f1.regalia_minera<>0
		        and f1.fecha_liquidacion>=? and f1.fecha_liquidacion<=?
		        GROUP BY cooperativa.razon_social,c1.cooperativa_id, cooperativa.nit, f1.letra, f1.producto
                ORDER BY c1.cooperativa_id

                                ", [$fechaInicial, $fechaFinal, $fechaInicial, $fechaFinal]);

        return $cooperativas;
    }

    private function reporteHistoricoProductoVenta($fechaInicio, $fechaFin)
    {
        $venta = Venta::
        select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7) as
            fecha,
            venta.letra,
	            fn_peso_seco_producto(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7), venta.letra, 'venta')
	            as peso,

	            fn_neto_venta_producto(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as
	            varchar) ,1, 7), venta.letra, 'venta') as neto_venta"))
            ->groupBy('venta.letra', DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->where([[DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '<=', $fechaFin],
                ["tipo_lote", '=', TipoLoteVenta::Venta]])
            ->get();

        return $venta;
    }

    private function estadisticaPesoNetoSeco($fechaInicio, $fechaFin)
    {
        $pesoNetoSeco = DB::table('formulario_liquidacion')
            ->select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as fecha,
	            CAST(fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'compra')AS INTEGER) as peso"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->groupBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->get();

        $chartPesoNetoSeco = \Chart::title(['text' => 'REPORTE PESO NETO SECO',])
            ->chart(['type' => 'line', 'renderTo' => 'chartPesoNetoSeco'])
            ->subtitle(['text' => '(Expresado en KG)<br>Del ' . date('m/Y', strtotime($fechaInicio)) . ' al ' . date('m/Y', strtotime($fechaFin))])
            ->colors(['#0c2959'])
            ->xaxis([
                'categories' => $pesoNetoSeco->pluck('fecha'),
                'labels' => ['rotation' => 15, 'align' => 'top',],
                'title' => ['text' => 'Meses']
            ])
            ->yaxis(['title' => ['text' => 'Peso Neto Seco']])
            ->legend(['layout' => 'vertikal', 'align' => 'right', 'verticalAlign' => 'middle'])
            ->series([['name' => 'KG', 'data' => $pesoNetoSeco->pluck('peso')]])
            ->display();

        return $chartPesoNetoSeco;
    }

    private function estadisticaPesoNetoSecoVenta($fechaInicio, $fechaFin)
    {
        $pesoNetoSeco = DB::table('venta')
            ->select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7) as fecha,
	            CAST(fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7), 'venta')AS INTEGER) as peso"))
            ->where([[DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '<=', $fechaFin],
                ["tipo_lote", '=', TipoLoteVenta::Venta]])
            ->groupBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->get();

        $chartPesoNetoSeco = \Chart::title(['text' => 'REPORTE PESO NETO SECO',])
            ->chart(['type' => 'line', 'renderTo' => 'chartPesoNetoSecoVenta'])
            ->subtitle(['text' => '(Expresado en KG)<br>Del ' . date('m/Y', strtotime($fechaInicio)) . ' al ' . date('m/Y', strtotime($fechaFin))])
            ->colors(['#0c2959'])
            ->xaxis([
                'categories' => $pesoNetoSeco->pluck('fecha'),
                'labels' => ['rotation' => 15, 'align' => 'top',],
                'title' => ['text' => 'Meses']
            ])
            ->yaxis(['title' => ['text' => 'Peso Neto Seco']])
            ->legend(['layout' => 'vertikal', 'align' => 'right', 'verticalAlign' => 'middle'])
            ->series([['name' => 'KG', 'data' => $pesoNetoSeco->pluck('peso')]])
            ->display();

        return $chartPesoNetoSeco;
    }

    private function estadisticaNetoYSeco($fechaInicio, $fechaFin)
    {
        $valorNetoVenta = DB::table('formulario_liquidacion')
            ->select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as fecha,
	            CAST(fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'compra') AS INTEGER) as neto_venta,
	            CAST(fn_peso_neto_seco(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'compra')AS INTEGER) as peso"))
            ->where([[DB::raw("formulario_liquidacion.fecha_liquidacion"), '>=', $fechaInicio],
                [DB::raw("formulario_liquidacion.fecha_liquidacion"), '<=', $fechaFin]])
            ->groupBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->get();
        $chartNetoYSeco = \Chart::title(['text' => 'REPORTE VALOR NETO DE VENTA (BOB) Y PESO NETO SECO (KG)',])
            ->chart(['type' => 'areaspline', 'renderTo' => 'chartNetoYSeco'])
            ->subtitle(['text' => 'Del ' . date('m/Y', strtotime($fechaInicio)) . ' al ' . date('m/Y', strtotime($fechaFin))])
            ->xaxis([
                'categories' => $valorNetoVenta->pluck('fecha'),
                'labels' => ['rotation' => 15, 'align' => 'top',],
                'title' => ['text' => 'Meses']
            ])
            ->yaxis(['title' => ['text' => 'Valor Neto Venta y Peso Neto Seco']])
            ->legend(['layout' => 'vertikal', 'align' => 'right', 'verticalAlign' => 'middle'])
            ->series([['name' => 'VNV', 'data' => $valorNetoVenta->pluck('neto_venta')],
                ['name' => 'PNS', 'data' => $valorNetoVenta->pluck('peso')]])
            ->display();

        return $chartNetoYSeco;
    }

    private function estadisticaValorNetoVenta($fechaInicio, $fechaFin)
    {
        $valorNetoVenta = DB::table('formulario_liquidacion')
            ->select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7) as fecha,
	            CAST(fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)as varchar) ,1, 7), 'compra') AS INTEGER) as neto_venta"))
            ->where([[DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), '<=', $fechaFin]])
            ->groupBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion)"))
            ->get();
        $chartValorNetoVenta = \Chart::title(['text' => 'REPORTE VALOR NETO VENTA',])
            ->chart(['type' => 'line', 'renderTo' => 'chartValorNetoVenta'])
            ->subtitle(['text' => '(Expresado en BOB)<br>Del ' . date('m/Y', strtotime($fechaInicio)) . ' al ' . date('m/Y', strtotime($fechaFin))])
            ->colors(['#0c2959'])
            ->xaxis([
                'categories' => $valorNetoVenta->pluck('fecha'),
                'labels' => ['rotation' => 15, 'align' => 'top',],
                'title' => ['text' => 'Meses']
            ])
            ->yaxis(['title' => ['text' => 'Valor Neto Venta']])
            ->legend(['layout' => 'vertikal', 'align' => 'right', 'verticalAlign' => 'middle'])
            ->series([['name' => 'BOB', 'data' => $valorNetoVenta->pluck('neto_venta')]])
            ->display();

        return $chartValorNetoVenta;
    }

    private function estadisticaValorNetoVentaVenta($fechaInicio, $fechaFin)
    {
        $valorNetoVenta = DB::table('venta')
            ->select(DB::raw("SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7) as fecha,
	            CAST(fn_neto_venta(SUBSTRING (CAST(DATE_TRUNC('month',venta.fecha_venta)as varchar) ,1, 7), 'venta') AS INTEGER) as neto_venta"))
            ->where([[DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '>=', $fechaInicio],
                [DB::raw("to_char(venta.fecha_venta, 'YYYY-MM')"), '<=', $fechaFin],
                ["tipo_lote", '=', TipoLoteVenta::Venta]])
            ->groupBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->orderBy(DB::raw("DATE_TRUNC('month',venta.fecha_venta)"))
            ->get();
        $chartValorNetoVenta = \Chart::title(['text' => 'REPORTE VALOR NETO VENTA',])
            ->chart(['type' => 'line', 'renderTo' => 'chartValorNetoVentaVenta'])
            ->subtitle(['text' => '(Expresado en BOB)<br>Del ' . date('m/Y', strtotime($fechaInicio)) . ' al ' . date('m/Y', strtotime($fechaFin))])
            ->colors(['#0c2959'])
            ->xaxis([
                'categories' => $valorNetoVenta->pluck('fecha'),
                'labels' => ['rotation' => 15, 'align' => 'top',],
                'title' => ['text' => 'Meses']
            ])
            ->yaxis(['title' => ['text' => 'Valor Neto Venta']])
            ->legend(['layout' => 'vertikal', 'align' => 'right', 'verticalAlign' => 'middle'])
            ->series([['name' => 'BOB', 'data' => $valorNetoVenta->pluck('neto_venta')]])
            ->display();

        return $chartValorNetoVenta;
    }


    private function estadisticaComprasMes($mes)
    {
        if (!$mes)
            $mes = date('Y-m');
        $comprasPorMes = DB::table('cooperativa')
            ->join('cliente', 'cooperativa.id', '=', 'cliente.cooperativa_id')
            ->join('formulario_liquidacion', 'cliente.id', '=', 'formulario_liquidacion.cliente_id')
            ->select(DB::raw("cooperativa.razon_social as cooperativa,
                        fn_compras_por_mes(to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM'), cliente.cooperativa_id) as cantidad"))
            ->where(DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"), $mes)
            ->whereIn('estado', [Estado::Liquidado, Estado::Vendido, Estado::Composito])
            ->groupBy('cooperativa.razon_social', 'cliente.cooperativa_id', DB::raw("to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')"))
            ->get();

        $dataPoints = [];

        foreach ($comprasPorMes as $compra) {
            $dataPoints[] = [
                "name" => $compra->cooperativa,
                "y" => floatval($compra->cantidad)
            ];
        }
//        dd($dataPoints);

        $chartComprasMes = true;
        return $chartComprasMes;
    }

    private function estadisticaSatisfaccionClientes()
    {

        $bueno = SatisfaccionCliente::whereDescripcion(TipoSatisfaccionCliente::Bueno)->count();
        $muyBueno = SatisfaccionCliente::whereDescripcion(TipoSatisfaccionCliente::MuyBueno)->count();
        $malo = SatisfaccionCliente::whereDescripcion(TipoSatisfaccionCliente::Malo)->count();
        $muyMalo = SatisfaccionCliente::whereDescripcion(TipoSatisfaccionCliente::MuyMalo)->count();


        $dataPoints = [
            ["name" => TipoSatisfaccionCliente::MuyBueno, "y" => floatval($muyBueno)],
            ["name" => TipoSatisfaccionCliente::Bueno, "y" => floatval($bueno)],
            ["name" => TipoSatisfaccionCliente::Malo, "y" => floatval($malo)],
            ["name" => TipoSatisfaccionCliente::MuyMalo, "y" => floatval($muyMalo)],
        ];
        $chartSatisfaccionClientes = true;
        return $chartSatisfaccionClientes;
    }

    private function registrarCambio()
    {
        $fecha = date('Y-m-d');

        $contador = TipoCambio::whereFecha($fecha)->count();
        if ($contador == 0) {
            $input['dolar_compra'] = 6.86;
            $input['dolar_venta'] = 6.96;
            $input['fecha'] = $fecha;
            $input['api'] = false;
            TipoCambio::create($input);

        }
        $contador = CotizacionDiaria::whereFecha($fecha)->count();
        if ($contador == 0 and date('w')==1) {
            $ultimo = CotizacionDiaria::orderByDesc('fecha')->first();
            $fechaUltima= $ultimo->fecha;
            $ultimos = CotizacionDiaria::whereFecha($fechaUltima)->get();
            foreach ($ultimos as $u){
                $valor['fecha'] = $fecha;
                $valor['monto'] = $u->monto;
                $valor['unidad'] = $u->unidad;
                $valor['mineral_id'] = $u->mineral_id;
                CotizacionDiaria::create($valor);
            }
        }
    }


}
