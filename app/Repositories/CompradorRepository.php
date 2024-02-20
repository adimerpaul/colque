<?php

namespace App\Repositories;

use App\Models\Comprador;
use App\Repositories\BaseRepository;

/**
 * Class CompradorRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class CompradorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nit',
        'direccion',
        'razon_social',
        'nro_nim'
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
        return Comprador::class;
    }
}
