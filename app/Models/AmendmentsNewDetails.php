<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class AmendmentsNewDetails extends Model implements Auditable
{
    //use SoftDeletes;
    protected $table = 'amendments_new_details';
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'amendments_header_id',
        'lot_no',
        'name',
        'addres',
        'nic_number',
        'size',
        'ownership_type',
        'class',
        'mortgages',
        'other_boudages',
        'type',
        'owner_details_gn_division_id',
        'sub_type',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}



