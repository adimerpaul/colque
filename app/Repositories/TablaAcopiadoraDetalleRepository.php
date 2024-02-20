<?php

namespace App\Repositories;

use App\Models\TablaAcopiadoraDetalle;
use App\Repositories\BaseRepository;

/**
 * Class TablaAcopiadoraDetalleRepository
 * @package App\Repositories
 * @version June 21, 2021, 1:10 pm -04
*/

class TablaAcopiadoraDetalleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cotizacion',
        'l_5',
        'l_10',
        'l_15',
        'l_20',
        'l_25',
        'l_30',
        'l_35',
        'l_40',
        'l_45',
        'l_50',
        'l_55',
        'l_60',
        'l_65',
        'l_70',
        'l_75',
        'l_80',
        'tabla_acopiadora_id'
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
        return TablaAcopiadoraDetalle::class;
    }
}
