<?php

namespace App\Models;

use App\Http\Controllers\CajaController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SaldoDiario extends Model
{
    use HasFactory;

    public $table = 'saldo_diario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'monto_caja',
        'monto_banco',
        'monto_bnb',
        'monto_economico',
        'monto_dolares'
    ];

}
