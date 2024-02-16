<?php

namespace App\Http\Controllers\rrhh;

use AnticipoSueldo;
use App\Http\Controllers\Controller;
use App\Models\PagoMovimiento;
use App\Models\Personal;
use App\Models\Rrhh\Asistencia;
use App\Models\Rrhh\Calendario;
use App\Models\Rrhh\Feriado;
use App\Models\Rrhh\HoraExtra;
use App\Models\Rrhh\Permiso;
use App\Models\Rrhh\Planilla;
use App\Patrones\Fachada;
use Carbon\Carbon;
use Contrato;
use Illuminate\Http\Request;
use function redirect;
use function view;
use Flash;
use DB;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class PlanillaController extends Controller
{
    public function index(Request $request){   
        $fecha = $request->mes?? date('Y-m', strtotime('-1 month'));
        $contrato = $request->tipo_contrato?? 'contrato';
        $partes = explode("-", $fecha);
        if($fecha != ""){
            $anio = $partes[0];
            $mes = $partes[1];}
        else{$anio = "";
            $mes = "";}  
                 $planillas = Planilla::where('fecha_planilla', $fecha)
                                ->whereHas('personal', function ($query) use ($contrato) {
                                    $query->where('tipo_contrato', $contrato);
                                })
                                ->get();
        if ($contrato || $planillas !== ''){
        return view('rrhh.planillas_sueldos.index',compact('planillas','anio','mes'));}
        else{
            return view('rrhh.planillas_sueldos.index',compact('planillas','anio','mes'));
        }
    }
    public function diasTrabajados($personal_id, $fechaMesAnio){
        $datosPersonal = Personal::where('id', $personal_id)->first();
        if (!$datosPersonal) {
            return 0; // Personal no encontrado
        }
        $fechaIngreso = new \DateTime($datosPersonal->fecha_contrato);
        $fechaMesAnio= new \DateTime($fechaMesAnio);
        // Verificar si el empleado ingresó en el mes y año proporcionados
        if ($fechaIngreso->format('Y-m') === $fechaMesAnio->format('Y-m')) {
            $diferencia = $fechaIngreso->diff($fechaMesAnio)->days;
            return $diferencia;
        } else {return 30; // Si no ingresó en ese mes, se consideran 30 días trabajados.
        }
    }
    //haber básico PLANILLA
    private function haberBasico($personal_id, $fechaMesAnio,$incremento){
        // Obtener los datos del personal con contrato eventual o indefinido
        $datoPersonal = Personal::where('id', $personal_id)->first();
            // Calcular el haber básico
            $diasTrabajados = $this->diasTrabajados($datoPersonal->id, $fechaMesAnio);
           
            $haberBasico = round((($datoPersonal->haber_basico / 30) * $diasTrabajados)*(1.00+$incremento),2);
        // Retornar los datos procesados
        return $haberBasico;
    }
    private function antiguedad($personal_id, $fechaMesAnio){
        $datoPersonal = Personal::where('id', $personal_id)->first();
        $fechaIngreso = new \DateTime($datoPersonal->fecha_contrato);
        $fechaActual = new \DateTime($fechaMesAnio);
        // Calcular la diferencia en años
        $diferencia = $fechaIngreso->diff($fechaActual);
    
        return $diferencia->y; // Devuelve la antigüedad en años
    }
    // bono de antiguedad PLANILLA
    private function bonoAntiguedad($personal_id, $fechaMesAnio,$sueldoMiniNacional){
        $datoPersonal = Personal::where('id', $personal_id)->first();
        $antiguedad = $this->antiguedad($datoPersonal->id, $fechaMesAnio);
        $porcentajes = [
            2 => 0.05,
            5 => 0.11,
            8 => 0.18,
            11 => 0.26,
            15 => 0.34,
            20 => 0.42,
            25 => 0.50,
        ];
        $bonoAntiguedad = 0;
        foreach ($porcentajes as $anios => $porcentaje) {
            if ($antiguedad >= $anios) {
                $bonoAntiguedad = (($porcentaje*($sueldoMiniNacional*3))/30)* $this->diasTrabajados($personal_id, $fechaMesAnio) ;
            }
        }
        return $bonoAntiguedad;
    }
    public function horasExtra($personal_id, $fechaMesAnio){   
        $sumatoriaHorasExtrasEnMinutos = 0;
        $horasExtrasEnHoras=0;
        $operacion=Personal::where('id', $personal_id)->where('es_hora_extra',true)->first();
        if($operacion){
            $asistenciaPersonal = Asistencia::where('personal_id', $personal_id)
            ->where('hora_marcada', 'LIKE', $fechaMesAnio . '%')
            ->whereRaw('EXTRACT(DOW FROM "hora_marcada") != 0')
            ->whereNotIn(DB::raw('DATE(hora_marcada)'), function ($query) use ($fechaMesAnio) {
                $query->select('fecha')
                    ->from('rrhh.feriado')
                    ->where(DB::raw('DATE(fecha)'), 'LIKE', $fechaMesAnio . '%');
                })
            ->get();
        // Inicializar la suma de minutos de horas extras
            if($asistenciaPersonal){
            // Calcular la suma de minutos de horas extras
            foreach ($asistenciaPersonal as $asistencia) {
                $sumatoriaHorasExtrasEnMinutos += $asistencia->sumatoria_horas_extras;
            }}
            $horasExtrasEnHoras = round($sumatoriaHorasExtrasEnMinutos / 60,2);
        }else{
            //si alguien solicita hora extra de manera unica
            $horaExtraExcepcional = HoraExtra::where('personal_id', $personal_id)
                ->where('inicio', 'LIKE', $fechaMesAnio . '%')
                ->whereRaw('EXTRACT(DOW FROM "inicio") != 0')
                ->whereNotIn(DB::raw('DATE(inicio)'), function ($query) use ($fechaMesAnio) {
                    $query->select('fecha')
                        ->from('rrhh.feriado')
                        ->where(DB::raw('DATE(fecha)'), 'LIKE', $fechaMesAnio . '%');
                    })
                ->get();
            if($horaExtraExcepcional){
                $horasExtrasEnHoras=round($this->diferenciaHorasExtras($horaExtraExcepcional)/60,2);}
        }
        // Convertir minutos a horas
        return $horasExtrasEnHoras;
    }
    public function horasExtraFeriado($personal_id, $fechaMesAnio){   
        $feriados = Asistencia::where('personal_id', $personal_id)
        ->where('hora_marcada', 'LIKE', $fechaMesAnio . '%')
        ->where('tipo_asistencia', 'feriado')
        ->whereRaw('EXTRACT(DOW FROM "hora_marcada") != 0') // Excluir domingos (0 es el código para domingo)
        ->get();
        $horasExtrasFeriado=0;
        if($feriados){
            foreach($feriados as $feriado){
                    $fechaFeriado=date('Y-m-d',strtotime($feriado->hora_marcada));
                    $asistenciaPersonal = HoraExtra::where('personal_id', $personal_id)
                    ->where('inicio', 'LIKE', $fechaFeriado . '%')
                    ->where('es_aprobado', true)
                    ->get();
                    if($asistenciaPersonal){$horasExtrasFeriado=round($this->diferenciaHorasExtras($asistenciaPersonal)/60,2);}}
        }
                             
        return $horasExtrasFeriado;
    }
    public function horasExtraDomingo($personal_id, $fechaMesAnio){   
        $asistenciaPersonal = HoraExtra::where('personal_id', $personal_id)
        ->where('inicio', 'LIKE', $fechaMesAnio . '%')
        ->whereRaw('EXTRACT(DOW FROM "inicio") = 0') // Filtrar por el día de la semana (0 = Domingo)
        ->where('es_aprobado', true)
        ->get();
        
        if($asistenciaPersonal){
            $horasExtrasDomingo=round($this->diferenciaHorasExtras($asistenciaPersonal)/60,2);}
        else{
           $horasExtrasDomingo=0; 
        }    
        return $horasExtrasDomingo;
    }
    private function diferenciaHorasExtras($datos){
        $totTrab=0;
        foreach ($datos as $hora) 
        {   $horaInicio=strtotime(date('H:i:s', strtotime($hora->inicio)));
            $horaFin=strtotime(date('H:i:s', strtotime($hora->fin)));
            $diferencia= $horaFin-$horaInicio;
            $horas = intval($diferencia / 3600)*60;
            $minutos = floor(($diferencia % 3600) / 60);
            $totTrab+=$horas+$minutos;}
        return  $totTrab;  
        
    }
    private function calculoHoraExtraFeriadoDomingo($horasExtras,$haberBasico){
        if($horasExtras>8){
            $montoDoble=round(8*2*($haberBasico/240),2);
            $montoTriple=($horasExtras-8)*3*round(($haberBasico/240),2);
            $montoTotal=$montoTriple+$montoDoble;
        } 
        else{$montoTotal=round($horasExtras*2*($haberBasico/240),2);}
        return $montoTotal;
    }
    public function horaExtraMontoPagado($personal_id,$fechaMesAnio,$incremento){
        $montoDomingo=0;
        $montoFeriado=0;
        $personal=Personal::where('id',$personal_id)->first();
        $haberBasico=$this->haberBasico($personal->id,$fechaMesAnio,$incremento);
        $horaExtraDomingo=$this->horasExtraDomingo($personal_id,$fechaMesAnio);
        $horaExtraFeriado=$this->horasExtraFeriado($personal_id,$fechaMesAnio);
        $horaExtraNormal=$this->horasExtra($personal_id,$fechaMesAnio);
        if($horaExtraDomingo!=0 || $horaExtraFeriado!=0){
            $montoDomingo=$this->calculoHoraExtraFeriadoDomingo($horaExtraDomingo,$haberBasico);
            $montoFeriado=$this->calculoHoraExtraFeriadoDomingo($horaExtraFeriado,$haberBasico);
        }
        $montoNormal=round($horaExtraNormal*2*($haberBasico/240),2);
        return $montoNormal+$montoDomingo+$montoFeriado;

    }
    private function horaExtratotal($personal_id, $fechaMesAnio){
        return $this->horasExtra($personal_id, $fechaMesAnio)+$this->horasExtraFeriado($personal_id, $fechaMesAnio)+$this->horasExtraDomingo($personal_id, $fechaMesAnio);
    }
    private function otrosBonos($personal_id){
        // $personal=Personal::where('id',$personal_id)->first();
        // return $personal->adicion_bono;
        $personal = Personal::where('id', $personal_id)->value('adicion_bono') ?? 0;
        return $personal;

    }
    private function descuentoAfp($personal_id,$fechaMesAnio,$sueldominimo,$incremento){
        $personal=Personal::where('id',$personal_id)->first();
       
        $totalGanado=$this->haberBasico($personal_id,$fechaMesAnio,$incremento)+$this->bonoAntiguedad($personal_id,$fechaMesAnio,$sueldominimo)+$this->horaExtraMontoPagado($personal_id,$fechaMesAnio,$incremento)+$this->otrosBonos($personal_id);
        if($personal->tipo_asegurado =='dependiente'){
            return round($totalGanado*0.1271,2);
        }
        else{return round($totalGanado*0.0271,2);}
    }
    private function aporteSolidario($personal_id,$fechaMesAnio,$sueldominimo,$incremento){
        $personal=Personal::where('id',$personal_id)->first();
        $totalGanado=$this->haberBasico($personal_id,$fechaMesAnio,$incremento)+$this->bonoAntiguedad($personal_id,$fechaMesAnio,$sueldominimo)+$this->horaExtraMontoPagado($personal_id,$fechaMesAnio,$incremento)+$this->otrosBonos($personal_id,$fechaMesAnio);
        if($totalGanado>13000){
            return ($totalGanado-13000)*0.01;}
        else{return 0;}     
    }
    private function rcIva($personal_id,$rciva,$fechaMesAnio){
        //rc-iva no se requiere todavia
        $rcIva=$rciva;
        $personal_id=Personal::where('id',$personal_id)->first();
        return 0;    
    }
    public function atrasosDescuento($personal_id,$fechaMesAnio,$incremento){   
        $atrasos=0;
        $asistencias=Asistencia::where('personal_id',$personal_id)->where('hora_marcada','LIKE',$fechaMesAnio.'%')->get();
        // dd($personal_id);
        if($asistencias){
        foreach($asistencias as $asistencia)
        {$atrasos += $asistencia->sumatoria_atrasos_minutos;}}
        if($atrasos>30){
        $prueba=(($atrasos-30)*2)*($this->haberBasico($personal_id,$fechaMesAnio,$incremento)/14400);
        // dd($atrasos);    
        return (($atrasos-30)*2)*($this->haberBasico($personal_id,$fechaMesAnio,$incremento)/14400);}
        else{return 0;} 

    }
    public function faltaSinGoseHaberes($personal_id,$fechaMesAnio,$incremento){   
        //permisos sin gose de haberes (permisosSGH) y permisos sin gose de haberes personal (permisosSGHP)
        $permisosSGH = Permiso::where('personal_id', $personal_id)
                                ->where(function ($query) use ($fechaMesAnio) {
                                    $query->where(function ($subquery) use ($fechaMesAnio) {
                                        $subquery->where('inicio', 'LIKE', $fechaMesAnio . '%')
                                                ->orWhere('fin', 'LIKE', $fechaMesAnio . '%');
                                    })
                                    ->where('es_aprobado', true)
                                    ->where('tipo', 'PERMISO SIN GOCE DE HABERES');
                                })
                                ->get();
        $permisos=0;
        if($permisosSGH){
        foreach($permisosSGH as $permisosSGHP){
            $AsistenciaPermisos = Asistencia::where('personal_id', $permisosSGHP->personal_id)
            ->whereDate('hora_marcada', '>=', $permisosSGHP->inicio)
            ->whereDate('hora_marcada', '<=', $permisosSGHP->fin)
            ->where('hora_marcada','LIKE',$fechaMesAnio.'%')
            ->count();
        $permisos+=$AsistenciaPermisos;  
        }}
        if($permisos!=0){return $permisos*($this->haberBasico($personal_id,$fechaMesAnio,$incremento))/30;}else{return 0;}
        
    }
    public function faltasSinPermiso($personal_id,$fechaMesAnio,$incremento){
       //FALTAS sin permiso se divide con 30 para saber cuanto gana por dia
            $montoFaltaNormal= $this->fatasdiaCompletoMonto($personal_id,$fechaMesAnio,$incremento);
            $montoFaltaMedio= $this->faltasMedioDiaMonto($personal_id,$fechaMesAnio,$incremento);
            return $montoFaltaNormal+$montoFaltaMedio;
    }
    public function fatasdiaCompletoMonto($personal_id,$fechaMesAnio,$incremento)
    {   
        $faltas=Asistencia::where('personal_id',$personal_id)->where('hora_marcada','LIKE',$fechaMesAnio.'%')->where('tipo_asistencia','falta')->count();
       if($faltas){
            $faltasMedioDia=$this->faltasMedioDia($personal_id,$fechaMesAnio);
            $fataNormal=$faltas-$faltasMedioDia;
            $montoFaltaNormal= round(($fataNormal*($this->haberBasico($personal_id,$fechaMesAnio,$incremento))/30)*2,2);
            return  $montoFaltaNormal;}
        else{return 0;}    
    }
    public function faltasMedioDiaMonto($personal_id,$fechaMesAnio,$incremento){
        $faltasMedioDia=$this->faltasMedioDia($personal_id,$fechaMesAnio);
        $montoFaltaMedio=round($faltasMedioDia*($this->haberBasico($personal_id,$fechaMesAnio,$incremento))/30,2);
        return $montoFaltaMedio;
    }
    public function faltasMedioDia($personal_id,$fechaMesAnio)
    {   
        $faltas = Asistencia::join('rrhh.feriado', function($join) use ($fechaMesAnio) {
                                $join->on(DB::raw('DATE(rrhh.asistencia.hora_marcada)'), '=', DB::raw('DATE(rrhh.feriado.fecha)'))
                                    ->where('rrhh.feriado.es_turno', true)
                                    ->where(DB::raw('DATE(rrhh.feriado.fecha)'), 'LIKE', $fechaMesAnio . '%');
                            })
                            ->where('rrhh.asistencia.personal_id', $personal_id)
                            ->where('rrhh.asistencia.tipo_asistencia', 'falta')
                            ->select('rrhh.asistencia.*')
                            ->count();
        return $faltas;
    }
    private function anticiposSueldo($personal_id,$fechaMesAnio,$incremento){
        return PagoMovimiento::whereDate('created_at', '=', $fechaMesAnio)
        ->where('origen_type', AnticipoSueldo::class)
            ->whereHas('origen', function ($q) use ($personal_id) {
                $q->where('personal_id', $personal_id);
            })
            ->sum('monto'); 
              
    }
    private function anticiposOtrosDescuentos($personal_id,$fechaMesAnio,$incremento){
       return $this->atrasosDescuento($personal_id,$fechaMesAnio,$incremento)+$this->faltaSinGoseHaberes($personal_id,$fechaMesAnio,$incremento)+$this->faltasSinPermiso($personal_id,$fechaMesAnio,$incremento);//$this->anticiposSueldo($personal_id,$fechaMesAnio,$incremento);    
    }
    private function eventualPlanilla($datos,$personal_id){
        $personal=Personal::where('id',$personal_id)->first();
        $item=[];
        $item['personal_id']=$personal->id;  
        $item['fecha_planilla']=$datos['fechaMesAnio'];
        $item['haber_basico']=$this->haberBasico($personal->id,$datos['fechaMesAnio'],$datos['incremento']);
        $item['bono_antiguedad']=$this->bonoAntiguedad($personal->id,$datos['fechaMesAnio'],$datos['sueldominimo']);
        $item['numero_horas_extra']=$this->horaExtratotal($personal->id,$datos['fechaMesAnio']);
        $item['hora_extra_monto_pagado']=$this->horaExtraMontoPagado($personal->id,$datos['fechaMesAnio'],$datos['incremento']);
        $item['bono_prod']=0;
        $item['dominical']=0;
        $item['otros_bonos']=$this->otrosBonos($personal->id);
        $item['afp']=0;
        $item['aporte_solidario']=0;
        $item['rc_iva']=0;
        $item['anticipos_otros_descuentos']=$this->anticiposOtrosDescuentos($personal->id,$datos['fechaMesAnio'],$datos['incremento']);
        return $item;
        
    }
    private function indefinidoPlanilla($datos,$personal_id){
        $personal=Personal::where('id',$personal_id)->first();
        $item['personal_id']=$personal->id;  
        $item['fecha_planilla']=$datos['fechaMesAnio'];
        $item['haber_basico']=$this->haberBasico($personal->id,$datos['fechaMesAnio'],$datos['incremento']);
        $item['bono_antiguedad']=$this->bonoAntiguedad($personal->id,$datos['fechaMesAnio'],$datos['sueldominimo']);
        $item['numero_horas_extra']=$this->horaExtratotal($personal->id,$datos['fechaMesAnio']);
        $item['hora_extra_monto_pagado']=$this->horaExtraMontoPagado($personal->id,$datos['fechaMesAnio'],$datos['incremento']);
        $item['bono_prod']=0;
        $item['dominical']=0;
        $item['otros_bonos']=$this->otrosBonos($personal->id);
        $item['afp']=$this->descuentoAfp($personal->id,$datos['fechaMesAnio'],$datos['sueldominimo'],$datos['incremento']);
        $item['aporte_solidario']=$this->aporteSolidario($personal->id,$datos['fechaMesAnio'],$datos['sueldominimo'],$datos['incremento']);
        $item['rc_iva']=$this->rcIva($personal->id,$datos['rc_iva'],$datos['fechaMesAnio'],);
        $item['anticipos_otros_descuentos']=$this->anticiposOtrosDescuentos($personal->id,$datos['fechaMesAnio'],$datos['incremento']); 
        
        return $item;
    }
    private function fechaPlanilla($fechaPlanilla){
        // Divide la fecha en mes y año
        list($mes, $ano) = explode("-", $fechaPlanilla);
        // Calcula el último día del mes en formato YY-MM-DD
        $ultimoDiaDelMes = date("Y-m-d", strtotime("$ano-$mes-01 last day of"));
        // Formatea la fecha en "d/m/Y" (día/mes/año)
        return date("d/m/Y", strtotime($ultimoDiaDelMes));
        
    }
    public function create(){
        return view('rrhh.planillas_sueldos ');
    }
    public function store(Request $request)
    {   
        // \DB::beginTransaction();
        // try {
            
                $datos=$request->all();
                $controlEventual = Planilla::where('fecha_planilla', $request->fechaMesAnio)
                    ->whereHas('personal', function ($query) {
                        $query->where('tipo_contrato', 'eventual');
                    })->count();

                $controlContrato = Planilla::where('fecha_planilla', $request->fechaMesAnio)
                    ->whereHas('personal', function ($query) {
                        $query->where('tipo_contrato', 'contrato');
                    })->count();
                // $this->faltasMedioDia(26,$datos['fechaMesAnio']);
                if(strtotime($request->fechaMesAnio) < strtotime(date('Y-m-01')))
                {
                    if($controlEventual==0 || $controlContrato==0)
                    {    $item=[];
                        $eventual = Personal::where('tipo_contrato', 'eventual')->get();
                        $contrato= Personal::where('tipo_contrato', 'contrato')->get();
                                            
                        $personalGeneral= Personal::where('tipo_contrato', 'eventual')->orWhere('tipo_contrato', 'contrato')->get();
                        if(isset($datos['ambos'])){
                        foreach($personalGeneral as $personal){
                            if($personal->tipo_contrato=="eventual"){$item=$this->eventualPlanilla($datos,$personal->id);Planilla::create($item);$item=[];}
                            if($personal->tipo_contrato=="contrato"){$item=$this->indefinidoPlanilla($datos,$personal->id);Planilla::create($item);$item=[];}
                        }}
                        elseif(isset($datos['eventuales'])){
                            foreach($eventual as $personal)
                            {$item=$this->eventualPlanilla($datos,$personal->id);Planilla::create($item);$item=[];}}
                        elseif(isset($datos['contrato'])){  
                            foreach($contrato as $personal)
                            {$item=$this->indefinidoPlanilla($datos,$personal->id);Planilla::create($item);$item=[];}}
                        // \DB::commit();
                        Flash::success('Planilla de sueldo y salarios creada correctamente.');
                        return redirect()->route('planillas-sueldos.index');
                    }
                    else{Flash::error('Mes ya creado para la planilla de sueldos y salarios');
                        return redirect(route('planillas-sueldos.index'));}
                }
                else{Flash::error('No se puede generar la planilla antes que termine el mes de generacion de planilla');
                    return redirect(route('planillas-sueldos.index'));
                }
            // }
            // catch (\Exception $e) {
            // \DB::rollBack();
            // Flash::error('Error al generarse el archivo');
            // return redirect(route('planillas-sueldos.index'));}    
    } 
}