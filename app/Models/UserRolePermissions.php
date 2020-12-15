<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class UserRolePermissions extends Model implements Auditable
{
    use SoftDeletes;
    protected $table = 'user_role_permissions';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'role_code',
        'module_code',
        'md_group',
        'is_enable',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        /* 'can_process', */
        'can_approve',
        'can_reject',
        'can_proof_read',
        'can_close',
        'updated_at',
        'created_at',
    ];

    protected $rules = [
        'module_code' => 'required',
        'role_code' => 'required',

    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



