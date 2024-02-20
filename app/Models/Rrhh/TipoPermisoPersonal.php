<?php

namespace App\Models\Rrhh;

use App\Http\Controllers\rrhh\PermisoController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\Rrhh\TipoPermiso;


class TipoPermisoPersonal extends Model
{
    use HasFactory;
    public $table = 'rrhh.tipo_permiso_personal';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'personal_id',
        'tipo_permiso_id',
        'es_habilitado',
    ];
    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id');
    }
    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class,'tipo_permiso_id');
    }
    public function getCantidadPermisoAttribute(){
        if($this->tipoPermiso->cantidad_dia){
        $cantidadPermiso=$this->tipoPermiso->cantidad_dia;}
        else{$cantidadPermiso=$this->tipoPermiso->cantidad_hora;}
        $permisos= new PermisoController;
        $cantidad=$permisos->cantDatosCorrespondientes($this->personal_id,$this->tipo_permiso_id,$cantidadPermiso);
        return $cantidad;
    }
    public function getPermisosSacadosAttribute(){
        
        $permisos= new PermisoController;
        $cantidad=$permisos->cantidadDatosPermisos($this->personal_id,$this->tipo_permiso_id);
        return $cantidad;
    }
    public function getCantidadActualAttribute(){
        $cantidadPermiso=$this->cantidad_permiso;
        $cantidadSacada=$this->permisos_sacados;
        $total=$cantidadPermiso-$cantidadSacada;
        return $total;
    }

}
