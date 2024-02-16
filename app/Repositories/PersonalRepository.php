<?php

namespace App\Repositories;

use App\Models\Personal;
use App\Repositories\BaseRepository;

/**
 * Class PersonalRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class PersonalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ci',
        'ci_add',
        'expedido',
        'nombre_completo',
        'telefono',
        'celular',
        'foto',
        'firma',
        'empresa_id'
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
        return Personal::class;
    }
}
