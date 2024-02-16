<?php

namespace App\Models\Lab;
use Eloquent as Model;

class InventarioInsumo extends Model
{
    public $table = 'laboratorio.inventario_insumo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fecha',
        'insumo_id',
        'cantidad',
        'tipo'
    ];


    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required|date',
        'tipo' => 'required',
        'insumo_id' => 'required',
        'cantidad' => 'required|numeric|min:0',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function getCantidadSignoAttribute()
    {
        $cantidad = $this->cantidad;
        if ($this->tipo === 'Egreso') {
            $cantidad = $cantidad * (-1);
        }
        return $cantidad;
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

}
