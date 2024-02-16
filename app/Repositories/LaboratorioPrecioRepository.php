<?php

namespace App\Repositories;

use App\Models\LaboratorioPrecio;
use App\Repositories\BaseRepository;

/**
 * Class LaboratorioPrecioRepository
 * @package App\Repositories
 * @version January 17, 2021, 8:41 am -04
 */

class LaboratorioPrecioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'monto',
        'producto_id',
        'laboratorio_quimico_id'
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
        return LaboratorioPrecio::class;
    }
}
