<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /*
     * Tham chiếu tới những bản ghi có cùng bảng
     * CreatedBy: LTQUAN (11/11/2020)
     */
    public function permissionChildrent(){
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
