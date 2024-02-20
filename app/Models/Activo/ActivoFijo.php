<?php

namespace App\Models\Activo;

use App\Models\Activo\ActivoFijoBaja;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    use HasFactory;
    public $table = 'activo.activo_fijo';
    protected $fillable = [

    'codigo',
    'descripcion',
    'personal_id',
    'area_trabajo',
    'tipo_id',
    'estado',

    'unidad_medida',
    'fecha_adquisicion',
    'alta',
    'observacion'
    ];

    public static function rules($isNew = true)
    {
        return [
            $isNew?'codigo':'codigo_numero' => 'digits_between:4,5',
        'descripcion' => 'required|min:4|max:500',
        'personal_id' => 'required',
        'area_trabajo' => 'required|max:50',
        'tipo_id' => 'required',
        'estado' => 'required|max:30',
        'cantidad'=>$isNew ?'required|numeric|min:1':'',
        'unidad_medida' => 'required|max:10',
        'fecha_adquisicion' => 'required|date',
        'valor_unitario' =>$isNew ?'required|regex:/^\d+(\.\d{1,2})?$/':'',
        'alta' => 'nullable',
        'observacion' => 'nullable|max:300'
        ];
    }



    public function getCantidadStockAttribute()
    {
        $cantidadBajas = ActivoFijoBaja::
        whereHas('detalle', function ($q) {
            $q->where('activo_fijo_id', $this->id);
        })->sum('cantidad');
        $cantidadAltas = DetalleActivo::whereActivoFijoId($this->id)->sum('cantidad');
        $cantidad = $cantidadAltas - $cantidadBajas;
        return $cantidad;
    }

    public function getPrecioTotalAttribute(){
        return DetalleActivo::whereActivoFijoId($this->id)->get()->sum('precio_total');
    }

    public function getCantidadUnidadAttribute()
    {
        return sprintf("%d %s", $this->cantidad_stock, $this->unidad_medida);
    }

    public function getCodigoNumeroAttribute()
    {
        return substr($this->codigo, 4);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }


}
