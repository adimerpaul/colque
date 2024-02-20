<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'rol',
        'alta',
        'ultimo_cambio_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function setPasswordAttribute($value)
    {
        if($value !== null)
            $this->attributes['password'] = bcrypt($value);
    }*/

    public function getItSeftAttribute(){
        return $this->id === auth()->user()->id;
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function accesos(){
        return $this->hasMany(Acceso::class);
    }

    public function hasRol(array $roles)
    {
        foreach ($roles as $rol) {
            if ($this->rol === $rol) {
                return true;
            }
        }
        return false;
    }
}
