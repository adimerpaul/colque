<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoVenta extends Model
{
    use HasFactory;

    public $table = 'documento_venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'agregado',
        'venta_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];



    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id');
    }

}
