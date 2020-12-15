<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Provinces extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'province_name',
        'sinhala_name',
        'provincial_code',
        'is_active', 
        'updated_at',
        'created_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



