<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DescuentoCatalogo extends Model
{
    use HasFactory;

    public $table = 'descuento_catalogo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nombre',
    ];

    public $hidden = ['created_at', 'updated_at'];

}
