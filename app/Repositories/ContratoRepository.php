<?php

namespace App\Repositories;

use App\Models\Contrato;
use App\Repositories\BaseRepository;

/**
 * Class ContratoRepository
 * @package App\Repositories
 * @version November 23, 2020, 6:53 am -04
 */

class ContratoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'porcentaje_arsenico',
        'porcentaje_antimonio',
        'porcentaje_bismuto',
        'porcentaje_estanio',
        'porcentaje_hierro',
        'porcentaje_silico',
        'porcentaje_zinc',
        'deduccion_elemento',
        'deduccion_plata',
        'porcentaje_pagable_elemento',
        'porcentaje_pagable_plata',
        'maquila',
        'base',
        'escalador',
        'deduccion_refinacion_onza',
        'refinacion_libra_elemento',
        'laboratorio',
        'molienda',
        'manipuleo',
        'margen_administrativo',
        'transporte',
        'roll_back',
        'producto_id'
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
        return Contrato::class;
    }
}
