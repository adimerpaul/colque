<?php

namespace App\Models;

use App\Http\Controllers\CuentaCobrarController;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;
    public $table = 'cliente';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nit',
        'nombre',
        'celular',
        'firma',
        'password',
        'cooperativa_id',
        'es_asociado',
        'alta',
        'es_aprobado',
        'anverso',
        'reverso',
        'rostro',
        'ultimo_login',
        'es_editable'
    ];

    public $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nit' => 'string',
        'nombre' => 'string',
        'celular' => 'string',
        'firma' => 'string',
        'es_asociado' => 'boolean',
        'cooperativa_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($isNew = true)
    {
        return [
        'nit' => 'required|min:5|max:12'.($isNew ? '|unique:cliente' : ''),
        'nombre' => 'required|min:2|max:100',
        'celular' => 'digits_between:8,10',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
        ];
    }


    public $appends = ['info', 'info_cliente', 'info_cooperativa'];
    public function getInfoAttribute(){
        return sprintf("%s | %s <span class='pull-right'>%s</span>", $this->nit, $this->nombre, $this->cooperativa->razon_social);
    }
    public function getInfoCooperativaAttribute(){
        return sprintf("%s | %s | %s", $this->nit, $this->nombre, $this->cooperativa->razon_social);
    }
    public function getInfoClienteAttribute(){
        if($this->es_deudor)
            return sprintf("<label class='text-muted' style='padding-right:5px; padding-left:5px; padding-top:2px; background-color: #E53935; color: white'>%s | %s </label><br><small class='text-muted'>Productor: %s</small>", $this->nit, $this->nombre, $this->cooperativa->razon_social);
        else
            return sprintf("%s | %s <br><small class='text-muted'>Productor: %s</small>", $this->nit, $this->nombre, $this->cooperativa->razon_social);
    }

    public function getPuedeEliminarseAttribute(){
        $obj = new CuentaCobrarController();
        return $obj->puedeBorrarseCliente($this->id);
    }

    public function getPuedeDarBajaAttribute(){
        $obj = new CuentaCobrarController();
        return $obj->puedeDarBajaCliente($this->id);
    }

    public function getEsDeudorAttribute(){
        $obj = new CuentaCobrarController();
        return $obj->esDeudor($this->id);
    }

    public function getTotalPuntosAttribute(){
        return PuntoCliente::whereClienteId($this->id)->sum('valor');
    }

    public function cooperativa(){
        return $this->belongsTo(Cooperativa::class);
    }



    public function formularioLiquidacions(){
        return $this->hasMany(FormularioLiquidacion::class);
    }

    public function cuenta()
    {
        return $this->morphOne(CuentaCobrar::class, 'origen');
    }
}
