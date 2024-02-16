<?php

namespace App\Models\Rrhh;

use App\Models\Personal;
use App\Http\Controllers\rrhh\PlanillaController;
use Hamcrest\DiagnosingMatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    use HasFactory;
    public $table = 'rrhh.planilla';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        
        'personal_id',
        'fecha_planilla',
        'haber_basico',
        'bono_antiguedad',
        'numero_horas_extra',
        'hora_extra_monto_pagado',
        'bono_prod',
        'dominical',
        'otros_bonos',
        'afp',
        'aporte_solidario',
        'rc_iva',
        'anticipos_otros_descuentos'
    ];
        public function personal()
        {
            return $this->belongsTo(Personal::class);
        }
        
        public function getDiasTrabajadosAttribute(){
            $fechaIngreso = new \DateTime ($this->personal->fecha_ingreso);
            $fechaMesAnio = \DateTime::createFromFormat('Y-m', $this->fecha_planilla);
    
            if ($fechaIngreso->format('Y-m') === $fechaMesAnio->format('Y-m')) {
                $diferencia = $fechaMesAnio->diff($fechaIngreso);
                return $diferencia->days;} 
                else {return 30;}
        }
        public function getHorasTrabajadasAttribute(){
            return 8;
        }
        
        //Planilla
        public function getTotalGanadoAttribute()
        {
            return $this->haber_basico+$this->bono_antiguedad+$this->hora_extra_monto_pagado+$this->bono_prod+$this->dominical+$this->otros_bonos;
        }
        public function getTotalDescuentosAttribute()
        {
            return $this->afp+$this->aporte_solidario+$this->rc_iva+$this->anticipos_otros_descuentos;
        }
        public function getLiquidoPagableAttribute()
        {
            return $this->total_ganado-$this->total_descuentos;
        }
        public function getatrasosAttribute(){
            $atrasos= Asistencia::where('personal_id', $this->personal_id)
            ->where('hora_marcada', 'LIKE', $this->fecha_planilla . '%')
            ->get();
            return $atrasos->sum('sumatoria_atrasos_minutos') . ' min';
        }
        public function getatrasosMontoAttribute(){
            $monto = new PlanillaController(); 
           return round($monto->atrasosDescuento($this->personal_id,$this->fecha_planilla,0.00),2);
        }
        public function getfaltasAttribute(){
            $faltasMedioDia=new PlanillaController();
            $faltas=Asistencia::where('personal_id',$this->personal_id)->where('hora_marcada','LIKE',$this->fecha_planilla.'%')->where('tipo_asistencia','falta')->count();
            $faltasMedioDias=$faltasMedioDia->faltasMedioDia($this->personal_id,$this->fecha_planilla);
            $fataNormal=$faltas-$faltasMedioDias;
            return $fataNormal .' dias';
        }
        public function getfaltasMontoAttribute(){
            $faltamonto=new PlanillaController();
            return round($faltamonto->fatasdiaCompletoMonto($this->personal_id,$this->fecha_planilla,0.00),2); 
        }
        public function getpermisoSinGoceHaberAttribute(){
            $permiso= Asistencia::where('personal_id', $this->personal_id)
            ->where('hora_marcada', 'LIKE', $this->fecha_planilla . '%')
            ->where('tipo_asistencia','permiso')
            ->where('observacion','LIKE','PERMISO SIN GOCE DE HABERES'. '%')
            ->count();
            return $permiso .' dias';
        }
        public function getpermisoSinGoceHaberMontoAttribute(){
            $permisoMonto=new PlanillaController();
            return round($permisoMonto->faltaSinGoseHaberes($this->personal_id,$this->fecha_planilla,0.00),2);
        }
        public function gettotalDescuentoAttribute(){
             return $this->atrasos_monto+$this->faltas_monto+$this->permiso_sin_goce_haber_monto+$this->afp;
        }
        public function getfaltasMedioDiaAttribute(){
            $faltasMedioDia=new PlanillaController();
            return $faltasMedioDia->faltasMedioDia($this->personal_id,$this->fecha_planilla).' dias';
        }
        public function getfaltasMedioDiaMontoAttribute(){
            $faltasMedioDiaMonto=new PlanillaController();
            return round($faltasMedioDiaMonto->faltasMedioDiaMonto($this->personal_id,$this->fecha_planilla,0.00),2);}
        public function getfaltasMontoTotalAttribute(){
            $faltasMontoTotal=new PlanillaController();
            return round($faltasMontoTotal->faltasSinPermiso($this->personal_id,$this->fecha_planilla,0.00),2);}
        
            

}