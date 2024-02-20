<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContratoPlantilla extends Model
{
    use HasFactory;

    public $table = 'contrato_plantilla';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion',
        'tipo'
    ];


}
