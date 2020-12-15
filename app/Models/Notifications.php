<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Notifications extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'notifications';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'created_by',
        'creared_stage',
        'received_by',
        'received_stage',
        'message',
        'url',
        'is_read',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



