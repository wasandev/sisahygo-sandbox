<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Pktharindu\NovaPermissions\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
        'branch_rec_id',
        'avatar',
        'mobile',
        'usercode',
        'employee_id',
        'customer_id',
        'created_by',
        'updated_by',
        'department_id'
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
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime'
    ];
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function branch_rec()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_rec_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
    }
    public function assignRole(Role $role)
    {
        return $this->roles()->save($role);
    }
    public function assign_user()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
    public function assign_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function user_create()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    // public function checker()
    // {
    //     return $this->belongsTo('App\Models\User', 'checker_id');
    // }
    // public function loader()
    // {
    //     return $this->belongsTo('App\Models\User', 'loader_id');
    // }
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function order_checkers()
    {
        return $this->hasMany(Order_checker::class);
    }
}
