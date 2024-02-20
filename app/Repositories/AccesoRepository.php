<?php

namespace App\Repositories;

use App\Models\Acceso;
use App\Repositories\BaseRepository;

/**
 * Class AccesoRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class AccesoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'ip',
        'users_id'
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
        return Acceso::class;
    }
}
