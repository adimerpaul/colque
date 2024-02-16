<?php

namespace App\Repositories;

use App\Models\TablaAcopiadora;
use App\Repositories\BaseRepository;

/**
 * Class TablaAcopiadoraRepository
 * @package App\Repositories
 * @version June 10, 2021, 10:55 pm -04
*/

class TablaAcopiadoraRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'gestion',
        'nombre',
        'cotizacion_inicial',
        'cotizacion_final',
        'l_5_incremental',
        'l_5_inicial',
        'l_10_incremental',
        'l_10_inicial',
        'l_15_incremental',
        'l_15_inicial',
        'l_20_incremental',
        'l_20_inicial',
        'l_25_incremental',
        'l_25_inicial',
        'l_30_incremental',
        'l_30_inicial',
        'l_35_incremental',
        'l_35_inicial',
        'l_40_incremental',
        'l_40_inicial',
        'l_45_incremental',
        'l_45_inicial',
        'l_50_incremental',
        'l_50_inicial',
        'l_55_incremental',
        'l_55_inicial',
        'l_60_incremental',
        'l_60_inicial',
        'l_65_incremental',
        'l_65_inicial',
        'l_70_incremental',
        'l_70_inicial',
        'l_75_incremental',
        'l_75_inicial',
        'l_80_incremental',
        'l_80_inicial'
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
        return TablaAcopiadora::class;
    }
}
