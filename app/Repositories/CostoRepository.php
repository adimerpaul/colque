<?php

namespace App\Repositories;

use App\Models\Costo;
use App\Repositories\BaseRepository;

/**
 * Class CostoRepository
 * @package App\Repositories
 * @version January 17, 2021, 8:41 am -04
*/

class CostoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'formulario_liquidacion_id',
        'tratamiento',
        'laboratorio',
        'pesaje',
        'comision',
        'dirimicion',
        'publicidad',
        'pro_productor'
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
        return Costo::class;
    }
}
