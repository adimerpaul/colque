<?php

namespace App\Repositories;

use App\Models\CotizacionDiaria;
use App\Repositories\BaseRepository;

/**
 * Class CotizacionRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class CotizacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'monto',
        'unidad',
        'material_id'
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
        return CotizacionDiaria::class;
    }
}
