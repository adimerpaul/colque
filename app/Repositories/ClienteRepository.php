<?php

namespace App\Repositories;

use App\Models\Cliente;
use App\Repositories\BaseRepository;

/**
 * Class ClienteRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class ClienteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nit',
        'nombre',
        'celular',
        'firma',
        'razon_social',
        'nro_nim',
        'es_asociado',
        'anverso',
        'reverso',
        'rostro',
        'ultimo_login',
        'es_editable',
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
        return Cliente::class;
    }
}
