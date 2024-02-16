<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leyenda extends Model
{
    protected $table = "impuestos.leyendas";

    protected $fillable = [
        "id",
        "codigo",
        "descripcion",
        "created_at",
        "updated_at"
    ];
}
