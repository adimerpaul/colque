<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{

    public $table = 'municipio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nombre',
        'provincia_id'
    ];

    protected $casts = [
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function localidades()
    {
        return $this->hasMany(\App\Models\Localidad::class, 'municipio_id');
    }
}
