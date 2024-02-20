<?php

namespace App\Repositories;

use App\Models\Cooperativa;
use App\Repositories\BaseRepository;

/**
 * Class CooperativaRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class CooperativaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nit',
        'nombre',
        'razon_social',
        'nro_nim',
        'fecha_expiracion',
        'url_documento',
        'tipo_contrato',
        'municipio_id',
        'user_registro_id'
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
        return Cooperativa::class;
    }
}
