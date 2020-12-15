<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Form55Details extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'form_55_detail';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'form_55_header_id',
        'map_no',
        'block_no',
        'lot_no',
        'size',
        'certificate_number',
        'village',
        'registerd_office',
        'rejected',
        'rejected_reason',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



