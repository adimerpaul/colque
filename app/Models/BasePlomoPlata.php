<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BasePlomoPlata extends Model
{
    use HasFactory;

    public $table = 'base_plomo_plata';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'lme_pb_minimo',
        'lme_pb_maximo',
        'base',
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lme_pb_minimo' => 'string',
        'lme_pb_maximo' => 'string',
        'base' => 'string',
    ];


}
