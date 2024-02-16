<?php

namespace App\Repositories;

use App\Models\CampoReporte;
use App\Repositories\BaseRepository;

/**
 * Class CampoReporteRepository
 * @package App\Repositories
 * @version January 17, 2021, 8:41 am -04
*/

class CampoReporteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'visible',
        'tipo_reporte_id'
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
        return CampoReporte::class;
    }
}
