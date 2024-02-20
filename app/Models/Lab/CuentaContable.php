<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{

    public $table = 'laboratorio.cuenta_contable';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'descripcion',
        'tipo'
    ];



}
