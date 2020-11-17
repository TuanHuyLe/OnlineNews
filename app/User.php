<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /**
     * Kiểm tra quyền của user
     * CreatedBy: LTQUAN (11/11/2020)
     * @param $key_permission
     * @return bool
     */
    public function checkPermissionAccess($key_permission){
        // user co quyen add, edit danh muc va xem menu
        // B1: lay tat ca cac quyen cua user trong he thong
        $roles = auth()->user()->roles;
        // B2: so sanh gia tri dua vao router hien tai xem co ton tai trong cac quyen ma minh lay duoc hay khong
        foreach ($roles as $role){
            $permissions = $role->permissions;
            if($permissions->contains('key_permission', $key_permission)){
                return true;
            }
        }
        return false;
    }
}
