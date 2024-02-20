<?php

namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    use HasFactory;
    public $table = 'rrhh.hora_extra';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'inicio',
        'fin',
        'descripcion',
        'es_aprobado',
        'personal_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    
}
