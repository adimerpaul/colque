<?php

namespace App\Repositories;

use App\Models\Vehiculo;
use App\Repositories\BaseRepository;

/**
 * Class VehiculoRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class VehiculoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'placa',
        'descripcion'
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
        return Vehiculo::class;
    }
}
