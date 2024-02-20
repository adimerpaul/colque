<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Ubicacion extends Model
{
    use HasFactory;

    public $table = 'ubicacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion'
    ];

}
