<?php

namespace App\Repositories;

use App\Models\Proveedor;
use App\Repositories\BaseRepository;

/**
 * Class ProveedorRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class ProveedorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'nit',
        'empresa'
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
        return Proveedor::class;
    }
}
