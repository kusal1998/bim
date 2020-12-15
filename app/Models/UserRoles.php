<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class UserRoles extends Model implements Auditable
{ 
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'updated_at',
        'created_at'
    ];

    protected $rules = [
        'name' => 'required',
        'code' => 'required',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



