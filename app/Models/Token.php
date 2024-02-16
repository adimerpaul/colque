<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'impuestos.tokens';

    protected $fillable = [
        'id',
        'token',
        'fecha_expiracion',
        'created_at',
        'updated_at'
    ];
}
