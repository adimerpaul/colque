<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoMineral extends Model
{
    use HasFactory;

    public $table = 'producto_mineral';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'es_penalizacion',
        'producto_id',
        'mineral_id',
        'ley_minima'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'es_penalizado' => 'boolean',
        'ley_minima' => 'string',

    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function mineral()
    {
        return $this->belongsTo(Material::class, 'mineral_id', 'id');
    }
}
