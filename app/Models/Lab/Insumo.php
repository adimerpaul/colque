<?php

namespace App\Models\Lab;
use Eloquent as Model;

class Insumo extends Model
{
    public $table = 'laboratorio.insumo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cantidad_minima',
        'nombre',
        'unidad',
    ];


    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
            'nombre' => 'required|string|max:50',
            'unidad' => 'required|min:1|max:10',
            'cantidad_minima' => 'required|numeric|min:1',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
    ];


    public function getStockAttribute(){
        $stock = InventarioInsumo::whereInsumoId($this->id)->get()->sum('cantidad_signo');
        return $stock;
    }

    public function getFechaAttribute(){
        $fecha = date('d/m/Y', strtotime($this->created_at));
        $inventario = InventarioInsumo::whereInsumoId($this->id)->orderByDesc('fecha')->first();
        if(!empty($inventario)){
            $fecha= $inventario->fecha;
            $fecha = date('d/m/Y', strtotime($fecha));
        }
        return $fecha;
    }


}
