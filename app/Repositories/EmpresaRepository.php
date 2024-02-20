<?php

namespace App\Repositories;

use App\Models\Empresa;
use App\Repositories\BaseRepository;

/**
 * Class EmpresaRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
*/

class EmpresaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'identificacion_tributaria',
        'razon_social',
        'direccion',
        'email',
        'telefono',
        'celular',
        'alta',
        'logo',
        'cantidad_usuario'
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
        return Empresa::class;
    }
}
