<?php

namespace App\Repositories;

use App\Models\Ley;
use App\Repositories\BaseRepository;

/**
 * Class LeyRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class LeyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'valor',
        'unidad',
        'material_id'
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
        return Ley::class;
    }
}
