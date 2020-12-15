<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Recheck extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'recheck';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'ref_form_id',
        'recheck_by',
        'recheck_stage',
        'recheck_reason',
        'form_name',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



