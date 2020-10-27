<?php

namespace App\Models;


use Pktharindu\NovaPermissions\Permission as NovaPermission;

class Permission extends NovaPermission
{
    protected $guard_name = 'tenants';
}
