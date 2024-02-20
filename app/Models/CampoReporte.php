<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoReporte extends Model
{
    use HasFactory;

    public $table = 'campo_reporte';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nombre',
        'codigo',
        'visible',
        'tipo_reporte_id'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
}
