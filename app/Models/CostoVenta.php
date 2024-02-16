<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CostoVenta extends Model
{
    use HasFactory;

    public $table = 'costo_venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'monto',
        'venta_id',
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'descripcion' => 'max:100',
        'monto' => 'required|min:0.10|max:99999999.99',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public $hidden = ['created_at', 'updated_at'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

}
