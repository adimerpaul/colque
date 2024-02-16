<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoCooperativa extends Model
{
    use HasFactory;

    public $table = 'documento_cooperativa';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'descripcion',
        'agregado',
        'cooperativa_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];



    public function cooperativa()
    {
        return $this->belongsTo(\App\Models\Cooperativa::class, 'cooperativa_id');
    }

}
