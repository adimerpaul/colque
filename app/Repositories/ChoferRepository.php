<?php

namespace App\Repositories;

use App\Models\Chofer;
use App\Repositories\BaseRepository;

/**
 * Class ChoferRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class ChoferRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'licencia'
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
        return Chofer::class;
    }
}
