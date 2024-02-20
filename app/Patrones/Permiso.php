<?php


namespace App\Patrones;


class Permiso
{
    public static function esSuperAdmin(){
        $roles = [Rol::SuperAdmin];
        return auth()->user()->hasRol($roles);
    }

    public static function esAdmin(){
        $roles = [Rol::SuperAdmin, Rol::Administrador];
        return auth()->user()->hasRol($roles);
    }

    public static function esPesaje(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Pesaje];
        return auth()->user()->hasRol($roles);
    }

    public static function esInvitado(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Invitado];
        return auth()->user()->hasRol($roles);
    }

    public static function esLiquidacion(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Liquidacion];
        return auth()->user()->hasRol($roles);
    }

    public static function esComercial(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Comercial];
        return auth()->user()->hasRol($roles);
    }

    public static function esContabilidad(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Contabilidad];
        return auth()->user()->hasRol($roles);
    }
    public static function esCaja(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Caja];
        return auth()->user()->hasRol($roles);
    }

    public static function esOperaciones(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Operaciones];
        return auth()->user()->hasRol($roles);
    }

    public static function esActivos(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Activos];
        return auth()->user()->hasRol($roles);
    }
    public static function esClienteLab(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::ClienteLab];
        return auth()->user()->hasRol($roles);
    }
    public static function esLaboratorio(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Laboratorio];
        return auth()->user()->hasRol($roles);
    }

    public static function esSoloCaja(){
        $roles = [Rol::Caja];
        return auth()->user()->hasRol($roles);
    }
    public static function esJefe(){
        return auth()->user()->personal->es_jefe;
    }
    public static function esRrhh(){
        $roles = [Rol::SuperAdmin, Rol::Administrador, Rol::Rrhh];
        return auth()->user()->hasRol($roles);
    }
    //rutas
    public static function rolSuperAdmin()
    {
        return sprintf("roles:%s", Rol::SuperAdmin);
    }

    public static function rolAdministrador()
    {
        return sprintf("roles:%s,%s", Rol::SuperAdmin, Rol::Administrador);
    }

    public static function rolPesaje()
    {
        return sprintf("roles:%s,%s,%s", Rol::SuperAdmin, Rol::Administrador, Rol::Pesaje);
    }

    public static function rolLiquidacion()
    {
        return sprintf("roles:%s,%s,%s", Rol::SuperAdmin, Rol::Administrador, Rol::Liquidacion);
    }
}
