<?php

namespace App\Models\Lab;
use Eloquent as Model;
class FactorVolumetrico extends Model
{
    public $table = 'laboratorio.factor_volumetrico';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'valor',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'valor' => 'string',
    ];

}
