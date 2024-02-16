<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturasImpuestos extends Model
{
    protected $table = "impuestos.facturas_impuestos";

    protected $fillable = [
        'mes',
        'gestion',
        'nroFactura',
        'cuf',
        'cufd',
        'fechaEmision',
        'nombreRazonSocial',
        'numeroDocumento',
        'montoTotal',
        'leyenda',
        'codigoDetalle',
        'descripcionDetalle',
        'cuis',
        'es_enviado',
        'es_anulado',
        'user_id',
        'tipo_factura',
        'cobranza_id'
    ];
}
