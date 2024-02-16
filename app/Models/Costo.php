<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Costo extends Model
{
    use HasFactory;

    public $table = 'costo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'tratamiento',
        'laboratorio',
        'pesaje',
        'comision',
        'dirimicion',
        'publicidad',
        'pro_productor',
        'formulario_liquidacion_id',
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tratamiento' => 'string',
        'laboratorio' => 'string',
        'pesaje' => 'string',
        'comision' => 'string',
        'publicidad' => 'string',
        'pro_productor' => 'string',
        'dirimicion' => 'string',
    ];

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }

}
