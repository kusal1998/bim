<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Districts extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'districts_name',
        'sinhala_name',
        'districts_code',
        'province_id',
        'is_active',
        'updated_at',
        'created_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



