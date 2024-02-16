<?php

namespace App\Models;

use Eloquent as Model;
use App\Patrones\Fachada;


class Material extends Model
{
    public $table = 'mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'simbolo',
        'nombre',
        'unidad_laboratorio',
        'nandina',
        'margen_error',
        'margen_maximo',
        'con_cotizacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'simbolo' => 'string',
        'nombre' => 'string',
        'unidad_laboratorio' => 'string',
        'margen_error' => 'integer',
    ];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
//        $rule = $isNew ? 'unique:mineral,simbolo' : 'unique:mineral,simbolo,' . request('mineral');
        $rule = $isNew ? 'unique:mineral' : '';

        return [
            'simbolo' => 'required|string|max:10|' . $rule,
            'nombre' => 'required|string|max:20',
            'margen_error' => 'required|numeric|min:0|max:5',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }


    public $appends = ['info', 'cotizacion_diaria'];
    public function getInfoAttribute(){

        return sprintf("%s | %s", $this->simbolo, $this->nombre);
    }

    public function getUltimaUnidadAttribute()
    {
        $cd = CotizacionDiaria::whereMineralId($this->id)->orderByDesc('fecha')->first();

        if(is_null($cd))
            return null;
        else
            return $cd->unidad;
    }

    public function getUltimaCotizacionOficialAttribute()
    {
        $cd = CotizacionOficial::whereMineralId($this->id)->orderByDesc('fecha')->first();

        if(is_null($cd))
            return null;
        else
            return $cd;
    }
    public function getUltimaCotizacionDiariaAttribute()
    {
        $cd = CotizacionDiaria::whereMineralId($this->id)->orderByDesc('fecha')->first();

        if(is_null($cd))
            return null;
        else
            return $cd;
    }

    public function getCotizacionDiariaAttribute(){
        $cd = CotizacionDiaria::whereMineralId($this->id)->whereFecha(date("Y-m-d"))->first();
        if(is_null($cd))
            return null;
        else
            $cd->monto;
    }

    public function cotizacions()
    {
        return $this->hasMany(\App\Models\CotizacionDiaria::class, 'mineral_id');
    }

    public function cotizacionOficiales(){
        return $this->hasMany(CotizacionOficial::class, 'mineral_id');
    }

    public function productoMinerals()
    {
        return $this->hasMany(\App\Models\ProductoMineral::class, 'mineral_id');
    }

    public function liquidacionMinerals(){
        return $this->hasMany(LiquidacionMineral::class);
    }

    public function laboratorios()
    {
        return $this->hasMany(\App\Models\Laboratorio::class, 'mineral_id');
    }

}
