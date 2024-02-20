<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Vehiculo extends Model
{
    use HasFactory;
    public $table = 'vehiculo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'placa',
        'marca',
        'color'
    ];
    public $appends = ['info'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'placa' => 'string',
        'marca' => 'string',
        'color' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
            'placa' => 'required|min:6|max:7'.($isNew ? '|unique:vehiculo' : ''),
            'marca' => 'required|string|max:30',
            'color' => 'required|string|max:20',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }


    public function getInfoAttribute(){
        return sprintf("%s | %s", $this->placa, $this->marca);
    }
}
