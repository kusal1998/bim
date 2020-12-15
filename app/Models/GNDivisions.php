<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use OwenIt\Auditing\Contracts\Auditable;
class GnDivisions extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'gn_name',
        'sinhala_name',
        'gn_code',
        'ag_id',
        'is_active',
        'updated_at',
        'created_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



