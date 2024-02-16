<?php

namespace App\Repositories;

use App\Models\DescuentoBonificacion;
use App\Repositories\BaseRepository;

/**
 * Class DescuentoBonificacionRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class DescuentoBonificacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'valor',
        'unidad',
        'en_funcion',
        'tipo'
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
        return DescuentoBonificacion::class;
    }
}
