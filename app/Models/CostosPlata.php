<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CostosPlata extends Model
{
    use HasFactory;

    public $table = 'costos_plata';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'monto',
        'descripcion'
    ];

    public $hidden = ['created_at', 'updated_at'];


}
