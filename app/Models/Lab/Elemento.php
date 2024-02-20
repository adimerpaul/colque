<?php

namespace App\Models\Lab;
use Eloquent as Model;
class Elemento extends Model
{
    public $table = 'laboratorio.elemento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'simbolo',
        'nombre',
        'unidad',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'simbolo' => 'string',
        'nombre' => 'string',
        'unidad' => 'string',
    ];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        $rule = $isNew ? 'unique:elemento' : '';

        return [
            'simbolo' => 'required|string|max:10|' . $rule,
            'nombre' => 'required|string|max:20',
            'unidad' => 'required|min:1|max:10',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];
    }


    public $appends = ['info'];
    public function getInfoAttribute(){

        return sprintf("%s | %s", $this->simbolo, $this->nombre);
    }


}
