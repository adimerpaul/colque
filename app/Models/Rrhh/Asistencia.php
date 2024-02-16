<?php

namespace App\Models\Rrhh;

use App\Http\Controllers\rrhh\PermisoController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Personal;
use App\Models\Rrhh\TipoHorarioPersonal;
use App\Models\Rrhh\TipoHorario;
use App\Models\Rrhh\Permiso;
use App\Models\Rrhh\HoraExtra;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    public $table = 'rrhh.asistencia';

    protected $fillable = [
        'personal_id',
        'hora_marcada',
        'tipo_asistencia',
        'user_registro',
        'user_edicion',
        'observacion',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function userRegistro()
    {
        return $this->belongsTo(User::class, 'user_registro');
    }
    
    public function userEdicion()
    {
        return $this->belongsTo(User::class, 'user_edicion');
    }

    //hora extra mañana
    public function getHoraExtraMananaAttribute()
    {
        $medioDia=strtotime('12:00:00');
        $dia=strtotime(date('Y-m-d', strtotime($this->hora_marcada)));
        //hora de marcado del personal
        $horaMarcada = strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));
        //hora de entrada por defecto
        $horaContar = strtotime('08:00:00');
        //parametro desde que momento se cuenta horas extra
        $horaLimite = strtotime('07:30:00');
        if(strtotime(date('H:i:s', strtotime($this->hora_marcada)))<$medioDia)
        {   
            //pregutar si hay una falta o permiso
            if($horaMarcada!=strtotime('00:00:00')){
                
                //buscar fechas de ingreso del personal
                $personalfecha = TipoHorarioPersonal::where('personal_id', $this->personal_id)
                    ->where('fecha_inicial', '<=', $this->hora_marcada)
                    ->where('fecha_fin', '>=', $this->hora_marcada)
                    ->first();
                //buscar si tiene horario el personal e ingresar el horario de ingreso del personal
                if ($personalfecha) {
                        if (date('w', $dia) != 6) {
                            $horaContar = strtotime($personalfecha->tiposHorarios->inicio_semana);

                        } else {
                            $horaContar = strtotime($personalfecha->tiposHorarios->inicio_sabado);
                            if($horaContar==false){$horaContar = strtotime('08:00:00');                            }
                        }
                }
                //calcular la cantidad de hora extra ingresadas manualmente
                $horaExtra=HoraExtra::where('personal_id', $this->personal_id)
                                        ->wheredate('inicio',$this->hora_marcada)
                                        ->wheretime('inicio', '<=','12:00:00')->first();
                // dd($horaExtra);                        
                                        
                if($horaExtra)
                {
                    $horaExtraIncial=Asistencia::where('personal_id',$this->personal_id)
                                            ->where('hora_marcada',$horaExtra->fin)->first();
                    
                    if($horaExtraIncial->hora_marcada!=$this->hora_marcada)
                        {$horaLimite=0;
                        $horaMarcada=0;}
                    else{$horaMarcada = strtotime($horaExtra->inicio);
                        $horaContar = strtotime($horaExtra->fin);
                        $horaLimite= strtotime($horaExtra->fin);}

                }
                //se calcula las horas extras del personal
                if ($horaMarcada < $horaLimite) {
                    $diferencia = $horaContar - $horaMarcada;
                    $horas = floor($diferencia / 3600);
                    $minutos = floor(($diferencia % 3600) / 60);
                    $segundos = $diferencia % 60;
                    return "$horas:$minutos:$segundos";
                } else {
                    return 0;
                }
            }
            else{return 0;}
        }
        else{return 0;}
    }
    public function getHoraExtraTardeAttribute()
    {   
        $horaMarcada = strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));
        $dia=strtotime(date('Y-m-d', strtotime($this->hora_marcada)));
        $horaLimite = strtotime('16:30:00');
        $horaMaxima = strtotime('12:00:00');
        //verificar si la hora marcahada es mayor o igual que las doce
        if(strtotime(date('H:i:s', strtotime($this->hora_marcada)))>=$horaMaxima)
        {  

            if($horaMarcada!=strtotime('00:00:00')){
                if (date('w', $dia) != 0) {
                    //consulta el horario de salida del personal
                    $personalfecha = TipoHorarioPersonal::where('personal_id', $this->personal_id)
                        ->where('fecha_inicial', '<=', $this->hora_marcada)
                        ->where('fecha_fin', '>=', $this->hora_marcada)
                        ->first();
                    if ($personalfecha) {
                            if (date('w', $dia) != 6) {
                                $horaLimite = strtotime($personalfecha->tiposHorarios->fin_semana);

                            } else {
                                $horaLimite = strtotime($personalfecha->tiposHorarios->fin_sabado);
                                if($horaLimite==false){$horaLimite = strtotime('12:30:00');                            }
                            }
                    }
                }

                //calcular la cantidad de hora extra ingresadas manualmente
                $horaExtra=HoraExtra::where('personal_id', $this->personal_id)
                                        ->wheredate('inicio',$this->hora_marcada)
                                        ->wheretime('inicio', '>=','12:00:00')
                                        ->where('es_aprobado',true)
                                        ->first();
                $horaExtraDomingo = HoraExtra::where('personal_id', $this->personal_id)
                                        ->whereDate('inicio', $this->hora_marcada)
                                        ->where(function ($query) {
                                            $query->whereTime('inicio', $this->hora_marcada)
                                                ->orWhereTime('fin', $this->hora_marcada);
                                        })
                                        ->where('es_aprobado',true)
                                        ->first();

                if ($horaExtra)
                {
                    $horaExtraIncial=Asistencia::where('personal_id',$this->personal_id)
                                            ->where('hora_marcada',$horaExtra->inicio)->first();
                    if($horaExtraIncial->hora_marcada==$this->hora_marcada)
                    {   $horaLimite=0;
                        $horaMarcada=0;}
                    else{   $horaLimite = strtotime($horaExtra->inicio);
                            $horaMarcada = strtotime($horaExtra->fin);}
                }
                elseif($horaExtraDomingo){
                    $horaExtraIncial=Asistencia::where('personal_id',$this->personal_id)
                                            ->where('hora_marcada',$horaExtraDomingo->inicio)->first();
                    if($horaExtraIncial->hora_marcada==$this->hora_marcada)
                    {   $horaLimite=0;
                        $horaMarcada=0;}
                    else{   $horaLimite = strtotime($horaExtraDomingo->inicio);
                            $horaMarcada = strtotime($horaExtraDomingo->fin);}
                }
                //calculo de horas extra
                if ($horaMarcada > $horaLimite) {
                    $diferencia = $horaMarcada - $horaLimite;
                    $horas = floor($diferencia / 3600);
                    $minutos = floor(($diferencia % 3600) / 60);
                    $segundos = $diferencia % 60;
                    return "$horas:$minutos:$segundos";

                } else {
                    return 0;
                }
            }
            else{return 0;}
        }
        else{return 0;}
    }

    //cantidad de atraso
    public function getAtrasoAttribute()
    {   
        if($this->tipo_asistencia=='permiso'){return 0;}
        $asistenciaUltima=$this->asistencia_ultima;
        if($this->hora_marcada === $asistenciaUltima){return 0;}
        $horaMarcada = strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));
        $dia = strtotime(date('Y-m-d', strtotime($this->hora_marcada)));
        $horaLimite = strtotime('08:00:00');
        $horaMaxima = strtotime('12:00:00');
        
        if (date('w', $dia) == 0) {
            return 0;}
        //si existe hora extra    
        $horaExtra=HoraExtra::where('personal_id', $this->personal_id)
                            ->where('inicio', '<=', $this->hora_marcada)
                            ->where('fin', '>=', $this->hora_marcada)
                            ->get();
        if($horaExtra==null){return 0;}

        //horario de la personal que marco     
        $personalfecha = TipoHorarioPersonal::where('personal_id', $this->personal_id)
            ->where('fecha_inicial', '<=', $this->hora_marcada)
            ->where('fecha_fin', '>=', $this->hora_marcada)
            ->first();

        if ($personalfecha) {
                if (date('w', $dia) != 6) {
                    $horaLimite = strtotime($personalfecha->tiposHorarios->inicio_semana);
                    $horaIngreso=$personalfecha->tiposHorarios->inicio_semana;
                    $horaFinal= strtotime($personalfecha->tiposHorarios->fin_semana);

                } else {
                    $horaLimite = strtotime($personalfecha->tiposHorarios->inicio_sabado);
                    $horaFinal= strtotime($personalfecha->tiposHorarios->fin_sabado);
                    $horaIngreso=$personalfecha->tiposHorarios->inicio_sabado;
                    if($horaFinal==false && $horaLimite==false){$horaLimite=strtotime('08:00:00');$horaFinal=strtotime('12:30:00');$horaIngreso==strtotime('08:00:00');}
                }
        }

        //Verificar si el personal tiene permiso
        $horaFinal= date('H:i:s', $horaFinal);
        $fecha=date('Y-m-d',strtotime($this->hora_marcada));
        //consulta de permiso y nueva hora limite de entrada
        $permisoAsistencia=Asistencia::where('personal_id',$this->personal_id)
                                       ->whereBetween('hora_marcada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
                                       ->where('tipo_asistencia','permiso')->first();
        $permisoAsistenciaPrueba=Asistencia::where('personal_id',$this->personal_id)
                                       ->whereBetween('hora_marcada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
                                       ->where('tipo_asistencia','permiso')->get();
        // dd($permisoAsistenciaPrueba);                               
        if (!empty($permisoAsistencia)) {
            $permisoIngreso = date('H:i:s', strtotime($permisoAsistencia->hora_marcada));
            $horaInicioPermiso = date('H:i:s', strtotime($this->hora_marcada));

                if($permisoIngreso==$horaIngreso){
                    $permiso = Permiso::where('personal_id', $this->personal_id)
                                        ->where('es_aprobado', true)
                                        ->where(function ($query) use ($fecha) {
                                            $query->whereRaw('DATE(inicio) = ?', [$fecha])
                                                ->orWhereRaw('DATE(fin) = ?', [$fecha]);
                                        })
                                        ->pluck('fin');
                    $hora = substr($permiso[0], 11, 8);                                
                    $horaEntrada=date('H:i:s', strtotime($hora));  
                    $horaLimite=strtotime($horaEntrada);
                    $horaMaxima=strtotime($horaFinal);
                }
                else{
                    return 0;
                }                    
            }
            // dd(date('H:i:s',$horaMarcada),date('H:i:s',$horaLimite));

            if ($horaLimite < $horaMarcada && $horaMarcada < $horaMaxima) {
                    $diferencia = $horaMarcada - $horaLimite;
                    $horas = floor($diferencia / 3600);
                    $minutos = floor(($diferencia % 3600) / 60);
                    $segundos = $diferencia % 60;
                    return "$horas:$minutos:$segundos";
        }else{
            return 0;
        }

    }

    public function getAsistenciaUltimaAttribute(){
        $control=$this->control_asistencia;                               
        if($control >1){
            $fecha=date('Y-m-d',strtotime($this->hora_marcada));
            return Asistencia::where('personal_id',$this->personal_id)
                                    ->whereBetween('hora_marcada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
                                    ->where('tipo_asistencia','asistido')->latest('hora_marcada')->value('hora_marcada');
        }
        return 0;
    }
    //Conteo Total de Atrasos
    public function getsumatoriaAtrasosMinutosAttribute()
    {   
        if($this->tipo_asistencia=='permiso'){return 0;}
        $asistenciaUltima=$this->asistencia_ultima;
        if($this->hora_marcada === $asistenciaUltima){return 0;}
        $horaMarcada = strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));
        $horaLimite = strtotime('08:00:00');
        $dia=strtotime(date('Y-m-d', strtotime($this->hora_marcada)));
        $horaMaxima=strtotime('12:00:00');
        $horaFinal= strtotime('08:00:00');

        if (date('w', $dia) == 0) {
            return 0;}

        $personalfecha = TipoHorarioPersonal::where('personal_id', $this->personal_id)
            ->where('fecha_inicial', '<=', $this->hora_marcada)
            ->where('fecha_fin', '>=', $this->hora_marcada)
            ->first();
        $horaExtra=HoraExtra::where('personal_id', $this->personal_id)
            ->where('inicio', '<=', $this->hora_marcada)
            ->where('fin', '>=', $this->hora_marcada)
            ->first();
        if($horaExtra!=null){return 0;}
            if ($personalfecha) {
                if (date('w', $dia) != 6) {
                    $horaLimite = strtotime($personalfecha->tiposHorarios->inicio_semana);
                    $horaIngreso=$personalfecha->tiposHorarios->inicio_semana;
                    $horaFinal= strtotime($personalfecha->tiposHorarios->fin_semana);
                } else {
                    $horaLimite = strtotime($personalfecha->tiposHorarios->inicio_sabado);
                    $horaFinal= strtotime($personalfecha->tiposHorarios->fin_sabado);
                    $horaIngreso=$personalfecha->tiposHorarios->inicio_sabado;
                    if($horaFinal==false && $horaLimite==false){$horaLimite=strtotime('08:00:00');$horaFinal=strtotime('12:30:00');$horaIngreso==strtotime('08:00:00');}
                }
        }

         //Verificar si el personal tiene permiso
         $horaFinal= date('H:i:s', $horaFinal);
         $fecha=date('Y-m-d',strtotime($this->hora_marcada));
         //consulta de permiso y nueva hora limite de entrada
         $permisoAsistencia=Asistencia::where('personal_id',$this->personal_id)
                                       ->whereBetween('hora_marcada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
                                        ->where('tipo_asistencia','permiso')->first();
        if (!empty($permisoAsistencia)) {
            $permisoIngreso = date('H:i:s', strtotime($permisoAsistencia->hora_marcada)); 
                if($permisoIngreso==$horaIngreso){
                    $permiso = Permiso::where('personal_id', $this->personal_id)
                    ->where('es_aprobado', true)
                    ->where(function ($query) use ($fecha) {
                        $query->whereRaw('DATE(inicio) = ?', [$fecha])
                            ->orWhereRaw('DATE(fin) = ?', [$fecha]);
                    })
                    ->pluck('fin');
                    $hora = substr($permiso[0], 11, 8);                                
                    $horaEntrada=date('H:i:s', strtotime($hora));  
                    $horaLimite=strtotime($horaEntrada);
                    $horaMaxima=strtotime($horaFinal);
                }
                else{
                    return 0;
                }    
            }

        if($horaLimite<$horaMarcada && $horaMarcada<$horaMaxima)
        {
            $diferencia= $horaMarcada-$horaLimite;
            $horas = intval($diferencia / 3600)*60;
            $minutos = floor(($diferencia % 3600) / 60);
            return $horas+$minutos;

        }else{return 0;}
    }
    public function getpermisosCortosSAttribute(){
        
    }
    //Suma total Horas Extras
    public function getsumatoriaHorasExtrasAttribute()
    {
        if($this->hora_extra_tarde == "00:00:00" && $this->hora_extra_manana == "00:00:00"){  
        return 0;}
        if ($this->hora_extra_tarde != "00:00:00"){
            list($horas, $minutos, $segundos) = explode(":", date('H:i:s',strtotime($this->hora_extra_tarde)));
            $horas_en_minutos = $horas * 60;
        }
        else{
            list($horas, $minutos, $segundos) = explode(":", date('H:i:s',strtotime($this->hora_extra_manana)));
            $horas_en_minutos = $horas * 60;
        } 
        $total_minutos = $horas_en_minutos + $minutos;
        // dd($total_minutos);
        return $total_minutos;
    }
     //Sumatoria Segundos Atraso
    public function getsegundosAtrasosAttribute()
    {
        $horaLimite=strtotime('08:00:00');
        $horaMaxima=strtotime('12:00:00');
        $horaMarcada=strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));

        if($horaLimite<$horaMarcada && $horaMarcada<$horaMaxima)
        {
            $diferencia= $horaMarcada-$horaLimite;
            $segundos = $diferencia % 60;
            return $segundos;
        }else{return 0;}
    }
     //Sumatoria segundos de horas extra
    public function getsegundosHorasMananaTardeAttribute()
    {
    //Segundos Extra mañana
    $horaLimiteM = strtotime('07:30:00');
    $horaContarM = strtotime('08:00:00');
    $horaMarcadaM= strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));

    if ($horaMarcadaM < $horaLimiteM)
    {
        $diferenciaM = $horaContarM - $horaMarcadaM;
        $segundosM = $diferenciaM % 60;

    }else{$segundosM = 0;}

    //Segundos extra tarde
    $horaLimiteT = strtotime('16:30:00');
    $horaMarcadaT=strtotime(Carbon::parse($this->hora_marcada)->format('H:i:s'));

    if ($horaMarcadaT > $horaLimiteT) {
        $diferenciaT = $horaMarcadaT - $horaLimiteT;
        $segundosT = $diferenciaT % 60;

    } else {
        $segundosT=0;
    }

    return $segundosM+$segundosT;
    }
    public function getcontrolAsistenciaAttribute()
    {
        return Asistencia::where('personal_id',$this->personal_id)
                                ->whereDate('hora_marcada',$this->hora_marcada)
                                ->where('tipo_asistencia','asistido')
                                ->count();
    }
    public function getdiaAttribute(){

        setlocale(LC_TIME, 'es_ES'); // Configura la localización en español
        $horaMarcada = new DateTime($this->hora_marcada);
        // Obtener el día de la semana en español
        $diaSemana = strftime('%A', $horaMarcada->getTimestamp());
        $hora_24h = $horaMarcada->format('G');
        if ($hora_24h < 12) {

            $momentoDia = "Mañana";
        } else {
            $momentoDia = "Tarde";
        }
       
        return  \App\Patrones\Fachada::diaTraduccion($diaSemana) ." - ".$momentoDia;
    }
    public function gethoraExtraManual()
    {

    }
}
