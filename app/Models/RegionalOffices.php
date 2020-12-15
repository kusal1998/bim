<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class RegionalOffices extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'code',
        'contact_no',
        'contact_person',
        'address',
        'is_active',
        'updated_at',
        'created_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}

