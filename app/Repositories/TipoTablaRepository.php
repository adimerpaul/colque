<?php

namespace App\Repositories;

use App\Models\TipoTabla;
use App\Repositories\BaseRepository;

/**
 * Class TipoTablaRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class TipoTablaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'valor',
        'tabla'
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
        return TipoTabla::class;
    }
}
