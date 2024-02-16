<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiquidacionMineral extends Model
{
    use HasFactory;

    public $table = 'liquidacion_mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'es_penalizacion',
        'formulario_liquidacion_id',
        'ley_minima',
        'mineral_id',
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'es_penalizado' => 'boolean',
    ];

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Material::class, 'mineral_id', 'id');
    }
}
