<?php

namespace App\Repositories;

use App\Models\FormularioLiquidacion;
use App\Repositories\BaseRepository;

/**
 * Class FormularioLiquidacionRepository
 * @package App\Repositories
 * @version January 2, 2021, 11:43 am -04
*/

class FormularioLiquidacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'numero_lote',
        'fecha_cotizacion',
        'fecha_liquidacion',
        'producto',
        'peso_bruto',
        'tara',
        'anio',
        'valor_por_tonelada',
        'regalia_minera',
        'peso_seco',
        'estado',
        'observacion',
        'numero_tornaguia',
        'url_documento',
        'aporte_fundacion',
        'sacos',
        'saldo_favor',
        'tipo_cambio_id',
        'cliente_id',
        'chofer_id',
        'vehiculo_id',
        'neto_venta',
        'es_cancelado',
        'fecha_cancelacion',
        'en_molienda',
        'ubicacion',
        'tipo_material',
        'ip_tablet'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FormularioLiquidacion::class;
    }
}
