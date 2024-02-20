<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    public $table = 'provincia';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'nombre',
        'departamento_id'
    ];

    protected $casts = [
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function municipios()
    {
        return $this->hasMany(\App\Models\Municipio::class, 'provincia_id');
    }
}
