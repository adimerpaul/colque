<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMoneda extends Model
{
    protected $table = "impuestos.tipo_moneda";

    protected $fillable = [
        "id",
        "codigo",
        "descripcion",
        "created_at",
        "updated_at"
    ];
}
