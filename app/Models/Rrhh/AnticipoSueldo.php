<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use Carbon\Carbon;

class AnticipoSueldo extends Model
{
    use HasFactory;
    public $table = 'rrhh.anticipo_sueldo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'monto',
        'tipo',
        'es_cancelado',
        'es_aprobado',
        'personal_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function pago()
    {
        return $this->morphOne(PagoMovimiento::class, 'origen');
    }

    
}
