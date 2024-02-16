<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FacturaExportacion extends Model
{
    use HasFactory;

    protected $table = 'factura_exportacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'puerto_transito',
        'puerto_destino',
        'pais_destino',
        'incoterm',
        'kilos_netos_humedos',
        'humedad_porcentaje',
        'merma_porcentaje',
        'gastos_realizacion',
        'venta_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];



    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

}
