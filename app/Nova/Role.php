<?php

namespace App\Nova;


use Pktharindu\NovaPermissions\Nova\Role as RoleResource;

class Role extends RoleResource
{
    public static $group = '1.งานสำหรับผู้ดูแลระบบ';
    public static $priority = 4;
    //public static $showColumnBorders = true;

    public static $model = 'App\Models\Role';


    public static function label()
    {
        return __('Roles');
    }
    public static function singulaLabel()
    {
        return __('Role');
    }
}
