<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaMineral extends Model
{
    use HasFactory;

    public $table = 'venta_mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'peso_fino',
        'cantidad_extraccion',
        'descripcion_leyes',
        'venta_id',
        'mineral_id',
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Material::class, 'mineral_id', 'id');
    }
}
