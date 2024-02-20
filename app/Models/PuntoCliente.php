<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PuntoCliente extends Model
{
    use HasFactory;
    public $table = 'punto_cliente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'valor',
        'descripcion',
        'cliente_id'
    ];

//    public $hidden = ['created_at', 'updated_at'];



    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
