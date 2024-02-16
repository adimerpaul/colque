<?php

namespace App\Repositories;

use App\Models\CotizacionOficial;
use App\Repositories\BaseRepository;

/**
 * Class CotizacionOficialRepository
 * @package App\Repositories
 * @version December 14, 2020, 7:45 pm -04
*/

class CotizacionOficialRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'monto',
        'unidad',
        'es_aprobado',
        'mineral_id'
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
        return CotizacionOficial::class;
    }
}
