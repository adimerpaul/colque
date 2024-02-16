<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public $table = 'departamento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nombre',
    ];

    protected $casts = [
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required',
    ];

    public function provincias()
    {
        return $this->hasMany(\App\Models\Provincia::class, 'departamento_id');
    }
}
