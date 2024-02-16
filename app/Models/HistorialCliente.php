<?php


namespace App\Models;


use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HistorialCliente extends Model
{
    use HasFactory;

    protected $table = 'historial_cliente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipo',
        'valores_antiguos',
        'valores_nuevos',
        'cliente_id',
        'registrado_id'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }


}
