<?php


namespace App\Models;


use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Historial extends Model
{
    use HasFactory;

    protected $table = 'historial';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'fecha',
        'accion',
        'observacion',
        'formulario_liquidacion_id',
        'users_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'id' => 'integer',
        'fecha' => 'datetime',
        'accion' => 'string',
        'observacion' => 'string',
        'formulario_liquidacion_id' => 'integer',
        'users_id' => 'integer'
    ];

    public static $rules = [
        'fecha' => 'required',
        'accion' => 'required',
        'observacion' =>'required',
        'formulario_liquidacion_id' =>'required',
        'users_id' =>'required',
    ];

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class, 'formulario_liquidacion_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
