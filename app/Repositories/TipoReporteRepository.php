<?php

namespace App\Repositories;

use App\Models\TipoReporte;
use App\Repositories\BaseRepository;

/**
 * Class TipoReporteRepository
 * @package App\Repositories
 * @version January 17, 2021, 8:41 am -04
*/

class TipoReporteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'descripcion',
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
        return TipoReporte::class;
    }
}
