<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CambioFormulario extends Model
{
    use HasFactory;

    public $table = 'cambio_formulario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'descripcion',
        'formulario_liquidacion_id',
        'accion'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'descripcion' => 'string',
    ];

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }

}
