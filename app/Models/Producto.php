<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    public $table = 'producto';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'letra',
        'nombre',
        'costo_tratamiento',
        'costo_pesaje',
        'costo_comision',
        'costo_publicidad',
        'pro_productor',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'letra' => 'string',
        'nombre' => 'string',
        'costo_tratamiento' => 'string',
        'costo_pesaje' => 'string',
        'costo_comision' => 'string',
        'costo_publicidad' => 'string',
        'pro_productor' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'letra' => 'required|string|max:2',
        'nombre' => 'required|string|max:15',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getInfoAttribute(){
        return sprintf("%s | %s", $this->letra, $this->nombre);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function productoMinerals()
    {
        return $this->hasMany(\App\Models\ProductoMineral::class, 'producto_id');
    }

}
