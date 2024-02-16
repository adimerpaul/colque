<?php

namespace App\Repositories;

use App\Models\LaboratorioQuimico;
use App\Repositories\BaseRepository;

/**
 * Class LaboratorioQuimicoRepository
 * @package App\Repositories
 * @version January 17, 2021, 8:41 am -04
 */

class LaboratorioQuimicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'direccion',
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
        return LaboratorioQuimico::class;
    }
}
