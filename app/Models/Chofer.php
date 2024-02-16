<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Chofer extends Model
{
    use HasFactory;

    public $table = 'chofer';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nombre',
        'licencia',
        'celular'
    ];
    public $appends = ['info'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'licencia' => 'string',
        'celular' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
            'nombre' => 'required|min:2|max:100',
            'licencia' => 'required|min:5|max:20'.($isNew ? '|unique:chofer' : ''),
            'celular' => 'required|digits_between:8,8',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }
    public function getInfoAttribute(){
        return sprintf("%s | %s", $this->licencia, $this->nombre);
    }
}
