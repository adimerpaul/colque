<?php


namespace App\Models;


use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HistorialCuentaCobrar extends Model
{
    use HasFactory;

    protected $table = 'historial_cuenta_cobrar';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'accion',
        'observacion',
        'users_id',
        'origen_id',
        'origen_type'
    ];

    //protected $hidden = ['updated_at'];

    protected $casts = [
        'id' => 'integer',
        'accion' => 'string',
        'observacion' => 'string',
    ];

    public static $rules = [
        'accion' => 'required',
        'observacion' =>'required',
    ];

    public function origen()
    {
        return $this->morphTo();
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
