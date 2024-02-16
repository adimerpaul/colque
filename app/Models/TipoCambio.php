<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TipoCambio extends Model
{
    use HasFactory;

    public $table = 'tipo_cambio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'fecha',
        'dolar_compra',
        'dolar_venta',
        'ufv',
        'api'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fecha' => 'date',
        'dolar_compra' => 'decimal:2',
        'dolar_venta' => 'decimal:2',
        'ufv' => 'decimal:2',
        'api' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' =>'required',
        'dolar_compra' => 'numeric|min:1.00|max:99.99',
        'dolar_venta' => 'numeric|min:1.00|max:99.99',
        'ufv' => 'numeric|min:1.00|max:99.99',
    ];


}
