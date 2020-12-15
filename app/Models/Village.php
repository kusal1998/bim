<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Village extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'gn_division',
        'village',
        'sinhala_name',
        'updated_at',
        'created_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}
