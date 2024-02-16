<?php


namespace App\Models;


use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HistorialVenta extends Model
{
    use HasFactory;

    protected $table = 'historial_venta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'accion',
        'observacion',
        'venta_id',
        'users_id'
    ];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'id' => 'integer',
        'accion' => 'string',
        'observacion' => 'string',
        'venta_id' => 'integer',
        'users_id' => 'integer'
    ];

    public static $rules = [
        'accion' => 'required',
        'observacion' =>'required',
        'venta_id' =>'required',
        'users_id' =>'required',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
