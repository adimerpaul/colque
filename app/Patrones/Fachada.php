<?php


namespace App\Patrones;

use App\Http\Controllers\rrhh\PlanillaController;
use App\Models\Activo\Tipo;
use App\Models\Activo\DetalleActivo;
use App\Models\Bono;
use App\Models\CambioFormulario;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\DescuentoCatalogo;
use App\Models\Empresa;
use App\Models\FormularioLiquidacion;
use App\Models\Material;
use App\Models\Movimiento;
use App\Models\MovimientoCatalogo;
use App\Models\Municipio;
use App\Models\PagoRetencion;
use App\Models\ParametricaImpuestos;
use App\Models\Prestamo;
use App\Models\Producto;
use App\Models\Personal;
use App\Models\Token;
use App\Models\Venta;
use Carbon\Carbon;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\Rrhh\TipoHorario;
use App\Models\Rrhh\TipoHorarioPersonal;
use App\Models\Rrhh\Permiso;
use App\Models\Rrhh\TipoPermiso;
use App\Models\Rrhh\TipoPermisoPersonal;
use App\Models\TipoCambio;
use App\Models\TipoReporte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class Fachada
{
    public static $APP_NAME = 'Impuestoselasis';
    public static $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    public static function getToken()
    {
        $token = Token::orderByDesc('id')->first();
        return $token->token;

    }
    public static function tieneCotizacion()
    {
        $fecha = Fachada::getFecha();
        return self::tieneTipoCambio($fecha) && self::tieneCotizacionDiaria($fecha) && self::tieneCotizacionOficial($fecha);
    }

    private static function tieneTipoCambio($fecha)
    {
        return TipoCambio::whereFecha($fecha)->count() > 0;
    }

    public static function tieneCotizacionDiaria($fecha)
    {
        $cantidadMinerales = Material::whereConCotizacion(true)->get()->count();
        $cantidadCotizacionDiaria = CotizacionDiaria::whereFecha($fecha)->count();
        return $cantidadMinerales === $cantidadCotizacionDiaria;
    }

    public static function getFechaLiteral($fecha)
    {
        $dia = date('d', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $mes = Fachada::getMesEspanol(date('n', strtotime($fecha)));
        return sprintf("%s de %s de %s", $dia, $mes, $anio);
    }

    public static function tieneCotizacionOficial($fecha)
    {
        $res = self::getFechaCotizacionOficial($fecha);

        return !is_null($res);
    }

    public static function cambioPass()
    {
        $cambioPass = false;
        $cambio = auth()->user()->ultimo_cambio_password;
        if (!is_null($cambio))
            $cambioPass = true;
        return $cambioPass;
    }

    public static function getFechaCotizacionOficial($fechaParametro)
    {
        $fechaInicio = null;
        $cotizacion = CotizacionOficial::orderByDesc('fecha')->whereEsAprobado(true)->where('fecha', '<=', $fechaParametro)->first();
        if (!is_null($cotizacion)) {
            $fechaInicio = $cotizacion->fecha_inicio;
        }

        $fechaInicio = Fachada::setFormatoFecha($fechaInicio);
        try {
            $res = Fachada::setFormatoFecha(self::getFechaInferior(date('Y-m-d', strtotime($fechaParametro))));
        } catch (\Exception $e) {
            $res = Fachada::setFormatoFecha(self::getFechaInferior($fechaParametro->format('Y-m-d')));
        }

        if ($res !== $fechaInicio)
            return null;

        if (CotizacionOficial::where('fecha', $cotizacion->fecha)->whereEsAprobado(true)->count() < 4)
            return null;

        return Fachada::setDateFormat($cotizacion->fecha);
    }

    public static function getFechaInferior($fecha)
    {
        $fechaFormato = new \DateTime($fecha);

        if ($fechaFormato->format('d') > 15)
            return (date("16/m/Y", strtotime($fecha)));
        else
            return (date("01/m/Y", strtotime($fecha)));
    }

    public static function setDateFormat($fecha)
    {
        $fecha = $fecha . ' 00:00:00';
        return \DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
    }

    public static function setFormatoFecha($fecha)
    {
        return (date('Y-m-d', strtotime(str_replace('/', '-', $fecha))));
    }

    public static function getTipoCambio()
    {
        return TipoCambio::whereFecha(Fachada::getFecha())->first();
    }

    public static function getRoles()
    {
        if (\App\Patrones\Permiso::esSuperAdmin())
            return [null => "Seleccione..."] +
                [
                    //Rol::SuperAdmin => "Super administrador de la plataforma",
                    Rol::Administrador => "Administrador del sistema",
                    Rol::Pesaje => "Responsaje de pesaje",
                    Rol::Contabilidad => "Responsable de Contabilidad",
                    Rol::Comercial => "Responsable Comercial",
                    Rol::Caja => "Responsable de Caja",
                    Rol::Operaciones => "Operaciones",
                    Rol::Invitado => "Invitado"
                ];
        else
            return [null => "Seleccione..."] +
                [
                    Rol::Administrador => Rol::Administrador,
                    Rol::Pesaje => Rol::Pesaje,
                    Rol::Liquidacion => Rol::Liquidacion,
                ];
    }

    public static function getEstados()
    {
        return [
            Estado::EnProceso => Estado::EnProceso,
            Estado::Liquidado => Estado::Liquidado,
            Estado::Anulado => Estado::Anulado,
            Estado::Composito => Estado::Composito,
            Estado::Vendido => Estado::Vendido,
        ];
    }

    public static function getEstadosKardex()
    {
        return [
            Estado::EnProceso => Estado::EnProceso,
            Estado::Liquidado => Estado::Liquidado . ' (En Stock)',
            Estado::Anulado => Estado::Anulado,
            Estado::Composito => Estado::Composito,
            Estado::Vendido => Estado::Vendido,
        ];
    }

    public static function getEstadosAVender()
    {
        return [
            Estado::Liquidado => Estado::Liquidado,
            Estado::EnProceso => Estado::EnProceso,
            '%' => 'Todos'
        ];
    }

    public static function getEstadosCaja()
    {
        return [
            false => Estado::Liquidado,
            true => 'Cancelado',
        ];
    }

    public static function getEstadosCajaAnticipos()
    {
        return [
            false => 'No Cancelado',
            true => 'Cancelado',
        ];
    }

    public static function getEstadoMolienda()
    {
        return [
            false => 'No',
            true => 'Si',
        ];
    }

    public static function getUbicaciones()
    {
        return [
            'Oficina Principal' => 'Oficina Principal',
            'Patio Laboratorio' => 'Patio Laboratorio',
            'Depósito Altamira' => 'Depósito Altamira',
            'Depósito Penfold' => 'Depósito Penfold',
            'Deposito Impala' => 'Deposito Impala',
            'Planta San Clemente' => 'Planta San Clemente',
            'Planta EMSA' => 'Planta EMSA',
            'Entreoceanos' => 'Entreoceanos',
            'Otros' => 'Otros',
        ];
    }

    public static function getClasesCuentas()
    {
        return [
            ClaseCuentaCobrar::Prestamo => ClaseCuentaCobrar::Prestamo,
            ClaseCuentaCobrar::SaldoNegativo => ClaseCuentaCobrar::SaldoNegativo,
            ClaseCuentaCobrar::Retiro => ClaseCuentaCobrar::Retiro,
        ];
    }

    public static function getClasesCuentasSinRetiro()
    {
        return [
            ClaseCuentaCobrar::Prestamo => ClaseCuentaCobrar::Prestamo,
            ClaseCuentaCobrar::SaldoNegativo => ClaseCuentaCobrar::SaldoNegativo,
        ];
    }

    public static function getEstadosPrestamos()
    {
        return [
            false => 'No devuelto',
            true => 'Devuelto',
        ];
    }

    public static function getTiposMovimientos()
    {
        return [
            'Egreso' => 'Egreso',
            'Ingreso' => 'Ingreso',
            TipoPago::CuentaBancaria => TipoPago::CuentaBancaria,
            TipoPago::Efectivo => TipoPago::Efectivo
        ];
    }

    public static function listarTiposMovimientos()
    {
        return [
            'Egreso' => 'Egreso',
            'Ingreso' => 'Ingreso'
        ];
    }

    public static function getDepartamentos()
    {
        return [null => "Seleccione..."] +
            [
                'ORU' => 'Oruro',
                'LPZ' => 'La Paz',
                'SCZ' => 'Santa Cruz',
                'PTS' => 'Potosi',
                'CBB' => 'Cochabamba',
                'TJR' => 'Tarija',
                'PND' => 'Pando',
                'BEN' => 'Beni',
                'SUC' => 'Sucre',
            ];
    }

    public static function unidadesLeyes()
    {
        return array(
            UnidadLey::Porcentaje => UnidadLey::Porcentaje,
            UnidadLey::Decimarco => UnidadLey::Decimarco,
            UnidadLey::GramosPorTonelada => UnidadLey::GramosPorTonelada,
            UnidadLey::PartesPorMillon => UnidadLey::PartesPorMillon

        );
    }

    public static function tiposPagos()
    {
        return [null => "Seleccione..."] +
            [
                TipoDescuentoBonificacion::Bonificacion => 'BONIFICACIÓN',
                TipoDescuentoBonificacion::Descuento => 'DEDUCCIÓN INSTITUCIONAL',
                TipoDescuentoBonificacion::Retencion => 'RETENCIÓN DE LEY'
            ];
    }

    public static function getDescuentoBonificacion()
    {
        return
            [
                TipoDescuentoBonificacion::Bonificacion => TipoDescuentoBonificacion::Bonificacion,
                TipoDescuentoBonificacion::Descuento => TipoDescuentoBonificacion::Descuento,
                TipoDescuentoBonificacion::Retencion => TipoDescuentoBonificacion::Retencion
            ];
    }

    public static function unidadesPagos()
    {
        return array(
            UnidadDescuentoBonificacion::Porcentaje => UnidadDescuentoBonificacion::Porcentaje,
            UnidadDescuentoBonificacion::Constante => UnidadDescuentoBonificacion::Constante,
            UnidadDescuentoBonificacion::DolarPorTonelada => UnidadDescuentoBonificacion::DolarPorTonelada,
            UnidadDescuentoBonificacion::Cantidad => UnidadDescuentoBonificacion::Cantidad,
        );
    }

    public static function estadosAltas()
    {
        return array(
            true => 'Alta',
            false => 'Baja'
        );
    }

    public static function enFunciones()
    {
        return [null => 'Seleccione...'] +
            [
                //EnFuncion::PesoBrutoHumedo => EnFuncion::PesoBrutoHumedo,
                EnFuncion::PesoNetoSeco => EnFuncion::PesoNetoSeco,
                EnFuncion::ValorNetoVenta => EnFuncion::ValorNetoVenta,
                EnFuncion::ValorBrutoVenta => EnFuncion::ValorBrutoVenta,
                EnFuncion::Sacos => EnFuncion::Sacos,
                EnFuncion::Total => EnFuncion::Total
            ];
    }

    public static function unidadesCotizacion()
    {
        return [
            UnidadCotizacion::LF => UnidadCotizacion::LF,
            UnidadCotizacion::OT => UnidadCotizacion::OT,
            UnidadCotizacion::TM => UnidadCotizacion::TM,
        ];
    }

    public static function listarMinerales()
    {
        $minerales = Material::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray();
        return [null => 'Seleccione...'] + $minerales;
    }

    public static function getFecha()
    {
        $soloFecha = date('Y-m-d');
        return \DateTime::createFromFormat('Y-m-d', $soloFecha);
    }

    public static function getFechaHora()
    {
        $soloFecha = date('d/m/Y');
        $hora = date('H:i:s');
        $fecha = $soloFecha . ' ' . $hora;
        return \DateTime::createFromFormat('d/m/Y H:i:s', $fecha);
    }

    public static function setDateTime($soloFecha)
    {
        $hora = date('H:i:s');
        $fecha = $soloFecha . ' ' . $hora;
        return Carbon::createFromFormat('d/m/Y H:i:s', $soloFecha)->format("Y-m-d H:i:s");
    }

    public static function setDate($fecha)
    {
        return Carbon::createFromFormat('d/m/Y', $fecha)->format("Y-m-d");
    }

    public static function estado($estado)
    {
        $color = '';
        if ($estado === Estado::EnProceso) $color = 'default';
        if ($estado === Estado::Anulado) $color = 'danger';
        if ($estado === Estado::Liquidado) $color = 'primary';
        if ($estado === Estado::Composito) $color = 'warning';
        if ($estado === Estado::Vendido) $color = 'success';

        return "<span class='label label-" . $color . "'>{$estado}</span>";
    }
    public static function estadoAsistencia($item,$cantAsitencia)
    {
        $color = '';
        $nombre ='';
        if($item == 'falta') {$color='danger';$nombre='FALTA';}
        elseif($item == 'asistido'){if($cantAsitencia == 2) {$color='success';$nombre='ASISTENCIA';}else{$color='default';$nombre='ASISTENCIA';}}
        elseif($item == 'permiso'){$color='info';$nombre='PERMISO';}
        elseif($item == 'feriado'){
            return "<span class='label' style='background-color: #518CB7;'>FERIADO</span>";}
        elseif($item == 'horaExtra'){$color='warning';$nombre='HORA EXTRA';}
        return "<span class='label label-" . $color . "'>{$nombre}</span>";
    }




    public static function estadoLaboratorio($estado)
    {
        $color = '';
        if ($estado === EstadoLaboratorio::EnProceso) $color = 'default';
        if ($estado === EstadoLaboratorio::Anulado) $color = 'danger';
        if ($estado === EstadoLaboratorio::Recepcionado) $color = 'primary';
        if ($estado === EstadoLaboratorio::Finalizado) $color = 'success';

        return "<span class='label label-" . $color . "'>{$estado}</span>";
    }

    public static function esCancelado($esCancelado)
    {

        if (!$esCancelado)
            return "<span class='label label-danger'>No Cancelado</span>";
        else
            return "<span class='label label-success'>Cancelado</span>";

    }

    public static function enviadoOperaciones($esEnviado)
    {
        if (!$esEnviado)
            return "<span class='label label-danger'>Sin enviar a Op.</span>";
    }
    public static function verificadoDespacho($esDespachado)
    {
        if ($esDespachado)
            return "<span class='label label-success'>Despacho verificado</span>";
    }

    public static function documentoSubido($falta)
    {
        $color = 'success';
        $estado = 'Docs. Completos';
        if ($falta) {
            $color = 'danger';
            $estado = 'Faltan Docs.';
        }
        return "<span class='label label-" . $color . "'>{$estado}</span>";
    }

    public static function moliendo($molienda)
    {
        $color = 'success';
        $estado = 'Listo';
        if ($molienda) {
            $color = 'danger';
            $estado = 'En molienda.';
        }
        return "<span class='label label-" . $color . "'>{$estado}</span>";
    }

    public static function letras()
    {
        $letras = [];
        for ($i = 65; $i <= 90; $i++) {
            $letras += [chr($i) => chr($i)];
        }
        return $letras;
    }

    public static function tiposTablas()
    {

        return [null => "Seleccione..."] +
            [
                Tabla::Merma => Tabla::Merma
            ];
    }

    public static function getCamposReporte()
    {
        return [
            0 => 'PESO BRUTO HUMEDO (Kg)',
            1 => 'TARA (Kg)',
            2 => 'PESO NETO HUMEDO (Kg)',
            3 => 'Humedad (%)',
            4 => 'Humedad (Kg)',
            5 => 'MERMA (Kg)',
            6 => 'PESO NETO SECO (Kg)',
            7 => 'VALOR POR TONELADA USD',
            8 => 'VALOR NETO VENTA',
            9 => 'REGALIA MINERA',
            10 => 'TOTAL RETENCIONES Y DESCUENTOS',
            11 => 'TOTAL BONIFICACIONES',
            12 => 'TOTAL BONIFICACIONES ACUMULATIVAS',
            13 => 'ANTICIPO/ENTREGA',
            14 => 'SALDO POR DEUDA/PRÉSTAMO',
            15 => 'LIQUIDO PAGABLE',
            16 => 'APORTE FUNDACION',
            17 => 'SALDO A FAVOR',
            18 => 'COSTO TRATAMIENTO',
            19 => 'COSTO LABORATORIO',
            20 => 'COSTO PESAJE',
            21 => 'COSTO DE COMISIONES',
            22 => 'ESTADO',
            23 => 'PB',
            24 => 'AG',
            25 => 'ZN',
            26 => 'SN',
            27 => 'AU',
            28 => 'SB',
            29 => 'RETENCIONES DE LEY',
            30 => 'DESCUENTOS INSTITUCIONALES',
            31 => 'BONIFICACIONES',
            32 => 'COMPROBANTE LIQUIDACIÓN',
            33 => 'COMPROBANTES ANTICIPOS',
        ];
    }

    public static function getCodigosCamposReporte()
    {
        return [
            0 => 'pesoBrutoHumedo',
            1 => 'tara',
            2 => 'pesoNetoHumedo',
            3 => 'humedadPorcentaje',
            4 => 'humedadKg',
            5 => 'merma',
            6 => 'pesoNetoSeco',
            7 => 'valorPorTonelada',
            8 => 'valorNetoVenta',
            9 => 'regaliaMinera',
            10 => 'totalRetencionesDescuento',
            11 => 'totalBonificaciones',
            12 => 'totalBonificacionesAcumulativas',
            13 => 'anticipos',
            14 => 'cuentasCobrar',
            15 => 'liquidoPagable',
            16 => 'aporteFundacion',
            17 => 'saldoFavor',
            18 => 'tratamiento',
            19 => 'laboratorio',
            20 => 'pesaje',
            21 => 'comision',
            22 => 'estado',
            23 => 'pb',
            24 => 'ag',
            25 => 'zn',
            26 => 'sn',
            27 => 'au',
            28 => 'sb',
            29 => 'retencionesDeLey',
            30 => 'descuentosInstitucionales',
            31 => 'bonificaciones',
            32 => 'comprobanteLiquidacion',
            33 => 'comprobantesAnticipos',
        ];
    }

    public static function listarTiposReportes()
    {
        return TipoReporte::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray();
    }

    public static function listarLotesVentas($letra)
    {
        switch ($letra) {
            case 'A':
                $ventas = Venta::whereIn('letra', ['A', 'C'])->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;

            case 'B':
                $ventas = Venta::whereIn('letra', ['B', 'C'])->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;

            case 'E':
                $ventas = Venta::whereIn('letra', ['A', 'B', 'C', 'E'])->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;
            default:
                $ventas = Venta::whereLetra($letra)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;
        }
//        $ventas = Venta::whereLetra($letra)->whereEstado(EstadoVenta::EnProceso)->orderBy('id')->get()->pluck('lote', 'id')->toArray();
        return [null => 'Seleccione...'] + $ventas;
    }

    public static function listarLotesVentasSinIngenio($letra)
    {
        switch ($letra) {
            case 'A':
                $ventas = Venta::whereIn('letra', ['A', 'C'])->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;

            case 'B':
                $ventas = Venta::whereIn('letra', ['B', 'C'])->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;

            case 'C':
                $ventas = Venta::whereIn('letra', ['A', 'B', 'C'])->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;

            case 'E':
                $ventas = Venta::whereIn('letra', ['A', 'B', 'C', 'E'])->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;
            case 'F':
                $ventas = Venta::whereIn('letra', [ 'B', 'F'])->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;
            default:
                $ventas = Venta::whereLetra($letra)->whereTipoLote(TipoLoteVenta::Venta)->whereEstado(EstadoVenta::EnProceso)->orderBy('numero_lote')->orderBy('letra')->get()->pluck('lote', 'id')->toArray();
                break;
        }
        return [null => 'Seleccione...'] + $ventas;
    }

    public static function listarLotesActivos()
    {
        $lotes = FormularioLiquidacion::whereEstado(Estado::EnProceso)->orderBy('numero_lote')->get()->pluck('lote', 'id')->toArray();
        return [null => 'Seleccione...'] + $lotes;
    }

    public static function listarLotesParaLab()
    {
        return FormularioLiquidacion::where('estado', Estado::EnProceso)
            ->orWhere(function ($q) {
                $q->where('estado', Estado::Liquidado)
                    ->where('fecha_liquidacion', '>=', DB::raw("NOW() - INTERVAL '15 DAYS'"));
            })
            ->orderByDesc('id')->get()->pluck('lote', 'id')->toArray();
    }

    public static function listarLotesLabColquechaca($elementoId)
    {
        if ($elementoId==1) {
            $data=\DB::select("
                (
                    SELECT id, CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) AS lote, 'Compra' as tipo
                    FROM formulario_liquidacion
                    WHERE estado ='En proceso' AND letra='D'
                )
                UNION
                (
                    SELECT concentrado.id, concentrado.nombre as lote, 'Venta' as tipo
                    FROM concentrado INNER JOIN venta ON concentrado.venta_id = venta.id
                    WHERE venta.estado='En proceso' and venta.tipo_lote='Ingenio' and concentrado.tipo_lote='Venta' and concentrado.nombre is not null AND venta.letra='D'
                )
                ORDER BY tipo, lote
            ");
        }
        elseif ($elementoId==3) {
            $data=\DB::select("
                (
                    SELECT id, CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) AS lote, 'Compra' as tipo
                    FROM formulario_liquidacion
                    WHERE estado ='En proceso' AND letra IN ('A', 'B', 'C', 'E')
                )
                UNION
                (
                    SELECT concentrado.id, concentrado.nombre as lote, 'Venta' as tipo
                    FROM concentrado INNER JOIN venta ON concentrado.venta_id = venta.id
                    WHERE venta.estado='En proceso' and venta.tipo_lote='Ingenio' and concentrado.tipo_lote='Venta'
                    and concentrado.nombre is not null AND venta.letra IN ('A', 'B', 'C', 'E')
                )
                ORDER BY tipo, lote
            ");
        }
        else {
            $data=\DB::select("
                (
                    SELECT id, CONCAT(sigla,numero_lote,letra,'/', substring(cast(anio as varchar),3,4)) AS lote, 'Compra' as tipo
                    FROM formulario_liquidacion
                    WHERE estado ='En proceso'
                )
                UNION
                (
                    SELECT concentrado.id, concentrado.nombre as lote, 'Venta' as tipo
                    FROM concentrado INNER JOIN venta ON concentrado.venta_id = venta.id
                    WHERE venta.estado='En proceso' and venta.tipo_lote='Ingenio' and concentrado.tipo_lote='Venta' and concentrado.nombre is not null
                )
                ORDER BY tipo, lote
            ");
        }
        return $data;
    }

    public static function listarLotesParaLabVenta()
    {
        return Venta::where('estado', EstadoVenta::EnProceso)
            ->orWhere(function ($q) {
                $q->where('estado', EstadoVenta::Liquidado)
                    ->where('fecha_venta', '>=', DB::raw("NOW() - INTERVAL '30 DAYS'"));
            })
            ->orderByDesc('id')->get()->pluck('lote', 'id')->toArray();
    }

    public static function listarLotesActivosParaDevoluciones()
    {
        $lotes = FormularioLiquidacion::whereEstado(Estado::EnProceso)->orderBy('numero_lote')->get()->pluck('lote', 'id')->toArray();
        return [null => 'Enviar a Caja'] + $lotes;
    }

    public static function listarTiposPagos()
    {
        return [
            TipoPago::Efectivo => TipoPago::Efectivo,
            TipoPago::CuentaBancaria => TipoPago::CuentaBancaria
        ];

    }
    public static function listarTiposPagosConDolar()
    {
        return [
            TipoPago::Efectivo => TipoPago::Efectivo,
            TipoPago::CuentaBancaria => TipoPago::CuentaBancaria,
            TipoPago::CuentaBancariaDolares => TipoPago::CuentaBancariaDolares
        ];

    }

    public static function listarBancos()
    {
        return [
            Banco::BNB => 'Banco Nacional de Bolivia',
            Banco::Economico => 'Banco Economico',
        ];

    }


    public static function listarTiposLotes()
    {
        return [
            'Compra' => 'Compra',
            'Venta' => 'Venta',
        ];

    }

    public static function listarOtrosCostos()
    {
        return [
            'Pago regalia minera' => 'Pago regalia minera',
            'Transporte exportación' => 'Transporte exportación',
            'Transporte interno' => 'Transporte interno',
            'Servicio de análisis químico' => 'Servicio de análisis químico',
            'Servicio de verificación' => 'Servicio de verificación',
            'Servicio de retroexcavadora' => 'Servicio de retroexcavadora',
            'Pago servicio Toll' => 'Pago servicio Toll',
            'Otros' => 'Otros',
        ];

    }

    public static function listarCambiosFormularios($accion)
    {
//        if($accion==AccionCambioFormulario::Restablecimiento)
        $lotes = CambioFormulario::whereRevisado(false)->whereAccion($accion)->orderBy('id')->get();
//        else
//            $lotes = CambioFormulario::whereRevisado(false)->whereIn('accion', [AccionCambioFormulario::Pesaje, AccionCambioFormulario::NuevoLote])->orderBy('id')->get();

        return $lotes;
    }

    public static function listarPrestamosNoAprobados()
    {
        return Prestamo::whereAprobado(false)->where('created_at', '>=', DB::raw("NOW() - INTERVAL '168 HOURS'"))->orderBy('id')->get();
    }

    public static function listarVentasNoAprobadas()
    {
        return Venta::whereEsAprobado(false)->whereEstado(EstadoVenta::Liquidado)->orderBy('updated_at')->get();
    }

    public static function listarRetencionesNoAprobadas()
    {
        return PagoRetencion::whereEsAprobado(false)->orderBy('updated_at')->get();
    }

    public static function listarClientesNoAprobados()
    {
        return
            \DB::select("
            (
                SELECT id, nombre, 'CLIENTE' as tipo, 'clientes.edit' as ruta
                FROM cliente WHERE es_aprobado=false
            )
            UNION
            (
                SELECT	id, nombre, 'PROVEEDOR'	as tipo, 'proveedores.edit' as ruta
                FROM proveedor WHERE es_aprobado=false
            )
            UNION
            (
                SELECT	id, razon_social, 'COMPRADOR' as tipo, 'compradores.edit' as ruta
                FROM comprador WHERE es_aprobado=false
            )
            UNION
            (
                SELECT	id, razon_social, 'COOPERATIVA'	as tipo, 'cooperativas.edit' as ruta
                FROM cooperativa WHERE es_aprobado=false
            )"
            );
    }

    public static function contarClientesNoAprobados()
    {
        return
            \DB::select("
                SELECT(
            (SELECT count(*) FROM cliente WHERE es_aprobado=false) +
            (SELECT count(*) FROM proveedor WHERE es_aprobado=false) +
            (SELECT count(*) FROM comprador WHERE es_aprobado=false) +
            (SELECT count(*) FROM cooperativa WHERE es_aprobado=false)) as contador
            "
            );
    }

    public static function listarMovimientosNoAprobados()
    {
        return Movimiento::whereEsAprobado(false)->where('created_at', '>=', DB::raw("NOW() - INTERVAL '168 HOURS'"))->orderBy('id')->get();
    }

    public static function getCatalogosMovimientos()
    {
        $catalogos = MovimientoCatalogo::orderBy('descripcion')->get()->pluck('descripcion', 'descripcion')->toArray();
        return ['%' => 'TODO...'] + $catalogos;
    }

    public static function listarCLientes()
    {
        $clientes = Cliente::
        whereEsAprobado(true)
            /*whereHas('cooperativa', function ($q)  {
                $q->where('fecha_expiracion', '>=', date('Y-m-d'));
            })->*/
            ->get()->pluck('info', 'id')->toArray();
        //::whereLetra($letra)->whereEstado(EstadoVenta::EnProceso)->orderBy('id')->get()->pluck('lote', 'id')->toArray();
        return [null => 'Seleccione...'] + $clientes;
    }


    public static function getEstadosVentas()
    {
        return [
            EstadoVenta::EnProceso => EstadoVenta::EnProceso,
            EstadoVenta::Liquidado => EstadoVenta::Liquidado,
            EstadoVenta::Anulado => EstadoVenta::Anulado,
        ];
    }

    public static function getEmpaques()
    {
        return [
            Empaque::AGranel => Empaque::AGranel,
            Empaque::Ensacado => Empaque::Ensacado,
        ];
    }

    public static function getDiferenciaMontoVenta()
    {
        return [
            'Positiva' => 'Positiva',
            'Negativa' => 'Negativa',
        ];
    }

    public static function getDocumentosVentas($letra, $sigla)
    {
        if($letra=='A' or $letra=='B'){
            return [
                DocumentoVenta::ListaEmpaque => DocumentoVenta::ListaEmpaque,
                DocumentoVenta::FacturaExportacion => DocumentoVenta::FacturaExportacion,
                DocumentoVenta::InformeEnsayo => DocumentoVenta::InformeEnsayo,
                DocumentoVenta::DeclaracionExportacion => DocumentoVenta::DeclaracionExportacion,
                DocumentoVenta::FormularioRegaliaMinera => DocumentoVenta::FormularioRegaliaMinera,
                DocumentoVenta::Consolidado => DocumentoVenta::Consolidado,
                DocumentoVenta::PesajesMasFormularios101 => DocumentoVenta::PesajesMasFormularios101,
                DocumentoVenta::Formularios3007PagoRegaliaMineria => DocumentoVenta::Formularios3007PagoRegaliaMineria,
                DocumentoVenta::FormularioM03 => DocumentoVenta::FormularioM03,
                DocumentoVenta::Liquidacion => DocumentoVenta::Liquidacion,
                DocumentoVenta::Otros => DocumentoVenta::Otros,
            ];
        }
        elseif($sigla=='CMI'){
            return [
                DocumentoVenta::Laboratorio => DocumentoVenta::Laboratorio,
                DocumentoVenta::Pesaje => DocumentoVenta::Pesaje,
                DocumentoVenta::Otros => DocumentoVenta::Otros,
            ];
        }
        else{
            return [
                DocumentoVenta::Laboratorio => DocumentoVenta::Laboratorio,
                DocumentoVenta::Pesaje => DocumentoVenta::Pesaje,
                DocumentoVenta::Liquidacion => DocumentoVenta::Liquidacion,
                DocumentoVenta::Otros => DocumentoVenta::Otros,
            ];
        }
    }

    public static function getTiposDocumentosVentas($letra, $sigla)
    {
        if($letra=='A' or $letra=='B'){
            return [
                0 => DocumentoVenta::Liquidacion,
                1 => DocumentoVenta::Otros,
                2 => DocumentoVenta::FormularioM03,
                3 => DocumentoVenta::ListaEmpaque,
                4 => DocumentoVenta::FacturaExportacion,
                5 => DocumentoVenta::InformeEnsayo,
                6 => DocumentoVenta::DeclaracionExportacion,
                7 => DocumentoVenta::FormularioRegaliaMinera,
                8 => DocumentoVenta::Consolidado,
                9 => DocumentoVenta::Formularios3007PagoRegaliaMineria,
                10 => DocumentoVenta::PesajesMasFormularios101,
            ];
        }
        elseif($sigla=='CMI'){
            return [
                0 => DocumentoVenta::Laboratorio,
                1 => DocumentoVenta::Pesaje,
                2 => DocumentoVenta::Otros,
            ];
        }
        else{
            return [
                0 => DocumentoVenta::Laboratorio,
                1 => DocumentoVenta::Pesaje,
                2 => DocumentoVenta::Liquidacion,
                3 => DocumentoVenta::Otros,
            ];
        }
    }

    public static function getDocumentosCompras()
    {
        return [
            DocumentoCompra::BoletaPesaje => DocumentoCompra::BoletaPesaje,
            DocumentoCompra::LaboratorioEmpresa => DocumentoCompra::LaboratorioEmpresa,
            DocumentoCompra::LaboratorioCliente => DocumentoCompra::LaboratorioCliente,
            DocumentoCompra::Anticipos => DocumentoCompra::Anticipos,
            DocumentoCompra::Formulario => DocumentoCompra::Formulario,
        ];
    }

    public static function getTiposDocumentos()
    {
        return [
            0 => DocumentoVenta::Laboratorio,
            1 => DocumentoVenta::Pesaje,
            2 => DocumentoVenta::Liquidacion,
            3 => DocumentoVenta::Otros,
        ];
    }

    public static function getTiposDocumentosCompras()
    {
        return [
            0 => DocumentoCompra::BoletaPesaje,
            1 => DocumentoCompra::Formulario,
            2 => DocumentoCompra::LaboratorioCliente,
            3 => DocumentoCompra::LaboratorioEmpresa,
            4 => DocumentoCompra::Anticipos,
        ];
    }

    public static function getTiposContratos()
    {
        return [null => "Seleccione..."] +
            [
                TipoContrato::Administrativo => TipoContrato::Administrativo,
                TipoContrato::Cooperativo => TipoContrato::Cooperativo,
            ];
    }

    public static function getDocumentosCooperativas()
    {
        return [
            DocumentoCooperativa::NIM => DocumentoCooperativa::NIM,
            DocumentoCooperativa::NIT => DocumentoCooperativa::NIT,
            DocumentoCooperativa::Contrato => DocumentoCooperativa::Contrato,
        ];
    }

    public static function getTransferenciasCuentas()
    {
        return [
            'Lote' => 'Lote',
            'Cliente' => 'Cliente',
        ];
    }

    public static function getTiposDocumentosCooperativas()
    {
        return [
            0 => DocumentoCooperativa::NIM,
            1 => DocumentoCooperativa::NIT,
            2 => DocumentoCooperativa::Contrato,
        ];
    }

    public static function getColorTipoDocumento($tipo, $estado, $fecha)
    {
        if ($estado) $color = 'success';
        else $color = 'danger';

        if ($tipo === DocumentoCooperativa::NIM and $fecha < date('Y-m-d')) $color = 'danger';
        elseif ($tipo === DocumentoCooperativa::NIM and $estado) $color = 'success';

        return "<span class='label label-" . $color . "'>{$tipo}</span>";
    }

    public static function getFechasAnio()
    {
        $fecha = date('Y-m-d');
        $anio = date('Y');
        $anioAnterior = date('Y') - 1;
        $anioPosterior = date('Y') + 1;

        if (date('m') < 10) {
            $fechaInicio = date($anioAnterior . "-10-01 00:00:00", strtotime($fecha));
            $fechaFin = date("Y-09-30 23:59:59", strtotime($fecha));
        } else {
            $fechaInicio = date("Y-10-01 00:00:00", strtotime($fecha));
            $fechaFin = date($anioPosterior . "-09-30 23:59:59", strtotime($fecha));
        }
        return (['inicio' => $fechaInicio, 'fin' => $fechaFin]);
    }

    public static function listarDepartamentos()
    {
        $deptos = Departamento::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray();
        return [null => 'Seleccione...'] + $deptos;
    }

    public static function listarMunicipios($id)
    {
        $deptos = Municipio::
        whereHas('provincia', function ($q) use ($id) {
            $q->where('departamento_id', $id);
        })->orderBy('nombre', 'asc')->get()->pluck('nombre', 'id')->toArray();
        return [null => 'Seleccione...'] + $deptos;
    }

    public static function listarCatalogosDescuentos()
    {
        $catalogos = DescuentoCatalogo::orderBy('nombre')->get()->pluck('nombre', 'nombre')->toArray();
        return [null => 'Seleccione...'] + $catalogos;
    }

    public static function listarComplejos()
    {
        return Producto::whereIn('letra', ['A', 'B', 'C'])->orderBy('letra')->get()->pluck('info', 'id')->toArray();
    }

    public static function getDescuentosNoPagables()
    {
        return [
            'CAJA NACIONAL DE SALUD' => 'CAJA NACIONAL DE SALUD',
            'COMIBOL' => 'COMIBOL',
            'FEDECOMIN LA PAZ' => 'FEDECOMIN LA PAZ',
            'FEDECOMIN ORURO' => 'FEDECOMIN ORURO',
            'FEDECOMINORPO' => 'FEDECOMINORPO',
            'FERECOMINORPO' => 'FERECOMINORPO',
            'FEDERACIÓN DEPARTAMENTAL O REGIONAL' => 'FEDERACIÓN DEPARTAMENTAL O REGIONAL',
            'FENCOMIN' => 'FENCOMIN',
        ];
    }

    public static function getMotivosAnulacion()
    {
        return [
            MotivoAnulacion::CambioCliente => MotivoAnulacion::CambioCliente,
            MotivoAnulacion::CambioProducto => MotivoAnulacion::CambioProducto
        ];
    }

    public static function getTransferenciasInterna()
    {
        return [
            TipoTransferencia::CajaACuentaBnb => TipoTransferencia::CajaACuentaBnb,
            TipoTransferencia::CajaACuentaEconomico => TipoTransferencia::CajaACuentaEconomico,
            TipoTransferencia::CuentaBnbACaja => TipoTransferencia::CuentaBnbACaja,
            TipoTransferencia::CuentaEconomicoACaja => TipoTransferencia::CuentaEconomicoACaja,
            TipoTransferencia::CuentaBnbACuentaEconomico => TipoTransferencia::CuentaBnbACuentaEconomico,
            TipoTransferencia::CuentaEconomicoACuentaBnb => TipoTransferencia::CuentaEconomicoACuentaBnb,
        ];
    }

    public static function getMotivosRetiro()
    {
        return [
            MotivoRetiro::TransanccionInsatisfactoria => MotivoRetiro::TransanccionInsatisfactoria,
            MotivoRetiro::CambioProducto => MotivoRetiro::CambioProducto
        ];
    }

    public static function getMesEspanol($mes)
    {
        $nombre = '';
        switch ($mes) {
            case 1:
                $nombre = 'enero';
                break;
            case 2:
                $nombre = 'febrero';
                break;
            case 3:
                $nombre = 'marzo';
                break;
            case 4:
                $nombre = 'abril';
                break;
            case 5:
                $nombre = 'mayo';
                break;
            case 6:
                $nombre = 'junio';
                break;
            case 7:
                $nombre = 'julio';
                break;
            case 8:
                $nombre = 'agosto';
                break;
            case 9:
                $nombre = 'septiembre';
                break;
            case 10:
                $nombre = 'octubre';
                break;
            case 11:
                $nombre = 'noviembre';
                break;
            case 12:
                $nombre = 'diciembre';
                break;
        }
        return $nombre;
    }

    public static function getClasesDevoluciones()
    {
        return array(
            ClaseDevolucion::Interno => 'Retiro de material',
            ClaseDevolucion::Analisis => 'Análisis de laboratorio'
        );
    }

    public static function getTiposMotivosDevoluciones()
    {
        return array(
            TipoMotivoDevolucion::Analisis => 'Análisis',
            TipoMotivoDevolucion::Anticipo => 'Anticipo'
        );
    }

    public static function getTiposMaterial()
    {
        return [
            TipoMaterial::Concentrado => TipoMaterial::Concentrado,
            TipoMaterial::Guiamina => TipoMaterial::Guiamina,
            TipoMaterial::Broza => TipoMaterial::Broza
        ];
    }

    public static function getTiposLoteVenta()
    {
        return [
            TipoLoteVenta::Venta => TipoLoteVenta::Venta,
            TipoLoteVenta::Ingenio => TipoLoteVenta::Ingenio
        ];
    }

    public static function getCaracteristicasLaboratorio()
    {
        return [
            "Sobre Cerrado" => "Sobre Cerrado",
            "Sobre Abierto" => "Sobre Abierto",
            "Muestra Geológica" => "Muestra Geológica"
        ];
    }

    public static function getValorUnitario()
    {
        return [
            null => 'Seleccione...',
            TipoActivoFijo::Pza => TipoActivoFijo::Pza,
            TipoActivoFijo::Litros => TipoActivoFijo::Litros,
            TipoActivoFijo::Kg => TipoActivoFijo::Kg,
        ];
    }

    public static function getTiposActivos()
    {
        return Tipo::orderBy('nombre')->get()->pluck('nombre', 'id')->toArray();
    }

    public static function getPersonal()
    {
        return Personal::orderBy('nombre_completo')->get()->pluck('nombre_completo', 'id')->toArray();
    }
    public static function getpersonalUser($id)
    {
        return Personal::where('id',$id)->pluck('nombre_completo','id')->toArray();
    }
    public static function getTipoHorario()
    {
        $tipoHorarios = TipoHorario::orderBy('id')->get()->pluck('horario', 'id')->toArray();
        $tipoHorarios = ['' => 'Seleccione...'] + $tipoHorarios;
        return $tipoHorarios;
    }


    public static function getOficinasMovimiento()
    {
        return [
            OficinaMovimiento::Principal => OficinaMovimiento::Principal,
            OficinaMovimiento::Laboratorio => OficinaMovimiento::Laboratorio
        ];
    }

    public static function getTiposMovimientosIE()
    {
        return [
            'Egreso' => 'Egreso',
            'Ingreso' => 'Ingreso',
        ];
    }

    public static function getTiposEstados()
    {
        return [
            '%' => 'Todos',
            TipoEstado::Bueno => TipoEstado::Bueno,
            TipoEstado::Malo => TipoEstado::Malo,
            TipoEstado::Regular => TipoEstado::Regular,
            TipoEstado::Otro => TipoEstado::Otro,
        ];


    }

    public static function getDetallesActivos($id)
    {
        return DetalleActivo::where('activo_fijo_id', $id)->orderBy('cantidad')->get()->pluck('info', 'id')->toArray();
    }



    public static function getTiposPagosLaboratorios()
    {
        return [
            'Normal' => 'Normal',
            'Acreditado' => 'Acreditado',

        ];
    }

    public static function getTiposConcentradosAgregar()
    {
        return [
            TipoConcentrado::Venta => 'Normal',
            TipoConcentrado::Sobrante => TipoConcentrado::Sobrante,

        ];
    }

    public static function getTiposPermisos()
    {
        return [
            '2 HORAS MES' => '2 HORAS MES',
            'ASUNTOS PERSONALES 2 DIAS AÑO' => 'ASUNTOS PERSONALES 2 DIAS AÑO',
            'COMISIÓN MEMORANDUM' => 'COMISIÓN MEMORANDUM',
            'DECRETO SUPREMO 3164 PAP MAMO PROST COLON' => 'DECRETO SUPREMO 3164 PAP MAMO PROST COLON',
            'DIA DE LA MADRE' => 'DIA DE LA MADRE',
            'LIC. POR CUMPLEAÑOS' => 'LIC. POR CUMPLEAÑOS',
            'LIC. POR NACIMIENTO HIJO (A)' => 'LIC. POR NACIMIENTO HIJO (A)',
            'MATERNIDAD PRE-NATAL, POST-NATAL' => 'MATERNIDAD PRE-NATAL, POST-NATAL',
            'TOLERANCIA MINISTERIO DE TRABAJO' => 'TOLERANCIA MINISTERIO DE TRABAJO',
            'A CUENTA VACACIÓN' => 'A CUENTA VACACIÓN',
            'BAJA POR ENFERMEDAD, INVALIDEZ' => 'BAJA POR ENFERMEDAD, INVALIDEZ',
            'DÍA DE LA MUJER' => 'DÍA DE LA MUJER',
            'LIC. POR DUELO' => 'LIC. POR DUELO',
            'LIC.POR SALUD' => 'LIC.POR SALUD',
            'PERMISO SIN GOCE DE HABERES' => 'PERMISO SIN GOCE DE HABERES',
            'VACACIONES PROGRAMADAS' => 'VACACIONES PROGRAMADAS',
            'AISLAMIENTO COVID-19, VIRUELA DEL MONO' => 'AISLAMIENTO COVID-19, VIRUELA DEL MONO',
            'COMPENSACIÓN JUSTIFICADA, MEMORANDUM' => 'COMPENSACIÓN JUSTIFICADA, MEMORANDUM',
            'DÍA DEL PADRE' => 'DÍA DEL PADRE',
            'LIC. POR MATRIMONIO' => 'LIC. POR MATRIMONIO',
            'LIC. PADRES MADRES D.S. 3462' => 'LIC. PADRES MADRES D.S. 3462',
            'ALMUERZO' => 'ALMUERZO',
            'SÁBADO LIBRE' => 'SÁBADO LIBRE',
            'PREMIO CONCURSO' => 'PREMIO CONCURSO',

        ];
    }
    public static function TiposPermisos($personal_id){
        $permisosHabilitados = TipoPermisoPersonal::where('personal_id', $personal_id)
                            ->where('es_habilitado', true)
                            ->get();
        $tiposPermisos = [];

        foreach ($permisosHabilitados as $permiso) {
            if($permiso->cantidad_actual>0||($permiso->tipoPermiso->cantidad_dia == null && $permiso->tipoPermiso->cantidad_hora == null)){
            $tipoPermiso = $permiso->tipoPermiso;
            $tiposPermisos[$tipoPermiso->id] = $tipoPermiso->descripcion;}
        }
        return $tiposPermisos;
    }
    public static function todosTiposPermisos()
    {
        $permisosHabilitados = TipoPermiso::orderBy('id', 'asc')->get();

        $tiposPermisos = [];

        foreach ($permisosHabilitados as $permiso) {
            $tipoPermiso = $permiso->id;
            $tiposPermisos[$tipoPermiso] = $permiso->descripcion;
        }

        return $tiposPermisos;
    }


    public static function  tipoHorario($fecha){
        return TipoHorarioPersonal::where('fecha_inicial','<=',$fecha)
                                    ->where('fecha_fin','>=',$fecha)->get();
    }
    public static function diaTraduccion($dia_en_ingles){
        // Array de traducción de inglés a español
        $traducciones = array(
            'Monday'    => 'Lunes',
            'Tuesday'   => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday'  => 'Jueves',
            'Friday'    => 'Viernes',
            'Saturday'  => 'Sábado',
            'Sunday'    => 'Domingo'
        );

        // Verifica si el nombre del día existe en el array de traducción
        if (array_key_exists($dia_en_ingles, $traducciones)) {
            return $traducciones[$dia_en_ingles];
        } else {
            return $dia_en_ingles; // Si no se encuentra en la traducción, retorna el valor original
        }
    }


    public static function listarPermisosSuperior()
    {
       return \App\Models\Rrhh\Permiso::whereEsAprobado(false)
            ->whereHas('personal', function ($q){
                $q->where('superior_id', Auth::user()->personal_id);
            })->get();
    }
    //Tipos de asistencia

    public static function getTiposAsistencias()
    {
        return  [
                 null => 'Seleccione...',
                 'asistencia'=>'asistido',
                 'feriado'=>'feriado',
                 'permiso'=>'permiso',
                 'falta'=>'falta',
                 'horaExtra'=>'horaExtra',];
    }
    public static function listarAsistencia()
    {
       return \App\Models\Rrhh\AsistenciaManual::whereEsAprobado(false)->get();
    }
    public static function listarHorasExtra()
    {
       return \App\Models\Rrhh\HoraExtra::whereEsAprobado(false)->get();

    }

    public static function listarPermisos()
    {
        return \App\Models\Rrhh\Permiso::where('es_aprobado', true)
        ->whereDate('updated_at', now()->toDateString())
        ->get();
    }
    public static function encontrarPersonal($id)
    {
       return \App\Models\Personal::find($id);

    }
    public static function listarCLientesLaboratorio()
    {
        $clientes = \App\Models\Lab\Cliente::
            /*whereHas('cooperativa', function ($q)  {
                $q->where('fecha_expiracion', '>=', date('Y-m-d'));
            })->*/
            get()->pluck('info_cliente', 'id')->toArray();
        //::whereLetra($letra)->whereEstado(EstadoVenta::EnProceso)->orderBy('id')->get()->pluck('lote', 'id')->toArray();
        return [null => 'Seleccione...'] + $clientes;
    }

    public static function getTiposProductores()
    {
        return [
            TipoProductor::Cooperativa => TipoProductor::Cooperativa,
            TipoProductor::Empresa => TipoProductor::Empresa,
        ];
    }

    // funciones para planilla
    public static function horasExtra($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        return $horaExtra->horasExtra($personal_id, $fechaMesAnio);
    }
    public static function horasExtraFeriado($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        return $horaExtra->horasExtraFeriado($personal_id, $fechaMesAnio);
    }
    public static function horasExtraFeriadoDetallePrimero($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        $detalleHora=$horaExtra->horasExtraFeriado($personal_id, $fechaMesAnio);
        if($detalleHora>8){
        return 8;}
        else {return  $detalleHora;}
    }
    public static function horasExtraFeriadoDetalleSegundo($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        $detalleHora=$horaExtra->horasExtraFeriado($personal_id, $fechaMesAnio);
        if($detalleHora>8){
        return $detalleHora-8;}
        else {return  0;}
    }
    public static function horasExtraDomingo($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        return $horaExtra->horasExtraDomingo($personal_id, $fechaMesAnio);
    }
    public static function horasExtraDomingoPrimero($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        $detalleHora=$horaExtra->horasExtraDomingo($personal_id, $fechaMesAnio);
        if($detalleHora>8){
        return 8;}
        else {return  $detalleHora;}
    }
    public static function horasExtraDomingoSegundo($personal_id, $fechaMesAnio){
        $horaExtra= new PlanillaController();
        $detalleHora=$horaExtra->horasExtraDomingo($personal_id, $fechaMesAnio);
        if($detalleHora>8){
        return $detalleHora-8;}
        else {return  0;}
    }
    public static function horaExtraMonto($hora,$haberBasico,$tipo){
            return round($hora*($haberBasico/240)*$tipo,2);
    }


    public static function getTiposFacturas()
    {
        return [
            TipoFactura::CompraVenta => TipoFactura::CompraVenta,
            TipoFactura::ExportacionMinera => TipoFactura::ExportacionMinera,
        ];
    }

    public static function listarParametricasImpuestos($tipo)
    {
        return ParametricaImpuestos::whereTipo($tipo)->orderBy('nombre')->get()->pluck('nombre', 'id')->toArray();
    }

    public static function getPuertosTransito()
    {
        return [
            'Antofagasta' => 'Antofagasta',
            'Arica' => 'Arica',
            'Iquique' => 'Iquique',
            'Ilo' => 'Ilo',
            'Matarani' => 'Matarani',
        ];
    }

    public static function getIncoterms()
    {
        return [

            "EXW - EX Works" => "EXW - EX Works",
            "FCA - Free Carrier" => "FCA - Free Carrier",
            "FAS - Free Alongside Ship" => "FAS - Free Alongside Ship",
            "FOB - Free On Board" => "FOB - Free On Board",
            "CFR - Cost and Freight" => "CFR - Cost and Freight",
            "CIF - Cost, Insurance and Freight" => "CIF - Cost, Insurance and Freight",
            "CPT - Carriage Paid To" => "CPT - Carriage Paid To",
            "CIP - Carriage and Insurance Paid To" => "CIP - Carriage and Insurance Paid To",
            "DPU - Delivery at Place Unloaded" => "DPU - Delivery at Place Unloaded",
            "DAP - Delivery At Place" => "DAP - Delivery At Place",
            "DDP - Delivery Duty Paid" => "DDP - Delivery Duty Paid"

        ];
    }
    //Tipos permisos
    public static function minutosAHorasPermisos($id,$cantidad){
        $tipoPermiso=TipoPermiso::where('id',$id)->first();
        if($id==1){
        $horas = floor($cantidad / 60);
        $minutosRestantes = $cantidad % 60;
        return sprintf('%02d:%02d', $horas, $minutosRestantes);}
        //Condicional para verificar si son permisos ilimitados y la forma de mostrar en pantalla
        if($tipoPermiso->cantidad_dia == null && $tipoPermiso->cantidad_hora == null)
        {return ($cantidad > 50 || $cantidad < 0) ? "--:--:--" : $cantidad;}
        return $cantidad;
    }
    public static function determinarEstiloFondo($id,$valor)
    {   $tipoPermiso=TipoPermiso::where('id',$id)->first();
        if($tipoPermiso->cantidad_dia == null && $tipoPermiso->cantidad_hora == null)
        {return '';}
        else {return $valor == 0 ? 'background-color: #FFD6D6;' : '';}

    }

    public static function getMotivosAnulacionImpuestos()
    {
        return [
            "1" => "FACTURA MAL EMITIDA",
            "2" => "NOTA DE CREDITO-DEBITO MAL EMITIDA",
            "3" => "DATOS DE EMISION INCORRECTOS",
            "4" => "FACTURA O NOTA DE CREDITO-DEBITO DEVUELTA",
        ];
    }
}




