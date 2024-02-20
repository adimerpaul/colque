<?php

namespace App\Repositories;

use App\Models\TipoCambio;
use App\Repositories\BaseRepository;

/**
 * Class TipoCambioRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class TipoCambioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'dolar_compra',
        'dolar_venta',
        'ufv',
        'api'
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
        return TipoCambio::class;
    }
}
